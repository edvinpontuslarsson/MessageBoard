<?php

class LoginView {
	private $login = 'LoginView::Login';
	private $logout = 'LoginView::Logout';
	private $name = 'LoginView::UserName';
	private $password = 'LoginView::Password';
	private $cookieName = 'LoginView::CookieName';
	private $cookiePassword = 'LoginView::CookiePassword';
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

	public function isRegisterQueryString() : bool {
		return isset($_GET["register"]);
	}
	
	public function getRequestType() : string {
		return $_SERVER["REQUEST_METHOD"];
	}

	public function getNavLink() {
		return '<a href="?register">Register a new user</a>';
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
				<p id="' . $this->$messageId . '">' . $message .'</p>
				<input type="submit" name="' . $this->$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
			return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . $this->$messageId . '">' . $this->message . '</p>
					
					<label for="' . $this->$name . '">Username :</label>
					<input type="text" id="' . $this->$name . '" name="' . $this->$name . '" value="'. $this->username .'" />

					<label for="' . $this->$password . '">Password :</label>
					<input type="password" id="' . $this->$password . '" name="' . $this->$password . '" />

					<label for="' . $this->$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . $this->$keep . '" name="' . $this->$keep . '" />
					
					<input type="submit" name="' . $this->$login . '" value="login" />
				</fieldset>
			</form>
			';
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
	}	
}