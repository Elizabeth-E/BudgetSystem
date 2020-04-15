<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AccountsController extends AppController
{
    private $tableHeaders = ['date', 'name', 'description', 'amount'];
    protected $model = NULL;
    protected $emailEngine = NULL;

    private function getExportInfo(array $accounts) {
        // Turn all selected accounts into array
        $accountIds = [];
        foreach ($accounts as $account) {
            $accountIds[] = $account;
        }

        // Fetch all export info for array of accounts
        $records = [];
        if (count($accountIds) > 0) {
            $records = $this->model->getExportInfo($accountIds);
        }

        return $records;
    }

    private function filterExportInfo(array $fields, array $records) {
        $fields = [];
        if (count($records) > 0) {
            foreach ($_POST['fields'] as $key => $val) {
                $fields[] = $key;
            }
        }

        // Only filter selected fields
        $filteredData = [];
        foreach ($records as $record) {
            $filteredData[] = array_intersect_key(
                $record,
                array_flip($fields)
            );
        }

        return $filteredData;
    }

    private function setContentHeader(string $contentType, string $fileName) {
        ob_clean(); // Clean buffer

        // Tell the browser that a file of type text/csv is offered
        header("Content-Type: $contentType; charset=utf-8");
        header("Content-Disposition: attachment; filename=$fileName");

        // // Disable cache
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
    }

	public function __construct(string $action = "", array $params)
	{
		parent::__construct($action, $params);

		$this->model = new Models\AccountsModel();
		$this->action = $action;
		
		$this->emailEngine = new EmailEngine(true);
    }
    
    public function accountOverview(array $params)
    {
        $this->setLayout("authenticated");
        
        $username = \Framework\CryptXOR($_SESSION["username"]);
        $accounts = $this->model->getAccounts((int) $_SESSION["userId"]);
        $transactions = $this->model->getTransactions((int) $_SESSION["userId"]);

        // Check registration form stuff
        if (!empty($_POST))
        {
            $userid = $_SESSION["userId"];
            $accountname = $_POST["accountname"];
            $accounttype = $_POST["accounttype"];
            $amount = doubleval($_POST["amount"]);
            unset($_POST);

            $this->model->createAccount($userid, $accountname, $accounttype, $amount);
            header("Refresh:0; url=" . BASE_URL . "/accounts/accountOverview", true, 200); 
        }

        $this->view->assign("username", $username);
        $this->view->assign("accounts", $accounts);
        $this->view->assign("transactions", $transactions);
        $this->view->assign("POST_URL", $this->getUrlSelf());
        $this->view->assign("title", "Accounts");

        $this->view->display("accounts/accountsOverview.tpl");
    }

    public function deleteAccounts(array $params)
    {
        $accountids = $_POST["accounts"];
        $hasWorked = $this->model->deleteAccount($accountids);

        $this->view->display("accounts/accountsOverview.tpl");

        header("Refresh:0; url=" . BASE_URL . "/accounts/accountOverview", true); 
    }

    public function generatePDF(array $params)
    {
        $accounts = $this->model->getAccounts((int) $_SESSION["userId"]);
        $username = \Framework\CryptXOR($_SESSION["username"]);

        //generate pdf of users accounts overview
        $pdf = new \FPDF('P','mm','A4');

        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(60, 10, $username,0,1,'C');
        foreach($accounts as $item)
        {
                $pdf->Cell(60, 10, $item['accountname'],0,1,'C');
                $pdf->Cell(60, 10, $item['accountnum'],0,1,'C');
                $pdf->Cell(60, 10, $item['amount'],0,1,'C');
                $pdf->Cell(60, 10, $item['type'],0,1,'C');

        }
        
        $url = 'http://www.qr-genereren.nl/qrcode.jpg?text='.$username.'&foreColor=000000&backgroundColor=ffffff&moduleSize=16&padding=0&&download=1';
        $content = file_get_contents($url);

        file_put_contents('../webroot/img/qr.jpg', $content);

        $pdf->Image('../webroot/img/qr.jpg', 160, 75, 20, 20);

        $pdf->Output();

    }

    public function importcsv(array $params)
    {
        $csvData = [];
        $parsedSuccessfully = false;

        // Parse uploaded CSV-file
        if ( ! empty($_FILES)) {
            $name = $_FILES["file"]["tmp_name"];
            $size = $_FILES["file"]["size"];

            // Open file and get CSV-data
            $fh = fopen($name, "r");
            while ($data = fgetcsv($fh, $size, ",")) {
                // Filter out unchecked fields
                foreach ($this->tableHeaders as $key => $val) {
                    // If field not in array, clear out
                    if ( ! in_array($key, $_POST['fields'])) {
                        $data[$key] = NULL;
                    }
                }
                $csvData[] = $data;
            }
            fclose($fh);

            // If CSV parsing worked, set to true
            if (count($csvData) == true) {
                unset($csvData[0]); // Remove headers (first line)
                $parsedSuccessfully = true;
            }
        }

        $response = 'fail';
        if ($parsedSuccessfully && $this->model->insertTransactions($_POST['accountname'], $csvData)) {
            $response = 'success';
        }
        header("Refresh:0; url=" . BASE_URL . "/accounts/accountOverview/$response", true);
    }

    public function exportcsv(array $params)
    {
        // Get and filter records based on submitted data
        $records = $this->getExportInfo($_POST['accounts']);
        $records = $this->filterExportInfo($_POST['fields'], $records);

        // Set content header
        $this->setContentHeader('text/csv', 'export.csv');

        // CSV parsing
        $csvBody = implode(',', $this->tableHeaders) . "\n";
        
        foreach ($records as $record) {
            foreach ($this->tableHeaders as $header) {
                if ( ! isset($record[$header])) {
                    $csvBody .= ',';
                } else {
                    $csvBody .= $record[$header] . ',';
                }
            }
            $csvBody = rtrim($csvBody, ',');
            $csvBody .= "\n";
        }

        die($csvBody);
    }

    public function exportxls(array $params)
    {
        // Get and filter records based on submitted data
        $records = $this->getExportInfo($_POST['accounts']);
        $records = $this->filterExportInfo($_POST['fields'], $records);

        // Set content header
        $this->setContentHeader('application/vnd.ms-excel', 'export.xlsx');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Create headers
        $counter = 2;

        $sheet->setCellValue("A1", 'date');
        $sheet->setCellValue("B1", 'name');
        $sheet->setCellValue("C1", 'description');
        $sheet->setCellValue("D1", 'amount');

        // Look at first record to get available headers
        $availableHeaders = [];
        foreach ($this->tableHeaders as $header) {
            if (isset($records[0][$header])) {
                $availableHeaders[] = $header;
            }
        }

        // Create body
        foreach ($records as $record) {
            if (in_array('date', $availableHeaders)) {
                $sheet->setCellValue("A{$counter}", $record['date']);
            } else {
                $sheet->setCellValue("A{$counter}", '');
            }

            if (in_array('name', $availableHeaders)) {
                $sheet->setCellValue("B{$counter}", $record['name']);
            } else {
                $sheet->setCellValue("B{$counter}", '');
            }

            if (in_array('description', $availableHeaders)) {
                $sheet->setCellValue("C{$counter}", $record['description']);
            } else {
                $sheet->setCellValue("C{$counter}", '');
            }

            if (in_array('amount', $availableHeaders)) {
                $sheet->setCellValue("D{$counter}", $record['amount']);
            } else {
                $sheet->setCellValue("D{$counter}", '');
            }

            $counter++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output'); // Print to STDOUT
    }

    public function importxls(array $params)
    {
        die('importxls');
    }
}
?>