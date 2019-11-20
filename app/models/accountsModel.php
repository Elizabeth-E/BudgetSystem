<?php
namespace App\Models;

class AccountsModel extends AppModel 
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
}
?>