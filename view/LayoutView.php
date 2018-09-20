<?php


class LayoutView {
  
  public function render($isLoggedIn, LoginView $loginView, DateTimeView $dtv) {
    echo '
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>My Login Example</title>
        </head>
        <body>

          <!-- TODO: Make this conditional -->
          <a href="?register">Register a new user</a>

          <h1>Assignment 2</h1>
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $loginView->response() . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}
