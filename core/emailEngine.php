<?php
namespace Framework;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set up the Smarty Templating Engine
class EmailEngine extends PHPMailer
{
	public function PHPMailer(bool $exceptions)
	{
		parent::__construct($exceptions);

		$this->Host = \Framework\getConfig("email.host");
		$this->Port = \Framework\getConfig("email.port");
		$this->SMTPSecure = \Framework\getConfig("email.tls");
		$this->Username = \Framework\getConfig("email.username");
		$this->Password = \Framework\getConfig("email.password");
	}

	public function sendEmail(string $subject, string $body, bool $html) : bool
	{
		try
		{
			$this->setFrom(
				\Framework\getConfig("email.from.email"),
				\Framework\getConfig("email.from.name")
			);
			
			$this->isHTML("html");
			$this->Subject = $subject;
			$this->Body = $body;
			$this->send();

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}
?>

