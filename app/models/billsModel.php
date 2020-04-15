<?php
namespace App\Models;

class BillsModel extends AppModel 
{
    private $username = "";
    private $email = "";

    public function __construct()
	{
		parent::__construct();
		
    }


    public function getUsername(string $email) : string
	{
        $email=\Framework\CryptXOR($email);

		$dbHandle = $this->database->prepare("SELECT username FROM users WHERE email = ?");
		$dbHandle->bind_param("s", $email);
        $dbHandle->execute();
        
        $result = $dbHandle->get_result();

        $username = "";
		if ($result->num_rows > 0)
		{
			$username = $result->fetch_assoc()["username"];
        }
        
        return $username;

    }
    
    public function getBills(string $username)
    {
        $dbHandle = $this->database->prepare("SELECT bills.name, bills.amount, bills.date, bills.frequency, bills.paid, accounts.accountname 
        FROM (((bills INNER JOIN accounts ON bills.accounts_id = accounts.id) 
        INNER JOIN users_has_accounts ON accounts.id = users_has_accounts.accounts_id) 
        INNER JOIN users ON users_has_accounts.users_id = users.id) WHERE users.username = ?");
		$dbHandle->bind_param("s", $username);
        $dbHandle->execute();

        $result = $dbHandle->get_result();
        

        $bills = [];
 
		if ($result->num_rows > 0)
		{
            while($row = $result->fetch_assoc()) {
                $bills[] = [
                    "name" => $row["name"],
                    "amount" => $row["amount"],
                    "date" => $row["date"],
                    "frequency" => $row["frequency"],
                    "status" => $row["paid"],
                    "account" => $row["accountname"]
                ];
            }

            return $bills;
        }

    }

      
    public function addBill(string $name, float $amount, string $date, string $frequency,int $accountId, int $billCatId)
    {
        
        $dbHandle = $this->database->prepare("INSERT INTO bills (name, amount, date, frequency, accounts_id, bill_categories_id) VALUES (?,?,?,?,?,?)");
		$dbHandle->bind_param("sdssii", $name, $amount, $date, $frequency, $accountId, $billCatId);
        
        if (!$dbHandle->execute()) {
            echo "Execute failed: (" . $dbHandle->errno . ") " . $dbHandle->error;
        }
        $dbHandle->close();

    }

    public function getBillCategories()
    {
        
		$dbHandle = $this->database->prepare("SELECT id, name FROM bill_categories");
        $dbHandle->execute();
        
        $result = $dbHandle->get_result();

        $categories = [];
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc()) {
                $categories[] = [
                    "name" => $row["name"],
                    "billCatId" => $row["id"]
                ];
            }
        }
        
        return $categories;
    }

    public function getAccounts(int $userid)
    {
        $dbHandle = $this->database->prepare("SELECT accounts.type, accounts.amount, accounts.accountname, accounts.accountnum, accounts.id 
        FROM accounts INNER JOIN users_has_accounts ON accounts.id = users_has_accounts.accounts_id WHERE users_has_accounts.users_id = ?");
		$dbHandle->bind_param("i", $userid);
        $dbHandle->execute();

        $result = $dbHandle->get_result();

        $accounts = [];
		if ($result->num_rows > 0)
		{
            while($row = $result->fetch_assoc()) {
                $accounts[] = [
                    "accountname" => $row["accountname"],
                    "type" => $row["type"],
                    "amount" => $row["amount"],
                    "accountnum" => $row["accountnum"],
                    "accountId" => $row["id"]
                ];
            }
            return $accounts;
        }
    }

}
?>