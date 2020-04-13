<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class AccountsController extends AppController
{
    protected $model = NULL;
	protected $emailEngine = NULL;

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

    public function generatePDF()
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

    public function import()
    {
        if ( ! empty($_FILES)) {
            // Parse uploaded file
            $parsedData = [];
            $name = $_FILES["file"]["tmp_name"];
            $size = $_FILES["file"]["size"];
        
            $fh = fopen($name, "r");
            while ($data = fgetcsv($fh, $size, ",")) {
                $parsedData[] = $data;
            }
            fclose($fh);
        
            // Remove headers (first line)
            unset($parsedData[0]);
        
            // Read parsed data and put into database
            $stmt = $mysqli->prepare("INSERT INTO data (name, email, date_of_birth) VALUES (?,?,?)");
            foreach($parsedData as $data) {
                $stmt->bind_param("sss", $data[1], $data[2], $data[3]);
                $stmt->execute();
                $res = $stmt->get_result();
            }
            $stmt->close();
        }

    }

    public function export()
    {
        /*
        // Tell the browser that a file of type text/csv is offered
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        // Disable cache
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        */

        $accountIds = [12,15];
        $transactions = $this->model->getExportInfo($accountIds);
    

        // Print the CSV output (will be directly placed in the file)
        $csv = "";
        $csv .= "id,name,email,date_of_birth\n";
        while($row = $res->fetch_assoc()) {
            $csv .= "{$row["id"]},";
            $csv .= "{$row["name"]},";
            $csv .= "{$row["email"]},";
            $csv .= "{$row["date_of_birth"]}\n";
        }
        echo $csv;

    }

    public function exportXls()
    {
        /*
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: attachment; filename=data.xlsx");

        // Disable cache
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);

        // https://phpspreadsheet.readthedocs.io/en/latest/
        // IMPORTANT: See this (https://github.com/PHPOffice/PhpSpreadsheet/issues/31)
        include_once 'PhpSpreadsheet/autoloader.php';
        include_once 'PhpSpreadsheet/autoload.php';
        include_once 'database.php';

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

   

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Create headers
        $counter = 2;
        $sheet->setCellValue("A1", "id");
        $sheet->setCellValue("B1", "name");
        $sheet->setCellValue("C1", "email");
        $sheet->setCellValue("D1", "date_of_birth");

        // Create body
        while($row = $res->fetch_assoc()) {
            $sheet->setCellValue("A{$counter}", $row["id"]);
            $sheet->setCellValue("B{$counter}", $row["name"]);
            $sheet->setCellValue("C{$counter}", $row["email"]);
            $sheet->setCellValue("D{$counter}", $row["date_of_birth"]);
            
            $counter++;
        }

        $writer = new Xlsx($spreadsheet);
        // $writer->save('data.xlsx'); // Save to file
        $writer->save('php://output'); // Print to STDOUT
        */
    }
   
}
?>