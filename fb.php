<?php
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once("facebook-php-sdk/src/facebook.php");

  $config = array(
    'appId' => '183241951834877',
    'secret' => '2395a79d0012892b85ed87ecb617bbf4',
  );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
?>
<html>
  <head>
    <title>MixTape</title>
  </head>
  <body>

  <?php
    function connect() {
      if($user_id) {
        // We have a user ID, so probably a logged in user.
        // If not, we'll get an exception, which we handle below.
        try {
          $user_profile = $facebook->api('/me','GET');
          echo "Name: " . $user_profile['name'];
          $user_music = $facebook->api('/me/music', 'GET');


        }
        catch(FacebookApiException $e) {
          // If the user is logged out, you can have a
          // user ID even though the access token is invalid.
          // In this case, we'll get an exception, so we'll
          // just ask the user to login again here.
          $login_url = $facebook->getLoginUrl();
          echo 'Please <a href="' . $login_url . '">login.</a>';
          error_log($e->getType());
          error_log($e->getMessage());
        }
      }
      else {
        // No user, print a link for the user to login
        $login_url = $facebook->getLoginUrl();
        echo 'Please <a href="' . $login_url . '">login.</a>';
      }
    }
  ?>

  </body>
</html>
