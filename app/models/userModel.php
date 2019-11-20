<?php
namespace App\Models;

class UserModel extends AppModel 
{
	private $salt = "";
	private $userId = 0;
	private $username = "";
	private $email = "";
	private $role = "";
	private $isActivated = false;

	public function __construct()
	{
		parent::__construct();
		
		$this->salt = \Framework\getConfig("password_salt");
	}

	public function generateValidationToken(string $email) : string
	{
		$randomToken = (string) rand() . ";" . $email . ";" . (string) date("d/m/Y");
		$validationToken = md5($randomToken);

		return $validationToken;
	}

	public function register(string $username, string $email, string $password, 
		string $validationToken, string $firstname, string $lastname, string $birthday)
	{
		$password = sha1($password . $this->salt);

		

		$dbHandle = $this->database->prepare("INSERT INTO users (username, password, email, validation_token, firstname, lastname, birthdate) VALUES (?,?,?,?,?,?,?)");
		$dbHandle->bind_param("sssssss", $username, $password, $email, $validationToken, $firstname, $lastname, $birthday);
		$dbHandle->execute();
		
		$dbHandle->close();
	}

	public function setValidationToken(string $email) : string
	{
		$validationToken = $this->generateValidationToken($email);
		
		$dbHandle = $this->database->prepare("UPDATE users SET validation_token = ? WHERE email = ?");
		$dbHandle->bind_param("ss", $validationToken, $email);
		$dbHandle->execute();
		$dbHandle->close();

		return $validationToken;
	}

	public function checkPassword(string $pass1, string $pass2) : bool
	{
		if (strcmp($pass1, $pass2) === 0) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function userExists(string $email) : bool
	{
		$dbHandle = $this->database->prepare("SELECT email from users where email = ?");
		$dbHandle->bind_param("s", $email);
		$dbHandle->execute();

		$result = $dbHandle->get_result();

		if ($result->num_rows == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	Public function checkCredentials(string $email, string $password) : bool
	{
		$password = sha1($password . $this->salt);

		$dbHandle = $this->database->prepare("
		SELECT users.id, users.email, users.username, users.activation_status, role.role 
		FROM users, user_roles AS role
		WHERE email = ? and password = ? AND role.id = users.user_roles_id");
		$dbHandle->bind_param("ss", $email, $password);
		$dbHandle->execute();

		$result = $dbHandle->get_result();

		if ($result->num_rows == 0)
		{
			return false;
		}
		else
		{
			$userData = $result->fetch_assoc();

			$this->userId = $userData["id"];
			$this->username = $userData["username"];
			$this->email = $userData["email"];
			$this->isActivated = $userData["activation_status"];
			$this->role = $userData["role"];

			return true;
		}

		$dbHandle->close();
	}

	public function checkValidationToken(string $email, string $validationToken) : bool
	{
		$dbHandle = $this->database->prepare("SELECT validation_token FROM users WHERE email = ? and validation_token = ? ");
		$dbHandle->bind_param("ss", $email, $validationToken);
		$dbHandle->execute();

		$result = $dbHandle->get_result();

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				if (strcmp($row["validation_token"], $validationToken) === 0) 
				{
					return true;
				}
			}
		}
		$dbHandle->close();

		return false;
	}

	public function activateAccount(string $email, string $validationToken) : bool
	{
		$status = false;

		if ($this->checkValidationToken($email, $validationToken))
		{
			$dbHandle = $this->database->prepare("UPDATE users SET activation_status = 1 WHERE validation_token = ? AND email = ?");
			$dbHandle->bind_param("ss", $validationToken, $email);
			
			$status = $dbHandle->execute();
			
			$dbHandle->close();
		}
		return $status;
	}

	public function changePassword(string $password, string $email)
	{
		$password = sha1($password . $this->salt);

		$dbHandle = $this->database->prepare("UPDATE users SET password = ? WHERE email = ?");
		$dbHandle->bind_param("ss", $password, $email);
		$dbHandle->execute();
		$dbHandle->close();
	}

	public function searchUsers(string $query) : array
	{
		$query = "%" . $query . "%";

		$dbHandle = $this->database->prepare("
			SELECT users.id, users.username, users.email, users.registration_date, roles.role
			FROM users, user_roles AS roles
			WHERE
				roles.id = users.user_roles_id AND (
				username LIKE ? OR 
				email LIKE ? OR 
				registration_date LIKE ? )
			LIMIT 0, 10");

		$dbHandle->bind_param("sss", $query, $query, $query);
		$dbHandle->execute();

		$result = $dbHandle->get_result();

		$searchResults = [];
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$searchResults[] = $row;
			}
		}

		$dbHandle->close();
		
		return $searchResults;
	}

	public function getUsername() : string
	{
		return $this->username;
	}

	public function getEmail() : string
	{
		return $this->email;
	}

	public function getRole() : string
	{
		return $this->role;
	}
	public function getUserId() : string
	{
		return $this->userId;
	}

	public function getActivationStatus() : bool
	{
		return boolval($this->isActivated);
	}

	public function getProfile(string $email) : array
	{
		$email=\Framework\CryptXOR($email);

		$dbHandle = $this->database->prepare("SELECT username, email, firstname, lastname, birthdate, registration_date FROM users WHERE email = ?");
		$dbHandle->bind_param("s", $email);
		$dbHandle->execute();

		$result = $dbHandle->get_result();

		$profile = [];
		if ($result->num_rows > 0)
		{
			$profile = $result->fetch_assoc();
		}

		return $profile;
	} 

	public function updateProfile($email, $firstname, $lastname, $birthday)
	{
		$dbHandle = $this->database->prepare("UPDATE users SET email = ?, firstname = ?, lastname = ?, birthdate = ? WHERE email = ?");
		$dbHandle->bind_param("sssss", $email, $firstname, $lastname, $birthday, $email);
		$dbHandle->execute();
		
		$dbHandle->close();
	}
}  