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
        $dbHandle = $this->database->prepare("SELECT accounts.type, accounts.amount, accounts.accountname, accounts.accountnum 
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
                    "accountnum" => $row["accountnum"]
                ];
            }
            return $accounts;
        }
    }
}
?>