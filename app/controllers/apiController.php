<?php
namespace App\Controllers;

use App\Models;
use Framework\EmailEngine;

class ApiController extends AppController
{
	private $apiKey = '';
	private $accountId = -1;
	private $method = 'GET';
    protected $model = NULL;
	protected $emailEngine = NULL;

	private function response(int $status, $message)
	{
		header('Content-Type: application/json');
		
		$response = [
			'status' => $status,
			'message' => $message
		];

		$response = json_encode($response);
		exit($response);
	}
	
	private function handleAuthorization()
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
			$this->response(400, 'No API key found!');
		}
	}

	public function __construct(string $action = "", array $params)
	{
		parent::__construct($action, $params);
		$this->action = $action;
		$this->emailEngine = new EmailEngine(true);
	}

	public function accounts(array $params)
	{
		$this->handleAuthorization();

		$accountsModel = new Models\AccountsModel();
		$accounts = $accountsModel->getAccounts($this->accountId);

		$this->response(200, $accounts);
	}

	public function bills(array $params)
	{
		exit('bills');
	}
}
?>