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
        $dbHandle = $this->database->prepare("SELECT bills.name, bills.amount, bills.date, bills.frequency, accounts.accountname 
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
                    "frequency" => $row["frequency"]
                ];
            }

            return $bills;
        }

    }

}
?>