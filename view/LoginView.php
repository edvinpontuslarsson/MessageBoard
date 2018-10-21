<?php

namespace view;

require_once('Environment.php');
require_once('view/RegisterView.php');

class LoginView {
	private $registerView;
	private $login = 'LoginView::Login';
	private $name = 'LoginView::UserName';
	private $password = 'LoginView::Password';
	private $keep = 'LoginView::KeepMeLoggedIn';
	private $messageId = 'LoginView::Message';
	private $message = "";
	private $username = "";

	public function __construct() {
        $this->registerView = new \view\RegisterView();
    }

	public function getLogin() : string {
		return $this->login;
	}

	public function getName() : string {
		return $this->name;
	}

	public function getPassword() : string {
		return $this->password;
	}

	public function getKeep() : string {
		return $this->keep;
	}

	public function getMessageId() : string {
		return $this->messageId;
	}

	public function setViewMessage(string $message) {
		$this->message = $message;
	}

	public function setViewUsername(string $username) {
		$this->username = $username;
	}

	public function getNavLink() {
		return '<a href="?'. $this->registerView->getRegisterQuery()  .'">Register a new user</a>';
	}

	public function response() {
		return $this->generateLoginFormHTML();
	}

	private function generateLoginFormHTML() {
			$enviornment = new \config\Environment();

			return '
			<form method="post" action="'. $enviornment->getIndexUrl() .'"> 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . $this->messageId . '">' . $this->message . '</p>
					
					<label for="' . $this->name . '">Username :</label>
					<input type="text" id="' . $this->name . '" name="' . $this->name . '" value="'. $this->username .'" />

					<label for="' . $this->password . '">Password :</label>
					<input type="password" id="' . $this->password . '" name="' . $this->password . '" />

					<label for="' . $this->keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . $this->keep . '" name="' . $this->keep . '" />
					
					<input type="submit" name="' . $this->login . '" value="login" />
				</fieldset>
			</form>
			';
	}
}