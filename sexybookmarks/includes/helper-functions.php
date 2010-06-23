<?php

function shrsb_preFlight_Checks() {
	global $shrsb_plugopts;
	if( ((function_exists('curl_init') && function_exists('curl_exec')) || function_exists('file_get_contents')) && (is_dir(SHRSB_PLUGDIR.'spritegen') && is_writable(SHRSB_PLUGDIR.'spritegen')) && ((isset($_POST['bookmark']) && is_array($_POST['bookmark']) && sizeof($_POST['bookmark']) > 0 ) || (isset($shrsb_plugopts['bookmark']) && is_array($shrsb_plugopts['bookmark']) && sizeof($shrsb_plugopts['bookmark']) > 0 )) && !$shrsb_plugopts['custom-mods'] ) {
		return true;
	}
	else {
		return false;
	}
}

function get_sprite_file($opts, $type) {

	$spritegen = 'http://www.shareaholic.com/api/sprite/?v=1&apikey=8afa39428933be41f8afdb8ea21a495c&imageset=60'.$opts.'&apitype='.$type;
  $filename = SHRSB_PLUGDIR.'spritegen/shr-custom-sprite.'.$type;
  $content = FALSE;

  if ( $type == 'png' ) {
    $fp_opt = 'rb';
  }
  else {
    $fp_opt = 'r';
  }

  if(function_exists('wp_remote_retrieve_body') && function_exists('wp_remote_get') && function_exists('wp_remote_retrieve_response_code')) {
    $request = wp_remote_get(
      $spritegen,
      array(
        'user-agent' => "shr-wpspritebot-fopen/v" . SHRSB_vNum,
        'headers' => array(
          'Referer' => get_bloginfo('url')
        )
      )
    );
    $response = wp_remote_retrieve_response_code($request);
    if($response == 200 || $response == '200') {
      $content = wp_remote_retrieve_body($request);
    }
    else {
      $content = FALSE;
    }
  }

  if ( $content === FALSE && function_exists('curl_init') && function_exists('curl_exec') ) {
	  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $spritegen);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_USERAGENT, "shr-wpspritebot-cURL/v" . SHRSB_vNum);
    curl_setopt($ch, CURLOPT_REFERER, get_bloginfo('url'));
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);

    $content = curl_exec($ch);

    if ( curl_errno($ch) != 0 ) {
      $content = FALSE;
    }
    curl_close($ch);
  }

  if ( $content !== FALSE ) {
    if ( $type == 'png' ) {
      $fp_opt = 'w+b';
    }
    else {
      $fp_opt = 'w+';
    }

    
    $fp = @fopen($filename, $fp_opt);

    if ( $fp !== FALSE ) {
      $ret = @fwrite($fp, $content);
      @fclose($fp);
    }
    else {
      $ret = @file_put_contents($filename, $content);
    }

    if ( $ret !== FALSE ) {
      @chmod($filename, 0666);
      return 0;
    }
    else {
      return 1;
    }
  }
  else {
    return 2;
  }
}

?>