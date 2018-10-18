<?php

class RegisterView {
	private $registerQuery = "register";
    private $regButton = 'DoRegistration';
    private $usernameField = 'RegisterView::UserName';
    private $passwordField = 'RegisterView::Password';
    private $repeatPasswordField = 
        'RegisterView::PasswordRepeat';

	private $message = "";
	private $username = "";

	public function getRegisterQuery() : string {
		return $this->registerQuery;
	}

	public function getRegButton() : string {
		return $this->regButton;
	}

	public function getUsernameField() : string {
		return $this->usernameField;
	}

	public function getPasswordField() : string {
		return $this->passwordField;
	}

	public function getRepeatPasswordField() : string {
		return $this->repeatPasswordField;
	}

	public function setViewMessage(string $message) {
		$this->message = $message;
	}

	public function setViewUsername(string $username) {
		$this->username = $username;
	}
	
	public function getNavLink() {
		return "<a href='?'>Back to login</a>";
	}
    
    public function response() {
        return $this->generateRegFormHTML();
	}

    private function generateRegFormHTML() {
        return "			
			<h2>Register new user</h2>
			<form action='?register' method='post' enctype='multipart/form-data'>
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id='RegisterView::Message'>$this->message</p>
					<label for='$this->usernameField' >Username :</label>
					<input type='text' size='20' name='$this->usernameField' id='$this->usernameField' value='$this->username' />
					<br/>
					<label for='$this->passwordField' >Password  :</label>
					<input type='password' size='20' name='$this->passwordField' id='$this->passwordField' value='' />
					<br/>
					<label for='$this->repeatPasswordField' >Repeat password  :</label>
					<input type='password' size='20' name='$this->repeatPasswordField' id='$this->repeatPasswordField' value='' />
					<br/>
					<input id='submit' type='submit' name='$this->regButton'  value='Register' />
					<br/>
				</fieldset>
			</form>
			";
    }
}