<?php

class LayoutView {  
  public function render(
    bool $isLoggedIn, 
    $loginView, 
    DateTimeView $dtv, 
    BlogView $blogView
  ) {
    echo '
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>

          <h1>Assignment 2</h1>

          ' . $loginView->getNavLink() . '

          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $loginView->response() . '
              
              ' . $dtv->show() . '
          </div>

          <div class="blog-posts">
            ' . $blogView->display() . '
          </div>

         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn(bool $isLoggedIn) : string {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}
