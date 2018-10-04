<?php

// TODO: do this less hardcoded and use premade functions
// Can have this in LoginView, perhaps

class InsideView {
    private $message = "";

    public function setViewMessage(string $message) {
		$this->message = $message;
    }

    public function getNavLink() {
        return ""; // no nav link here
    }

    public function response() {
        return $this->generateLogOutButtonHTML();
    }
    
    private function generateLogOutButtonHTML() {
        return "
            <form  method='post' >
            <p id='LoginView::Message'>$this->message</p>
            <input type='submit' name='LoginView::Logout' value='logout'/>
            </form>
        ";
    }
}