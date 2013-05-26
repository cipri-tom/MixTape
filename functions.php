<?php
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once("facebook-php-sdk/src/facebook.php");
  require_once("Services/Soundcloud.php");

  $type = 'a';
  $size = 50;

  if (isset($_POST['type']))
     $type = $_POST['type'];
  if (isset($_POST['size']))
     $size = $_POST['size'];
  if (isset($_POST['id']))
     $userId = $_POST['id'];
  if (isset($_POST['artist']))
     $artistQuery = $_POST['artist'];
  if (isset($_POST['img']))
     $img = ($_POST['img'] == "false" ? false : true);      // burn :)


  if ($type == "left")
     fillLeft($size);
  if ($type == "middle")
     fillMiddle($userId, $size);
  if ($type == "artists")
      loadArtists();
  if ($type == "recom")
      loadRecom($artistQuery, $img);


  function fillLeft($limit = 50) {
      $config = array(
        'appId' => '183241951834877',
        'secret' => '2395a79d0012892b85ed87ecb617bbf4',
      );

      $facebook = new Facebook($config);
      $user_id = $facebook->getUser();

      $sc_client = new Services_Soundcloud('defe41aed87b334bb8353082e4e5ae56');

      if($user_id) {
        // We have a user ID, so probably a logged in user.
        // If not, we'll get an exception, which we handle below.
        try {
          $user_profile = $facebook->api('/me','GET');
          $user_friends = $facebook->api("/me?fields=friends.limit($limit)",
                                         'GET');
          $sc_users = array();

          echo "<h3>Your facebook friends who are also on Soundcloud:</h3>";
          echo "<table>";
          foreach($user_friends["friends"]["data"] as $user) {
            // echo "FB: " . $user["name"] . " " . $user["location"];
            $sc_users_string = $sc_client->get('users',
               array('q' => $user["name"], 'limit' => 1));
            $sc_temp = json_decode($sc_users_string, $assoc = true);
            if ($sc_temp[0]["full_name"] == $user["name"] ) {
               echo "<tr>";
               echo "<td>" . utf8_encode($sc_temp[0]["full_name"]) . "</td>";
               echo "<td>" . "<a id=usr-{$sc_temp[0]['id']} class='tracks' href='#'\
                onclick='loadTracks(this,\"" . $sc_temp[0]["full_name"] . "\")'>Tracks</a>";
               echo "</tr>";
               //$sc_users[$sc_temp[0]["full_name"]] = $sc_temp[0]["id"];
            }
          }
          echo "</table>";
        }
        catch(FacebookApiException $e) {
          // If the user is logged out, you can have a
          // user ID even though the access token is invalid.
          // In this case, we'll get an exception, so we'll
          // report an exception.
          echo 'Oops, something went wrong';
        }
      }
      else {
        // No user, print a link for the user to login
        $login_url = $facebook->getLoginUrl();
        echo 'Please <a href="' . $login_url . '">login.</a>';
      }
      return $sc_users;
  } // fillLeft

  function fillMiddle($id, $limit = 50) {
      // the sound cloud client
      $sc_client = new Services_Soundcloud('defe41aed87b334bb8353082e4e5ae56',
                                           'b0334541c5a018d6dd100ab65f824d48');
      $sc_client->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));

      $user_tracks_str = $sc_client->get('tracks', array('user_id' => $id));
      $user_tracks = json_decode($user_tracks_str);

      if (!$user_tracks[0] || count($user_tracks) == 0) {
         //echo "<h3>Sorry, this user has no tracks to show.</h3>";
         return;
      }

      foreach ($user_tracks as $track) {
         $embed_info = json_decode($sc_client->get('oembed',
                                    array('url' => $track->uri, 'limit' => $limit)));
         echo $embed_info->html;
      }
  } // fillMiddle

  function loadArtists() {
      require_once('./lastfmapi/lastfmapi.php');
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
            $user_music   = $facebook->api('/me/music', 'GET');
            $user_artists = array();

            $authVars = array(
                     'apiKey' => 'c261b22bd6e22a07725811c1ba816484',
                     'secret' => '9a6d80370611b9590d674dcdba473a56',
                     );

            $config = array(
                  'enabled' => true,
                  'path' => './lastfmapi/',
                  'cache_length' => 1800
                  );

            $auth = new lastfmApiAuth('setsession', $authVars);
            $apiClass = new lastfmApi();
            $artistClass = $apiClass->getPackage($auth, 'artist', $config);

            $methodVars = array('artist' => '', 'limit' => '5');

            foreach ($user_music["data"] as $entry) {
               $methodVars['artist'] = $entry["name"];

               if ($artists = $artistClass->getSimilar($methodVars))
                  foreach ($artists as $similar) {
                     $user_artists[$entry["name"]][] = $similar["name"];
                  }
            } // for

            echo  "<input type=\"checkbox\" id='imgChk' checked='true' /><span style='font-size:87%;'>Images(Uncheck if on a slow connection)</span><br/>";

            echo "<table>";
            echo "<tr>";
            echo "<td><u><b>Your artists</b></u></td><td><u><b>Min Matches</b></u></td>";
            echo "</tr>";
            foreach ($user_artists as $orig => $list) {
               echo   "<tr><td>$orig</td><td>"
                    . "<a href='#' id=\"$orig\" onclick=loadRecom(this)>View("
                    . count($list) . ")</a></td></tr>";
            }
            echo "</table>";

         } // try
         catch(FacebookApiException $e) {
            echo 'Oops, something went wrong';
         }
      }
      else {
        // No user, print a link for the user to login
        $login_url = $facebook->getLoginUrl();
        echo 'Please <a href="' . $login_url . '">login.</a>';
      }
  } // loadArtists

  function loadRecom($artistQuery, $img) {
      if (!$artistQuery)
         return;
      require_once('./lastfmapi/lastfmapi.php');

      $authVars = array(
                     'apiKey' => 'c261b22bd6e22a07725811c1ba816484',
                     'secret' => '9a6d80370611b9590d674dcdba473a56',
                     );

      $config = array(
            'enabled' => true,
            'path' => './lastfmapi/',
            'cache_length' => 1800
            );

      $auth = new lastfmApiAuth('setsession', $authVars);
      $apiClass = new lastfmApi();
      $artistClass = $apiClass->getPackage($auth, 'artist', $config);
      echo "<h3><u>Artists similar to $artistQuery:</u></h3><table><tr>";
      $methodVars = array('artist' => $artistQuery);
      $index = 1;
      if ($artists = $artistClass->getSimilar($methodVars))
         foreach ($artists as $similar) {
            $elem = "<td>";
            var_dump($similar);
            break;
            // if ($img && ! ($info = $artistClass->getInfo(array('artist' => $similar["name"])))
            //     || (!($image = $info["image"]["large"]) &&
            //         !($image = $info["image"]["medium"])))
            //       $image = "./images/noimage.jpg";

            // if ($img)
            //    $elem = $elem . "<img src=\"$image\" width=200px/>";
            // echo $elem . "{$similar['name']}</td>";
            // if ($index % 3 == 0)
            //    echo "</tr>";
            $index++;
         }
      if ($index % 3 != 1)
         echo "</tr>";
      echo "</table>";
  }
?>
