<?php
namespace App\Models;

class MembershipModel extends AppModel 
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
    

}
?>