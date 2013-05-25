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
   <p><a href="http://matthewjamestaylor.com/blog/perfect-multi-column-liquid-layouts" title="Perfect multi-column liquid layouts - iPhone compatible">&laquo; Back to the CSS article</a> by <a href="http://matthewjamestaylor.com">Matthew James Taylor</a></p>
   <h1>The Perfect 3 Column Liquid Layout (Percentage widths)</h1>
   <h2>No CSS hacks. SEO friendly. No Images. No JavaScript. Cross-browser &amp; iPhone compatible.</h2>
   <ul>
      <li><a href="http://matthewjamestaylor.com/blog/perfect-3-column.htm" class="active">3 Column <span>Holy Grail</span></a></li>
      <li><a href="http://matthewjamestaylor.com/blog/perfect-3-column-blog-style.htm">3 Column <span>Blog Style</span></a></li>
      <li><a href="http://matthewjamestaylor.com/blog/perfect-2-column-left-menu.htm">2 Column <span>Left Menu</span></a></li>
      <li><a href="http://matthewjamestaylor.com/blog/perfect-2-column-right-menu.htm">2 Column <span>Right Menu</span></a></li>
      <li><a href="http://matthewjamestaylor.com/blog/perfect-2-column-double-page.htm">2 Column <span>Double Page</span></a></li>
      <li><a href="http://matthewjamestaylor.com/blog/perfect-full-page.htm">1 Column <span>Full Page</span></a></li>
      <li><a href="http://matthewjamestaylor.com/blog/perfect-stacked-columns.htm">Stacked <span>columns</span></a></li>
   </ul>
   <p id="layoutdims">Measure columns in: <a href="http://matthewjamestaylor.com/blog/ultimate-3-column-holy-grail-pixels.htm">Pixel widths</a> | <a href="http://matthewjamestaylor.com/blog/ultimate-3-column-holy-grail-ems.htm">Em widths</a> | <strong>Percentage widths</strong></p>
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
         require_once("Services/Soundcloud.php");

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
               $user_friends = $facebook->api('/me/friends', 'GET');
               $params = array( 'next' => 'http://localhost/MixTape?logout=true',
                              'access_token'=>$facebook->getAccessToken() );

               $logout_url = $facebook->getLogoutUrl($params);
               echo   "<h4 style='display:inline; margin-right: 5px;'>"
                    . "Logged in as <u>" . $user_profile['name'] . "</u></h4>"
                    . "<a href=" . $logout_url . ">Logout</a>";

               echo "<br/><a id='showFriends' href='#' onClick ='loadFriends()' >Show Soundcloud friends:</a>";
               echo "<input id ='size' type='text' size='4' value='50'> / "
                  . count($user_friends["data"])
                  . "</input>";
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
         <div class="col3">
            <!-- Column 3 start ==== RIGHT ==== -->
            <div id="ads">
               <a href="http://matthewjamestaylor.com">
                  <img src="mjt-125x125.gif" width="125" border="0" height="125" alt="Art and Design by Matthew James Taylor" />
               </a>
            </div>
            <h2>Browser Compatibility</h2>
            <p>This 3 column liquid Layout has been tested on the following browsers:</p>
            <h3>iPhone &amp; iPod Touch</h3>
            <ul>
               <li>Safari</li>
            </ul>
            <h3>Mac</h3>
            <ul>
               <li>Safari</li>
               <li>Firefox</li>
               <li>Opera 9.25</li>
               <li>Netscape 9.0.0.5 &amp; 7.1</li>
            </ul>
            <h3>Windows</h3>
            <ul>
               <li>Firefox 1.5, 2 &amp; 3</li>
               <li>Safari</li>
               <li>Opera 8.1 &amp; 9</li>
               <li>Google Chrome</li>
               <li>Explorer 5.5, 6 &amp; 7</li>
               <li>Netscape 8</li>
            </ul>
            <h2>Valid XHTML strict markup</h2>
            <p>The HTML in this layout validates as XHTML 1.0 strict.</p>
            <h2>This layout is FREE for anyone to use</h2>
            <p>That's right, you don't have to pay anything. If you are feeling generous however, link back to this page so other people can find and use this layout too.</p>
            <h2>Centered menus compatible</h2>
            <p>This layout is fully compatible with my <a href="http://matthewjamestaylor.com/blog/beautiful-css-centered-menus-no-hacks-full-cross-browser-support">cross-browser compatible centered menus</a>.</p>
            <!-- Column 3 end -->
         </div>
      </div>
   </div>
</div>
<div id="footer">
   <p>This page uses the <a href="http://matthewjamestaylor.com/blog/perfect-3-column.htm">Perfect 'Holy Grail' 3 Column Liquid Layout</a> by <a href="http://matthewjamestaylor.com">Matthew James Taylor</a>. View more <a href="http://matthewjamestaylor.com/blog/-website-layouts">website layouts</a> and <a href="http://matthewjamestaylor.com/blog/-web-design">web design articles</a>.</p>
</div>

</body>
</html>
