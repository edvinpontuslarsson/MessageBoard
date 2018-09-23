<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private $wantsToRegister = false;

	public function wantsToRegister($wantsToRegister) {
		$this->wantsToRegister = $wantsToRegister;
	}

	public function isRegisterQueryString() : bool {
		return isset($_GET["register"]);
	}
	
	public function getRequestType() : string {
		return $_SERVER["REQUEST_METHOD"];
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = '';
		
		$response = $this->generateLoginFormHTML($message);

		// this was commented away from start
		// put in if perhaps 
		// $response .= $this->generateLogoutButtonHTML($message);

		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		if (!$this->wantsToRegister) {
			return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
			';
		} else { // TODO: put in RegisterView
			return "
			<!-- TODO: Put above Not logged in-h2 -->
			<a href='?'>Back to login</a>
			
			<h2>Register new user</h2>
			<form action='?register' method='post' enctype='multipart/form-data'>
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id='RegisterView::Message'></p>
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
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
	}	
}