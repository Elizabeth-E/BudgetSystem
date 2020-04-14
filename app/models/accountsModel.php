<?php
namespace App\Models;

class AccountsModel extends AppModel 
{
    private $username = "";
    private $email = "";
    private $accountname = "";
    private $accountnum = "";
    private $accounttype = "";
    private $amountInAccount = "";

    public function __construct()
	{
		parent::__construct();
		
    }
    
    public function getId(string $email) : string
	{
        $email = \Framework\CryptXOR($email);

		$dbHandle = $this->database->prepare("SELECT id FROM users WHERE email = ?");
		$dbHandle->bind_param("s", $email);
        $dbHandle->execute();
        
        $result = $dbHandle->get_result();

        $id = "";
		if ($result->num_rows > 0)
		{
			$id = $result->fetch_assoc()["id"];
        }
        
        return $id;
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
    
    public function createAccount(int $userid, string $accountname, string $accounttype, float $amount)
    {
        //example
        $dbHandle = $this->database->prepare("INSERT INTO accounts (type, amount, accountname) VALUES (?,?,?)");
		$dbHandle->bind_param("sds", $accounttype,$amount, $accountname);
        $dbHandle->execute();

        $dbHandle = $this->database->prepare("INSERT INTO users_has_accounts (accounts_id, users_id) VALUES ((SELECT id FROM accounts Where accounts.accountname = ?), ?)");
		$dbHandle->bind_param("si", $accountname, $userid);
		$dbHandle->execute();
		
		$dbHandle->close();

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
                    "id" => $row["id"]
                ];
            }
            return $accounts;
        }
    }

    public function getTransactions(int $userid)
    {
        //SELECT transactions.date, transactions.name, transactions.description, transactions.amount, transactions.accounts_id FROM(( transactions INNER JOIN accounts ON transactions.accounts_id=accounts.id) INNER JOIN users_has_accounts ON accounts.id = users_has_accounts.accounts_id) WHERE users_has_accounts.users_id = 15
        $dbHandle = $this->database->prepare("SELECT transactions.date, transactions.name, transactions.description, transactions.amount, transactions.accounts_id 
        FROM(( transactions INNER JOIN accounts ON transactions.accounts_id=accounts.id) INNER JOIN users_has_accounts ON accounts.id = users_has_accounts.accounts_id)
         WHERE users_has_accounts.users_id = ?");
		$dbHandle->bind_param("i", $userid);
        $dbHandle->execute();

        $result = $dbHandle->get_result();

        $transactions = [];
		if ($result->num_rows > 0)
		{
            while($row = $result->fetch_assoc()) {
                $transactions[] = [
                    "date" => $row["date"],
                    "name" => $row["name"],
                    "description" => $row["description"],
                    "amount" => $row["amount"],
                    "accounts_id" => $row["accounts_id"]
                ];
            }
            return $transactions;
        }
    }

    public function insertTransactions(string $accountId, array $csvData) {
        // Dynamically generate placeholders & bind parameters
        $placeholders = '';
        $bindStr = '';
        foreach ($csvData as $csvLine) {
            $count = count($csvLine) +1; // +1 for accountName
            $placeholders .= '(';
            $placeholders .= implode(',', array_fill(0, $count, '?'));
            $placeholders .= '),';
            $bindStr .= 'sssdi';
        }
        $placeholders = rtrim($placeholders, ',');

        // Flatten array from 2D to 1D because bindparams expects a 1D array of parameters
        $testData = [];
        foreach ($csvData as $test) {
            $testData[] = $test[0];
            $testData[] = $test[1];
            $testData[] = $test[2];
            $testData[] = $test[3];
            $testData[] = $accountId;
        }

        // Run query
        $dbHandle = $this->database->prepare("INSERT INTO transactions (date, name, description, amount, accounts_id) VALUES $placeholders");
        if ( ! $dbHandle->bind_param($bindStr, ...$testData)) {
            echo "Binding parameters failed: (" . $dbHandle->errno . ") " . $dbHandle->error;
        }
        
        if ( ! $dbHandle->execute()) {
            echo "Execute failed: (" . $dbHandle->errno . ") " . $dbHandle->error;
        }
        $dbHandle->close();
    }

    public function getExportInfo($accountids)
    {
        // Dynamically generate placeholders & bind parameters
        $count = count($accountids);
        $placeholders = implode(',', array_fill(0, $count, '?'));
        $bindStr = str_repeat('i', $count);
        
        $dbHandle = $this->database->prepare("SELECT accounts_id, date, name, description, amount FROM transactions WHERE accounts_id IN ($placeholders)");
		$dbHandle->bind_param($bindStr, ...$accountids);
        $dbHandle->execute();

        $result = $dbHandle->get_result();
        $transactions = [];
		if ($result->num_rows > 0)
		{
            while($row = $result->fetch_assoc()) {
                $transactions[] = [
                    "accounts_id" => $row["accounts_id"],
                    "date" => $row["date"],
                    "name" => $row["name"],
                    "description" => $row["description"],
                    "amount" => $row["amount"],
                ];
            }
        }
        return $transactions;

    }

}
?>