<?php

class RegisterView {
    private static $regButton = 'DoRegistration';
    private static $rawUsername = 'RegisterView::UserName';
    private static $rawPassword = 'RegisterView::Password';
    private static $rawPasswordRepeat = 
        'RegisterView::PasswordRepeat';
    private static $errorMessage;

    // TODO: function that returns string if set or ""
    
    public function response() {
        $message = '';

        $response = $this->generateRegFormHTML($message);

        return $response;
    }

    private function generateRegFormHTML(string $message) {
        return "
			<!-- TODO: Put above Not logged in-h2 -->
			<a href='?'>Back to login</a>
			
			<h2>Register new user</h2>
			<form action='?register' method='post' enctype='multipart/form-data'>
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id='RegisterView::Message'> $message </p>
					<label for='RegisterView::UserName' >Username :</label>
					<input type='text' size='20' name='RegisterView::UserName' id='RegisterView::UserName' value='' />
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