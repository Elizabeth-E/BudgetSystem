<?php

namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class ApiController extends AppController
{
	protected $apiKey = '';
	protected $accountId = -1;
	protected $username = '';
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
			$this->username = $userModel->getApiUsername($this->accountId);
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
		$this->method = $_SERVER['REQUEST_METHOD'];
	}

	protected function parseJSON(string $jsonString) : array
	{
		$jsonObject = json_decode($jsonString, true);
		if ($jsonObject === null && json_last_error() !== JSON_ERROR_NONE) {
			return [];
		}
		return $jsonObject;
	}
	
	public function accounts(array $params = [])
	{
		$this->handleAuthorization();
		$route = new Accounts($this->action, $this->params, $this);

		switch ($this->method) {
			case 'GET':
				$route->read();
				break;
			case 'POST':
				$route->create();
				break;
			case 'DELETE':
				if ( ! isset($params[1]) || empty($params[1])) 
				{
					$this->response(400, 'An account number is required for deletion.');
				}
				else
				{
					$route->delete($params[1]);
				}
				break;
			default:
				$this->response(400, 'Invalid HTTP Verb');
		}

		if ($this->method == 'DELETE')
		{
			if (isset($params[0]) && $params[0] == 'delete')
			{

			}
		}
	}

	public function bills(array $params = [])
	{
		$this->handleAuthorization();
		$route = new Bills($this->action, $this->params, $this);

		switch ($this->method) {
			case 'GET':
				$route->read();
				break;
			case 'POST':
				$route->create();
				break;
			default:
				$this->response(400, 'Invalid HTTP Verb');
		}

		if ($this->method == 'DELETE')
		{
			if (isset($params[0]) && $params[0] == 'delete')
			{

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

	public function create()
	{
		$accountsModel = new Models\AccountsModel();
		
		$account = $this->parent->parseJSON($_POST['account']);
		if (count($account) ==  0)
		{
			$this->response(400, 'Invalid JSON data submitted!');
		}

		$isCreated = $accountsModel->createAccount(
			$account['userid'],
			$account['accountname'],
			$account['accounttype'],
			$account['amount']
		);

		if ( ! $isCreated)
		{
			$this->response(400, "Account ".$account['userid']." could not be created!");
		}

		$this->response(200, "Account " . $account['userid'] . " has been created!");
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

class Bills extends ApiController {
	private $parent = NULL;

	public function __construct(string $action = "", array $params, $parent)
	{
		parent::__construct($action, $params);
		$this->parent = $parent;
	}

	public function read()
	{
		$BillssModel = new Models\BillsModel();
		$bills = $BillssModel->getBills($this->parent->username);

		$this->response(200, $bills);
	}

	public function create()
	{
		$this->response(200, 'Not implemented yet!');
	}
}
?>