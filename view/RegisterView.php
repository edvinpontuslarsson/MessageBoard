<?php

class RegisterView {
    private static $regButton = 'DoRegistration';
    private static $rawUsername = 'RegisterView::UserName';
    private static $rawPassword = 'RegisterView::Password';
    private static $rawPasswordRepeat = 
        'RegisterView::PasswordRepeat';

	private $message = "";
	private $username = "";

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
        $message = '';

        $response = $this->generateRegFormHTML($message);

        return $response;
    }

    private function generateRegFormHTML(string $message) {
        return "			
			<h2>Register new user</h2>
			<form action='?register' method='post' enctype='multipart/form-data'>
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id='RegisterView::Message'>$this->message</p>
					<label for='RegisterView::UserName' >Username :</label>
					<input type='text' size='20' name='RegisterView::UserName' id='RegisterView::UserName' value='$this->username' />
					<br/>
					<label for='RegisterView::Password' >Password  :</label>
					<input type='password' size='20' name='RegisterView::Password' id='RegisterView::Password' value='' />
					<br/>
					<label for='RegisterView::PasswordRepeat' >Repeat password  :</label>
					<input type='password' size='20' name='RegisterView::PasswordRepeat' id='RegisterView::PasswordRepeat' value='' />
					<br/>
					<input id='submit' type='submit' name='DoRegistration'  value='Register' />
					<br/>
				</fieldset>
			</form>
			";
    }
}