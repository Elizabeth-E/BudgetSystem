<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class UserController extends AppController
{
	protected $model = NULL;
	protected $emailEngine = NULL;

	public function __construct(string $action = "", array $params)
	{
		parent::__construct($action, $params);

		$this->model = new Models\UserModel();
		$this->action = $action;
		
		$this->emailEngine = new EmailEngine(true);
	}

	public function login(array $params)
	{   
		$alert = false;
		$rememberMeInput = null;

		$this->view->assign("title", "Login");
		$this->view->assign("POST_URL", $this->getUrlSelf());

		if (!empty($_POST))
		{
			$alert = true;

			$email = $_POST["email"];
			$password = $_POST["password"];
			
			// If remember username checkbox is set, save cookie
			if (isset($_POST["remember_username"]))
			{
				$expire = (time() + (3600 * 24) * 30); // 1 month
				\Framework\createCookie("remember_login", $email, $expire);
			}

			if (!empty($email) && !empty($password))
			{
				$authenticated = $this->model->checkCredentials($email, $password);

				if($authenticated && $this->model->getActivationStatus())
				{
					$_SESSION["userId"] = $this->model->getUserId();
					$_SESSION["username"] = \Framework\CryptXOR($this->model->getUsername());
					$_SESSION["email"] = \Framework\CryptXOR($this->model->getEmail());
					$_SESSION["role"] = \Framework\CryptXOR($this->model->getRole());
					
					$this->view->assign("alertType", "success");
					$this->view->assign("alertMessage", "Login successful!");
				

					header("Refresh:2; url=" . BASE_URL . "/user/profile", true, 200); 
				}
				elseif (!$this->model->getActivationStatus())
				{
					$this->view->assign("alertType", "warning");
					$this->view->assign("alertMessage", "Account not yet activated!");
				}
				else
				{
					$this->view->assign("alertType", "warning");
					$this->view->assign("alertMessage", "Password/Username incorrect! Please try again");
				}
				
			}
			else
			{
				$this->view->assign("alertType", "warning");
				$this->view->assign("alertMessage", "Please fill in the entire form!");
			}
		}

		// If remmeber me cookie exists, fill input field
		$rememberMeCookie = \Framework\getCookie("remember_login");
		if (!empty($rememberMeCookie))
		{
			$rememberMeInput = $rememberMeCookie;
		}

		$this->view->assign("rememberMeInput", $rememberMeInput);
		$this->view->assign("alert", $alert);
		$this->view->display("user/login.tpl");
	}

	public function register(array $params)
	{
		$alert = false;

		$this->view->assign("title", "register");
		$this->view->assign("POST_URL", $this->getUrlSelf());

        // Check registration form stuff
        if (!empty($_POST))
        {
            $email = $_POST["email"];
            $username = $_POST["username"];
            $firstname = $_POST["FirstName"];
            $lastname = $_POST["LastName"];
            $birthday = $_POST["birthday"];
            $password = $_POST["password"];
            $passwordConfirm = $_POST["password_confirmation"];
            
            // TODO: What happens if password is empty here?
            $passwordsMatch = $this->model->checkPassword($password, $passwordConfirm);

			if (!empty($email) && !empty($password) && !empty($passwordConfirm) && !empty($username) && $passwordsMatch)
			{
				$alert = true;

                if ($this->model->userExists($email))
                {
                    $this->view->assign("alertType", "warning");
                    $this->view->assign("alertMessage", "User already exists!");
                }
                else
                {
                    // TODO: Make error checking
                    $validationToken = $this->model->generateValidationToken($email);
                    $this->model->register($username, $email, $password, $validationToken, $firstname, $lastname, $birthday);
                    
                    // Send email
                    $this->emailEngine->addAddress($email, $username);
                    
                    $subject = "Activation Email";
                    $body = "<h1>Welcome " . $username . ",</h1>
                            <p>to activate your account please follow the link to our website:<br />
                            <a href=" . BASE_URL . "/user/account_activation/" . $email . "/" . $validationToken . ">Activate account</a></p>";

					$emailSent = $this->emailEngine->sendEmail($subject, $body, true);

					if ($emailSent)
					{
						$this->view->assign("alertType", "success");
						$this->view->assign("alertMessage", "Check email for activation link!");
					}
					else
					{
						$this->view->assign("alertType", "warning");
						$this->view->assign("alertMessage", "You have been registered, but email could not be sent. Contact an administrator.");  
					}
				}
			}
			else
			{
				$alert = true;

				$this->view->assign("alertType", "warning");
				$this->view->assign("alertMessage", "Please fill in the entire form!");
			}
		}

		$this->view->assign("alert", $alert);
		$this->view->display("user/register.tpl");
	}

	private function sendActivationLink(string $email) : bool
	{
		// Send email here
		$validationToken = $this->model->setValidationToken($email);

		// Send email
		$this->emailEngine->addAddress($_POST["email"]);
		$subject = "Password reset requested";
		$body = "<p>A password reset request has been made to your account. If this is not intended for you, ignore the email-address. Otherwise follow the link below to reset yoru password.<br />
				<a href=" . BASE_URL . "/user/reset_password_confirmation/" . $_POST["email"] . "/" . $validationToken . ">Reset password</a></p>";

		$emailSent = $this->emailEngine->sendEmail($subject, $body, true);

		return $emailSent;
	}

	public function account_activation(array $params)
	{   
		$alert = false;

		$this->view->assign("title", "Account activation");

		// Expects an e-mail and activation code, so check length of two
		if (count($params) == 2)
		{
			$alert = true;
			$params = [
				"email" => $params[0],
				"activationToken" => $params[1]
			];

			$isActivated = $this->model->activateAccount($params["email"], $params["activationToken"]);
			if ($isActivated)
			{
				$this->view->assign("alertType", "success");
				$this->view->assign("alertMessage", "Your account has been activated!");
			}
			else
			{
				$this->view->assign("alertType", "warning");
				$this->view->assign("alertMessage", "Account could not be activated or does not exist!");
			}
		}

		$this->view->assign("alert", $alert);
		$this->view->display("user/activation.tpl");
	}

	public function reset_password(array $params)
	{   
		$alert = false;

		if (!empty($_POST))
		{
			$alert = true;

			$emailSent = $this->sendActivationLink($_POST["email"]);

			if ($emailSent)
			{
				$this->view->assign("alertType", "success");
				$this->view->assign("alertMessage", "A password reset link has been sent to your account if it exists; Please check your email.");
			}
			else
			{
				$this->view->assign("alertType", "warning");
				$this->view->assign("alertMessage", "The email could not be send. Contact an administrator.");  
			}
		}

		$this->view->assign("title", "Reset password");

		$this->view->assign("alert", $alert);
		$this->view->assign("POST_URL", $this->getUrlSelf());

		$this->view->display("user/reset_password.tpl");
	}
	
	public function reset_password_confirmation(array $params)
	{   
		$alert = false;

		$params = [
			"email" => $params[0],
			"activationToken" => $params[1]
		];
		$tokenIsValid = $this->model->checkValidationToken($params["email"], $params["activationToken"]);

		if (!empty($_POST))
		{
			$alert = true;

			$email = $params["email"];
			$validationToken = $params["activationToken"];
			$password = $_POST["password"];
			$passwordConfirm = $_POST["password_confirmation"];

			// TODO: What happens if password is empty here?
			$passwordsMatch = $this->model->checkPassword($password, $passwordConfirm);

			if (!empty($email) && !empty($validationToken) && !empty($password) && !empty($passwordConfirm) && $passwordsMatch)
			{
				if ($tokenIsValid)
				{
					$this->model->changePassword($password, $email);
	
					$this->view->assign("alertType", "success");
					$this->view->assign("alertMessage", "Your password has been updated; Try logging in.");
				}
				else
				{
					$this->view->assign("alertType", "danger");
					$this->view->assign("alertMessage", "Token or email-address was invalid!"); 
				}

			}
			else
			{
				$this->view->assign("alertType", "warning");
				$this->view->assign("alertMessage", "Please fill in the entire form!");
			}
		}

		if (!$tokenIsValid)
		{
			$alert = true;

			$this->view->assign("alertType", "danger");
			$this->view->assign("alertMessage", "The token or email-address was invalid.");
		}

		$this->view->assign("title", "Change password");

		$this->view->assign("alert", $alert);
		$this->view->assign("tokenIsValid", $tokenIsValid);
		$this->view->assign("validationToken", $params["activationToken"]);
		$this->view->assign("POST_URL", $this->getUrlSelf());

		$this->view->display("user/reset_password_confirmation.tpl");
	}

	public function edit_profile(array $params)
	{   
        $this->setLayout("authenticated");
        $profile = $this->model->getProfile($_SESSION["email"]);

        $this->view->assign("profile", $profile);
        
    
		if (!empty($_POST))
		{
            $email = $_POST["email"];
            $firstname = $_POST["firstName"];
            $lastname = $_POST["lastName"];
            $birthday = $_POST["dateOfBirth"];
    
            $this->model->updateProfile($email, $firstname, $lastname, $birthday);
            header("Refresh:0; url=" . BASE_URL . "/user/profile", true, 200); 
		}

		$this->view->assign("title", "Edit profile");
		$this->view->assign("POST_URL", $this->getUrlSelf());
		$this->view->display("user/edit_profile.tpl");
	}

	public function logout(array $params)
	{
		session_destroy(); 
		$this->view->assign("title", "Logout");
		$this->view->display("home/index.tpl");
	
	}

	public function search(array $params)
	{
		$this->setLayout("authenticated");

		$this->view->assign("title", "Find members");        
		$this->view->assign("PUT_URL", $this->getUrlSelf());

		$searchResults = [];
		if (!empty($_POST))
		{
			$searchResults = $this->model->searchUsers($_POST["query"]);	
		}

		$this->view->assign("resultsFound", count($searchResults));
		$this->view->assign("searchResults", $searchResults);
		$this->view->display("user/search.tpl");
    }
    
    public function profile(array $params)
    {
        $this->setLayout("authenticated");

        $profile = $this->model->getProfile($_SESSION["email"]);
        $this->view->assign("profile", $profile);

        $this->view->assign("title", "Profile");
        $this->view->display("user/profile.tpl");
    }
}
?>