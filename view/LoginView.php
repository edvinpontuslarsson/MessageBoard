<?php

class LoginView {
	private $login = 'LoginView::Login';
	private $name = 'LoginView::UserName';
	private $password = 'LoginView::Password';
	private $keep = 'LoginView::KeepMeLoggedIn';
	private $messageId = 'LoginView::Message';
	private $message = "";
	private $username = "";

	public function setViewMessage(string $message) {
		$this->message = $message;
	}

	public function setViewUsername(string $username) {
		$this->username = $username;
	}

	public function getNavLink() {
		return '<a href="?register">Register a new user</a>';
	}

	public function response() {
		return $this->generateLoginFormHTML();
	}

	private function generateLoginFormHTML() {
			return '
			<form method="post" > 
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