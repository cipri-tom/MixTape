<?php
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once("facebook-php-sdk/src/facebook.php");
  require_once("Services/Soundcloud.php");

  if (isset($_GET['logout']) && $_GET['logout'] == true) {
     setcookie('PHPSESSID', '', time()-3600, '/');
     session_destroy();
     header('Location: http://localhost/MixTape');
  }

  $config = array(
    'appId' => '183241951834877',
    'secret' => '2395a79d0012892b85ed87ecb617bbf4',
  );

  $facebook = new Facebook($config);
  //$user_id = $facebook->getUser();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
   <title>MixTape</title>
   <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
   <link rel="stylesheet" type="text/css" href="screen.css" media="screen" />
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
            <h2>Percentage dimensions of the holy grail layout</h2>
            <img src="perfect-3-column-dimensions.gif" width="350" height="370" alt="Three column layout dimensions" />
            <p>All the dimensions are in percentage widths so the layout adjusts to any screen resolution. Vertical dimensions are not set so they stretch to the height of the content.</p>
            <h3>Maximum column content widths</h3>
            <p>To prevent wide content (like long URLs) from destroying the layout (long content can make the page scroll horizontally) the column content divs are set to overflow:hidden. This chops off any content that is wider than the div. Because of this, it's important to know the maximum widths allowable at common screen resolutions. For example, if you choose 800 x 600 pixels as your minimum compatible resolution what is the widest image that can be safely added to each column before it gets chopped off? Here are the figures:</p>
            <dl>
               <dt><strong>800 x 600</strong></dt>
               <dd>Left &amp; right columns: 162 pixels</dd>
               <dd>Center page: 357 pixels</dd>
               <dt><strong>1024 x 768</strong></dt>
               <dd>Left &amp; right columns: 210 pixels</dd>
               <dd>Center page: 459 pixels</dd>
            </dl>
            <h2>The nested div structure</h2>
            <p>I've colour coded each div so it's easy to see:</p>
            <img src="perfect-3-column-div-structure.gif" width="350" height="369" alt="Three column layout nested div structure" />
            <p>The header, colmask and footer divs are 100% wide and stacked vertically one after the other. Colmid is inside colmask and colleft is inside colmid. The three column content divs (col1, col2 &amp; col3) are inside colleft. Notice that the main content column (col1) comes before the other columns.</p>
            <!-- Column 1 end -->
         </div>
         <div class="col2">
            <!-- Column 2 start ==== LEFT ==== -->
            <?php
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
                echo "<h4>Logged in as <u>" . $user_profile['name'] . "</u></h4>";
                $params = array( 'next' => 'http://localhost/MixTape?logout=true',
                                 'access_token'=>$facebook->getAccessToken() );

                $logout_url = $facebook->getLogoutUrl($params);
                echo "<a href=" . $logout_url . ">Logout</a>";
                $user_friends = $facebook->api('/me?fields=friends.limit(500)',
                                               'GET');

                echo "<h3>Your facebook friends who are also on Soundcloud:</h3>";

                foreach($user_friends["friends"]["data"] as $user) {
                  // echo "FB: " . $user["name"] . " " . $user["location"];
                  $sc_users_string = $sc_client->get('users',
                     array('q' => $user["name"], 'limit' => 1));
                  $sc_users = json_decode($sc_users_string, $assoc = true);
                  if ($sc_users[0]["full_name"] == $user["name"] )
                     echo $sc_users[0]["full_name"] . "<br />";;
                }
              }
              catch(FacebookApiException $e) {
                // If the user is logged out, you can have a
                // user ID even though the access token is invalid.
                // In this case, we'll get an exception, so we'll
                // just ask the user to login again here.
                $login_url = $facebook->getLoginUrl();
                echo 'Please <a href="' . $login_url . '">login.</a>';
              }
            }
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
