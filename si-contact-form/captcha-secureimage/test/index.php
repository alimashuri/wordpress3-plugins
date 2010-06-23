<?php
/*
Securimage Test Script
Version 1.0m Mike Challis 08/29/2009
http://www.642weather.com/weather/scripts.php

Upload this PHP script to your web server and call it from the browser.
The script will tell you if you meet the requirements for running Securimage.

http://www.phpcaptcha.org
*/
//error_reporting(E_ALL ^ E_NOTICE); // Report all errors except E_NOTICE warnings
//error_reporting(E_ALL); // Report all errors and warnings (very strict, use for testing only)
//ini_set('display_errors', 1); // turn error reporting on

// start a session cookie
if( !isset( $_SESSION ) ) {
    session_start();
}

if (isset($_GET['testimage']) && $_GET['testimage'] == '1') {
  $im = imagecreate(225, 225);
  $white = imagecolorallocate($im, 255, 255, 255);
  $black = imagecolorallocate($im, 0, 0, 0);

  $red   = imagecolorallocate($im, 255,   0,   0);
  $green = imagecolorallocate($im,   0, 255,   0);
  $blue  = imagecolorallocate($im,   0,   0, 255);

  // draw the head
  imagearc($im, 100, 120, 200, 200,  0, 360, $black);
  // mouth
  imagearc($im, 100, 120, 150, 150, 25, 155, $red);
  // left and then the right eye
  imagearc($im,  60,  95,  50,  50,  0, 360, $green);
  imagearc($im, 140,  95,  50,  50,  0, 360, $blue);

  imagestring($im, 5, 15, 1, 'Securimage Will Work!!', $blue);
  imagestring($im, 2, 5, 20, ':) :) :)', $black);
  imagestring($im, 2, 5, 30, ':) :)', $black);
  imagestring($im, 2, 5, 40, ':)', $black);

  imagestring($im, 2, 150, 20, '(: (: (:', $black);
  imagestring($im, 2, 168, 30, '(: (:', $black);
  imagestring($im, 2, 186, 40, '(:', $black);

  imagepng($im, null, 3);
  exit;
}

function print_status($supported)
{
  if ($supported) {
    echo "<span style=\"color: #00f\">Yes!</span>";
  } else {
    echo "<span style=\"color: #f00; font-weight: bold\">No</span>";
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Secureimage PHP Requirements Test</title>
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
</style>
</head>

<body>

<div class="group" style="margin-left:20%; margin-right:20%; padding:20px;">
<h2>Secureimage PHP Requirements Test</h2>
<p>
  This script will test your PHP installation to see if (Secureimage) CAPTCHA will run on your server.
  <br /><br />
Note: If you see any errors or warnings at the top of the page,
especially "Warning: session_start...", they could be indicating a problem with your PHP server that will prevent the CAPTCHA from working.
</p>

<ul>
  <li>
    <strong>PHP Version:</strong>
    <?php echo phpversion(); ?>
  </li>
  <li>
    <strong>System:</strong>
    <?php echo PHP_OS; ?>
  </li>
  <li>
    <strong>GD Support:</strong>
    <?php print_status($gd_support = extension_loaded('gd')); ?>
  </li>
  <?php if ($gd_support) $gd_info = gd_info(); else $gd_info = array(); ?>
  <?php if ($gd_support): ?>
  <li>
    <strong>GD Version:</strong>
    <?php echo $gd_info['GD Version']; ?>
  </li>
  <?php endif; ?>
  <li>
    <strong>TTF Support (FreeType):</strong>
    <?php print_status($gd_support && $gd_info['FreeType Support']); ?>
    <?php if ($gd_support && $gd_info['FreeType Support'] == false): ?>
    <br />No FreeType support.  Cannot use TTF fonts, but it will use GD fonts instead.
    <?php endif; ?>
  </li>
  
  <li>
    <strong>imagettftext Support:</strong>
    <?php print_status( function_exists('imagettftext') ); ?>
  </li>

  <li>
    <strong>imagettfbbox Support:</strong>
    <?php print_status( function_exists('imagettfbbox') ); ?>
  </li>

   <li>
    <strong>imagecreatetruecolor Support:</strong>
    <?php print_status( function_exists('imagecreatetruecolor') ); ?>
  </li>

  <li>
    <strong>imagefilledrectangle Support:</strong>
    <?php print_status( function_exists('imagefilledrectangle') ); ?>
  </li>

  <li>
    <strong>imagecolorallocatealpha Support:</strong>
    <?php print_status( function_exists('imagecolorallocatealpha') ); ?>
  </li>

  <li>
    <strong>JPEG Support:</strong>
    <?php

     if ( isset($gd_info['JPG Support']) ) {
         print_status($gd_support && $gd_info['JPG Support']);
     } else if ( isset($gd_info['JPEG Support']) ) {
         print_status($gd_support && $gd_info['JPEG Support']);
     }

    ?>
  </li>
  <li>
    <strong>PNG Support:</strong>
    <?php print_status($gd_support && $gd_info['PNG Support']); ?>
  </li>
  <li>
    <strong>GIF Read Support:</strong>
    <?php print_status($gd_support && $gd_info['GIF Read Support']); ?>
  </li>
  <li>
    <strong>GIF Create Support:</strong>
    <?php print_status($gd_support && $gd_info['GIF Create Support']); ?>
  </li>

</ul>

<?php if ($gd_support): ?>
Since you can see this...<br /><br />
<img src="<?php echo $_SERVER['PHP_SELF']; ?>?testimage=1" alt="Test Image" align="bottom" />
<?php else: ?>
Based on the requirements, you do not have what it takes to run (Secureimage) CAPTCHA :(
<?php endif; ?>

<p>
<b><a href="index.php">Try the PHP Requirements Test again</a></b><br />
<a href="cookie_test.php">Try the Cookie Test</a><br />
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