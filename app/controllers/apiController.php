<?php

url: api.php?endpoint=say
body: "Hello, world!"

if ($_GET['endpoint'] == 'say') {
	echo $_POST['body'];
}


namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class ApiController extends AppController
{
	protected $apiKey = '';
	protected $accountId = -1;
	protected $method = 'GET';
    protected $model = NULL;
	protected $emailEngine = NULL;

	protected function response(int $status, $message)
	{
		header('Content-Type: application/json');
		
		$response = [
			'status' => $status,
			'message' => $message
		];

		$response = json_encode($response);
		exit($response);
	}
	
	protected function handleAuthorization()
	{
		$this->method = $_SERVER['REQUEST_METHOD'];

		// See if API key is set
		if (isset($_SERVER['HTTP_X_API_KEY']) && strlen($_SERVER['HTTP_X_API_KEY']) == 32)
		{
			$this->apiKey = $_SERVER['HTTP_X_API_KEY'];

			$userModel = new Models\UserModel();
			$this->accountId = $userModel->getApiUserId($this->apiKey);
		}
		
		// Error if account does not exist
		if ($this->accountId == -1)
		{
			$this->response(300, 'No API key found!');
		}
	}

	public function __construct(string $action = "", array $params)
	{
		parent::__construct($action, $params);
		$this->action = $action;
		$this->emailEngine = new EmailEngine(true);
	}

	public function accounts(array $params = [])
	{
		$this->handleAuthorization();
		$route = new Accounts($this->action, $this->params, $this);

		if ($this->method == 'GET')
		{
			if (isset($params[0]) && $params[0] == 'read')
			{
				$route->read();
			}
		}

		if ($this->method == 'DELETE')
		{
			if (isset($params[0]) && $params[0] == 'delete')
			{
				if ( ! isset($params[1])) {
					$this->response(400, 'An account number is required for deletion.');
				}

				$route->delete($params[1]);
			}
		}
	}
}

class Accounts extends ApiController {
	private $parent = NULL;

	public function __construct(string $action = "", array $params, $parent)
	{
		parent::__construct($action, $params);
		$this->parent = $parent;
	}

	public function read()
	{
		$accountsModel = new Models\AccountsModel();
		$accounts = $accountsModel->getAccounts($this->parent->accountId);

		$this->response(200, $accounts);
	}

	public function delete($id)
	{
		$accountsModel = new Models\AccountsModel();
		$isRemoved = $accountsModel->deleteAccount([$id]);

		if ( ! $isRemoved)
		{
			$this->response(400, "Account $id could not be removed!");
		}

		$this->response(200, "Account $id has been removed!");
	}
}
?>