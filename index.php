<?php

// TODO: browse https://phpdelusions.net/
// make sure app is good & safe

/*

"Din kontroller börjar med att fråga lämpliga vyer om vad användaren vill... 
sedan gör den förändringar av tillståndet beroende på det med hjälp utav modellklasser

efter detta kan kontrollern beroende på modellens tillstånd be rätt vyer att skapa HTML"

/var/www/html/1dv610/CourseMaterial/1dv610/lectures/examples

link to register: /index.php?register

A post at that url, sends registration

When you have registered a new user, you get back to the index file,
looks like from start except registered username is label in username field

When logged in:

<h2>Logged in</h2>    <div class="container" >
      <form  method='post' >
			<p id='LoginView::Message'></p>
			<input type='submit' name='LoginView::Logout' value='logout'/>
			</form><p>Wednesday, the 19th of September 2018, The time is 15:12:03</p>    </div>
   </body>

*/
/*
require_once('controller/MainController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mainController = new MainController();
$mainController->initialize();
*/

echo "Live long and prosper";