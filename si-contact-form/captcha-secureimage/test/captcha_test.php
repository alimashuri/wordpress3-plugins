<?php
/*
Captcha Test Script
Version 1.0 Mike Challis 08/31/2009
http://www.642weather.com/weather/scripts.php

Upload this PHP script to your web server and call it from the browser.
The script will test the captcha

*/
//error_reporting(E_ALL ^ E_NOTICE); // Report all errors except E_NOTICE warnings
error_reporting(E_ALL); // Report all errors and warnings (very strict, use for testing only)
ini_set('display_errors', 1); // turn error reporting on

// start a session cookie
if( !isset( $_SESSION ) ) {
    session_start();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Captcha Test</title>
<style type="text/css" media="all">
body
{
	background-color:#E6E6E6;
	font-family:"Courier New", Arial, sans-serif, monospace;
	font-size:1em;
	color:#333333;
}
.group
{
	background-color:#FFFFFF;
	border:1px #CCCCCC solid;
	margin-top:25px;
	margin-bottom:50px;
	text-align:left;
}

.errors {
         color: #ff0000;
}



.title {
       color: black;
       font-weight: bold;
       font-size: 90%;
       margin-top: 2px;
       margin-bottom: 5px;
       }

.field {
        color: black;
        font-size: 90%;
        margin-bottom: 7px;
        }

</style>
</head>

<body>

<div class="group" style="margin-left:20%; margin-right:20%; padding:20px;">
<h2>CAPTCHA Test</h2>

<?php

// DISPLAY THE VARS
/*
echo "<pre>";
echo "COOKIE ";
var_dump($_COOKIE);
echo "\n\n";
echo "SESSION ";
var_dump($_SESSION);
echo "</pre>\n";
*/

//phpinfo();


$error = 0;
$hide_form = 0;
$error_captcha = '';
$error_print = '';

// Test for some required things, print error message if not OK.
$requires = 'ok';
if ( !extension_loaded("gd") ) {
       echo '<p class="errors">ERROR: GD image support not detected in PHP!</p>';
       echo '<p>Contact your web host and ask them why GD image support is not enabled for PHP.</p>';
       $requires = 'fail';
}
if ( !function_exists("imagepng") ) {
       echo '<p class="errors">ERROR: imagepng function not detected in PHP!</p>';
       echo '<p>Contact your web host and ask them why the imagepng function is not enabled for PHP.</p>';
       $requires = 'fail';
}
if ( !file_exists("../securimage.php") ) {
       echo '<p class="errors">ERROR: captcha_library not found</p>';
       $requires = 'fail';
}

if ($requires == 'fail') {
  exit;
}

// process form now
if (isset($_POST['action']) && ($_POST['action'] == 'check')) {
   $code    = htmlspecialchars(strip_tags($_POST['code']));
   // check the cookie can be read
   if (!isset($_SESSION['securimage_code_ctf_1']) || empty($_SESSION['securimage_code_ctf_1'])) {
          $error = 1;
          $error_captcha = 'Could not read CAPTCHA cookie. Make sure you have cookies enabled and not blocking in your web browser settings.';
   }else{
      // begin captcha check
      if(empty($code)) {
         $error = 1;
         $error_captcha = 'A CAPTCHA phrase is required.';
      } else {
         include "../securimage.php";
         $img = new Securimage();
         $valid = $img->check("$code");
         // Check, that the right CAPTCHA password has been entered, display an error message otherwise.
         if($valid == true) {
             // ok can continue
         } else {
           $error = 1;
           $error_captcha = 'You entered in the wrong Captcha phrase.<br />Sometimes the letters are hard to read. Please try again';
         }
      }
   }
  // end captcha check

  if (!$error) {
    echo '<p style="background-color:#99CC66; padding:10px;">Test Passed. The CAPTCHA matched, all is good.</p>
    ';
    $hide_form = 1;
  }

}

if (!$hide_form) {

 echo'

 <p>
  This test will check the function of the CAPTCHA outside of the WordPress environment.
  If you are having a problem with the CAPTCHA, this test can help rule out a conflict with another plugin.
  To begin the test, type the phrase in the CAPTCHA field and click "submit", then see if the test passes.

  <br /><br />
Note: If you see any errors or warnings at the top of the page,
especially "Warning: session_start...", they could be indicating a problem with your PHP server that will prevent the CAPTCHA from working.

 <br /><br />
Note: If the CAPTCHA image is missing the text, go to the plugin settings and check the setting "Disable CAPTCHA transparent text".
It will not fix it on the test page, but it might fix it on the wordpress forms.
</p>

 <form action="captcha_test.php" id="captcha_test" method="post">
 ';

 echo '<div class="title">
 <label for="code">Enter the phrase:</label>
 </div>  '.echo_if_error($error_captcha).'
 <div class="field">
 <input id="code" name="code" type="text" />
 </div>

 <div style="width: 430px;  height: 55px">
 <img id="siimage" style="padding-right: 5px; border-style: none; float:left;"
 src="../securimage_show.php?sid='.md5(uniqid(time())).'"
 alt="CAPTCHA Image" title="CAPTCHA Image" />
 <a href="../securimage_play.php" title="Audible Version of CAPTCHA">
 <img src="../images/audio_icon.gif" alt="Audio Version"
 style="border-style: none; vertical-align:top; border-style: none;" onclick="this.blur()" /></a><br />
 <a href="#" title="Refresh Image" style="border-style: none"
 onclick="document.getElementById(\'siimage\').src = \'../securimage_show.php?sid=\' + Math.random(); return false">
 <img src="../images/refresh.gif" alt="Reload Image"
 style="border-style: none; vertical-align:bottom;" onclick="this.blur()" /></a>
 </div>

  <p>
  <input type="hidden" name="action" value="check" />
  <input type="submit" value="submit" />
  </p>
  </form>

';

} // end if (!$hide_form)

function echo_if_error($this_error){
  global $error;
  if ($error) {
    if (!empty($this_error)) {
         return '<span class="errors">ERROR: ' . $this_error . '</span>'."\n";
    }
  }
}

?>

<p>
<a href="index.php">Try the PHP Requirements Test</a><br />
<a href="cookie_test.php">Try the Cookie Test</a><br />
<b><a href="captcha_test.php">Try the CAPTCHA Test again</a></b><br />
</p>

<p>PHP Scripts by Mike Challis<br />
<a href="http://www.642weather.com/weather/scripts.php">Free PHP Scripts</a><br />
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=8086141">Donate</a>, even small amounts are appreciated
<br />
<br />
Contact me: <a href="http://www.642weather.com/weather/wxblog/support/">(Mike Challis)</a><br />
I will need to know this information: (fill in this information on my support form)<br />
Plugin: Fast and Secure Contact Form<br />
Plugin Version:<br />
Your web site URL:<br />
Problem you are having:
</p>
</div>

</body>
</html>