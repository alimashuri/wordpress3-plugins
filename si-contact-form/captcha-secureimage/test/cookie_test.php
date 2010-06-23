<?php
/*
Cookie Test Script
Version 1.0 Mike Challis 08/30/2009
http://www.642weather.com/weather/scripts.php

Upload this PHP script to your web server and call it from the browser.
The script will tell you if your browser meets the cookie requirements for running Securimage.

cookie test code from:
http://www.coderemix.com/tutorials/php-cookies-enabled-check
*/
//error_reporting(E_ALL ^ E_NOTICE); // Report all errors except E_NOTICE warnings
error_reporting(E_ALL); // Report all errors and warnings (very strict, use for testing only)
ini_set('display_errors', 1); // turn error reporting on

// start a session cookie
if( !isset( $_SESSION ) ) {
    session_start();
}

$disabled_help = '
<b><a href="cookie_test.php">Try the Cookie Test again</a> just to be sure</b><br />
If the CAPTCHA is giving you a cookie error, this can be the cause.
The Captcha will not be able work. The contact form will display an error:
"ERROR: Could not read CAPTCHA cookie. Make sure you have cookies enabled."
<br /><br />
Solution: Please configure your browser to allow cookies.
';

$enabled_help = '
If the CAPTCHA is giving you a cookie error, this rules out your web browser as the cause.

<br /><br />
Solution: Try all 3 tests below.
If all 3 pass,
the problem could be another WordPress plugin is conflicting with the PHP session.
What other plugins do you have installed?
Can you temporarily deactivate them all.
Test, then if it works, activate them one at a time (then test) until the conflicting plugin is pinpointed?
If a conflicting plugin is found I might be able to fix it (or not), then we can notify the plugin author.
Contact me below.
';

// Define a cookie and reload the page
if(!isset($_GET['redirected']))
{
    setcookie ('mycookie', 'test', time() + 300);
    header('location:'.$_SERVER['PHP_SELF'].'?redirected=1');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cookies Test</title>
<style>
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
</style>
</head>

<body>

<div class="group" style="margin-left:20%; margin-right:20%; padding:20px;">
<h2>Cookies Test</h2>

<p>
This script will test your web browser to see if it can read a cookie required by the (Secureimage) CAPTCHA.
You should see a message below letting you know if cookies are properly enabled in your browser.
  <br /><br />
Note: If you see any errors or warnings at the top of the page, THEN THE TEST PROBABLY FAILED.
If you see an error: "Warning: session_start...", it is indicating a problem with your PHP server that will prevent the CAPTCHA from working.
</p>

<p>
<strong>Web browsers have a setting to enable/disable cookies.
They also have a setting to block/unblock cookies per each web site.

For instructions on how to enable cookies or unblock cookies in your browser, use a search engine</strong>.
Different internet browsers have different sets of instructions on how to change this setting.
</p>

<?php
// Check if the cookie just defined is there
$cookie_message = '';
if(isset($_GET['redirected']) and $_GET['redirected']==1) {
    if(!isset($_COOKIE['mycookie'])) {
        $cookie_message = '<p style="background-color:#CC6666; color:white; padding:10px;">
        Test Failed: Problem found: Cookies are NOT enabled on your browser.<br />
        '.$disabled_help.'
        </p>';
    }
    else{
        $cookie_message = '<p style="background-color:#99CC66; padding:10px;">
        Test Passed: Cookies are enabled on your browser.
        <br /><br />
        '.$enabled_help.'
        </p>';
    }
} else {
      $cookie_message = '<p style="background-color:#CC6666; padding:10px;">
        The test failed to check for cookies because of a PHP server error.
        <br /><br />
        The error message will indicate the cause of the problem.
        You may have to contact your web host support department.
        </p>';


}
echo $cookie_message;
?>

<p>
<a href="index.php">Try the PHP Requirements Test</a><br />
<b><a href="cookie_test.php">Try the Cookie Test again</a></b><br />
<a href="captcha_test.php">Try the CAPTCHA Test</a><br />
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