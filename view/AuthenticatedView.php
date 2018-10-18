<?php

class AuthenticatedView {
    private $logout = 'LoginView::Logout';
    private $messageId = 'LoginView::Message';
    private $message = "";

    public function getLogout() : string {
        return $this->logout;
    }

    public function setViewMessage(string $message) {
		$this->message = $message;
    }

    public function getNavLink() {
        return ""; // no nav link here
    }

    public function response() {
        return $this->generateLogOutButtonHTML();
    }
    
	private function generateLogoutButtonHTML() {
		return '
			<form  method="post" >
				<p id="' . $this->messageId . '">'. $this->message .'</p>
				<input type="submit" name="' . $this->logout . '" value="logout"/>
			</form>
		';
	}
}