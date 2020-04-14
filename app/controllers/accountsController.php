<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class AccountsController extends AppController
{
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
        // $accounts = getAccount(int $userid);

        $this->view->assign("username", $username);
        $this->view->assign("accounts", $accounts);
        $this->view->assign("transactions", $transactions);
        $this->view->assign("title", "Accounts");

        $this->view->display("accounts/accountsOverview.tpl");
    }

    public function addAccount(array $params)
    {
		$this->view->assign("title", "Add Account");
        $this->view->assign("POST_URL", $this->getUrlSelf());
        
        $this->setLayout("authenticated");
        $username = $this->model->getUsername($_SESSION["email"]);

        $this->view->assign("username", $username);

        // Check registration form stuff
        if (!empty($_POST))
        {
            \Framework\debug($_SESSION);
            \Framework\debug($_POST);
            // exit();
            $userid = $_SESSION["userId"];
            $accountname = $_POST["accountname"];
            $accounttype = $_POST["accounttype"];
            $amount = doubleval($_POST["amount"]);

            $this->model->createAccount($userid, $accountname, $accounttype, $amount);

        }

		$this->view->display("accounts/addAccounts.tpl");

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
        die('importcsv');
    }

    public function exportcsv(array $params)
    {
        // Get and filter records based on submitted data
        $records = $this->getExportInfo($_POST['accounts']);
        $records = $this->filterExportInfo($_POST['fields'], $records);

        ob_clean(); // Clean buffer

        // Tell the browser that a file of type text/csv is offered
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        // // Disable cache
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);

        // CSV parsing
        $csvHeaders = ['date', 'name', 'description', 'amount'];
        $csvBody = implode(',', $csvHeaders) . "\n";
        
        foreach ($records as $record) {
            foreach ($csvHeaders as $header) {
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
        die('exportxls');
    }

    public function importxls(array $params)
    {
        die('importxls');
    }
}
?>