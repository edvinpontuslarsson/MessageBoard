<?php


class LayoutView {
  
  public function render($isLoggedIn, LoginView $v, DateTimeView $dtv) {
    echo '
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>My Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response() . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  } // <link rel="icon" href="favicon.ico" type="image/x-icon">
  
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}
