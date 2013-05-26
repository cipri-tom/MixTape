<?php
// on logout
  if (isset($_GET['logout']) && $_GET['logout'] == true) {
     setcookie('PHPSESSID', '', time()-3600, '/');
     session_destroy();
     header('Location: http://localhost/MixTape');
  }

include("functions.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
   <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
   <title>MixTape</title>
   <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
   <link rel="stylesheet" type="text/css" href="screen.css" media="screen" />
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <script type="text/javascript" src='./load.js'></script>
</head>
<body>

<div id="header">
   <h1>All your friends, all their music</h1>
   <h2>See what your friends have shared on Soundcloud and listen for free to their music</h2>
   <ul>
      <li><a href='index.php'>Friends on<br/>SoundCloud</a></li>
      <li><a href='recom.php'>Recommendations<br/>for my taste</a></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
   </ul>
   <p id="layoutdims">Under construction | Contact: <a href="mailto:ciprian.tomoiaga@gmail.com">email</a> | Cipri Tom</p>
</div>
<div class="colmask threecol">
   <div class="colmid">
      <div class="colleft">
         <div class="col1">
            <!-- Column 1 start ==== MIDDLE ==== -->

            <!-- Column 1 end -->
         </div>
         <div class="col2">
         <!-- Column 2 start ==== LEFT ==== -->
         <?php
         // do initialisations any log the user in
         require_once("facebook-php-sdk/src/facebook.php");

         $config = array(
            'appId' => '183241951834877',
            'secret' => '2395a79d0012892b85ed87ecb617bbf4',
         );

         $facebook = new Facebook($config);
         $user_id = $facebook->getUser();

         if($user_id) {
            // We have a user ID, so probably a logged in user.
            // If not, we'll get an exception, which we handle below.
            try {
               $user_profile = $facebook->api('/me','GET');
               $params = array( 'next' => 'http://localhost/MixTape?logout=true',
                              'access_token'=>$facebook->getAccessToken() );

               $logout_url = $facebook->getLogoutUrl($params);
               echo   "<h4 style='display:inline; margin-right: 5px;'>"
                    . "Logged in as <u>" . $user_profile['name'] . "</u></h4>"
                    . "<a href=" . $logout_url . ">Logout</a>";

            }
            catch(FacebookApiException $e) {
               // If the user is logged out, you can have a
               // user ID even though the access token is invalid.
               // In this case, we'll get an exception, so we'll
               // just ask the user to login again here.
               $login_url = $facebook->getLoginUrl();
               echo 'Please <a href="' . $login_url . '">login.</a>';
            }
         } /// if
         else {
            // No user, print a link for the user to login
            $login_url = $facebook->getLoginUrl();
            echo 'Please <a href="' . $login_url . '">login.</a>';
         }
         ?>
         <!-- Column 2 end -->
         </div>
         <div class="col3" style="color:#CCC">
            <!-- Column 3 start ==== RIGHT ==== -->
            <div id="ads">
               <p>ADS ADS ADS ADS ADS ADS ADS ADS  </p>
            </div>
            <h2>Some other cool features here</h2>
            <p>Or maybe some ads</p>
            <img src='images/tape.gif'/>
            <!-- Column 3 end -->
         </div>
      </div>
   </div>
</div>
<div id="footer">
   <p>Footer @ UnderConstruction</p>
</div>

</body>
</html>
