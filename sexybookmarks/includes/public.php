<?php

// Functions related to mobile.
require_once 'mobile.php';
$shrsb_is_mobile = shrsb_is_mobile();
$shrsb_is_bot = shrsb_is_bot();

//cURL, file get contents or nothing, used for short url
function shrsb_nav_browse($url, $use_POST_method = false, $POST_data = null) {

  if(function_exists('wp_remote_request') && function_exists('wp_remote_retrieve_response_code') && function_exists('wp_remote_retrieve_body')) {
    if($use_POST_method == 'POST') {
      $request_params = array('method' => 'POST', 'body' => $POST_data);
    }
    else {
      $request_params = array('method' => 'GET');
    }

    $url_request = wp_remote_request($url, $request_params);
    $url_response = wp_remote_retrieve_response_code($url_request);

    if($url_response == 200 || $url_response == '200') {
      $source = wp_remote_retrieve_body($url_request);
    }
    else {
      $source = '';
    }
  }
	elseif (function_exists('curl_init') && function_exists('curl_exec')) {
		// Use cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		if($use_POST_method == 'POST'){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_data);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		$source = trim(curl_exec($ch));

    if ( curl_errno($ch) != 0 ) {
      $source = '';
    }

		curl_close($ch);
		
	}
	else {
		$source = '';
	}
	return $source;
}




function shrsb_get_fetch_url() {
	global $post, $shrsb_plugopts, $wp_query; //globals
	
	//get link but first check if inside or outside loop and what page it's on
	$post = $wp_query->post;

	if($shrsb_plugopts['position'] == 'manual') {
		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
		}
		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
		}
	}
	//Check if index page...
	elseif(is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) {
		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
		}
		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
		}
	}
	//Apparently isn't on index page...
	else {
		$perms = get_permalink($post->ID);
	}
	$perms = trim($perms);
	
	//if is post, and post is not published then return permalink and go back
	if($post && get_post_status($post->ID) != 'publish') {
		return $perms;
	}
	//if user chose not to use shortener, return permalink and go back
	if($shrsb_plugopts['shorty'] == 'none') {
		return $perms;
	}
	if ($shrsb_plugopts['shorty'] == 'tflp' && function_exists('permalink_to_twitter_link')) {
		$fetch_url = permalink_to_twitter_link($perms);
	} elseif ($shrsb_plugopts['shorty'] == 'yourls' && function_exists('wp_ozh_yourls_raw_url')) {
		$fetch_url = wp_ozh_yourls_raw_url();
	}
	//if it is tflp or yourls and short url has been successfully recieved, then do not save it in db or try getting a stored short url
	if( !empty( $fetch_url ) ) { 
		return $fetch_url;
	}
	//check if the link is already genereted or not, if yes, then return the link
	$fetch_url = trim(get_post_meta($post->ID, '_sexybookmarks_shortUrl', true));
	if(!is_null($fetch_url) && md5($perms) == trim(get_post_meta($post->ID, '_sexybookmarks_permaHash', true))) {
		return $fetch_url;
	}

	//some vars to be used later, so better set null values before
	$url_more = "";
	$method = 'GET';
	$POST_data = array();
	 
	// Which short url service should be used?
	switch ( $shrsb_plugopts['shorty'] ) {
		case 'tiny':
			$first_url = "http://tinyurl.com/api-create.php?url=".$perms;
			break;
		case 'snip':
			$first_url = "http://snipr.com/site/getsnip";
			$method = 'POST';
			$POST_data = array( "snipformat" => "simple", "sniplink" => rawurlencode($perms), "snipuser" => $shrsb_plugopts['shortyapi']['snip']['user'], "snipapi" => $shrsb_plugopts['shortyapi']['snip']['key'] );
			break;
		case 'cligs':
			$first_url = "http://cli.gs/api/v1/cligs/create?url=".urlencode($perms)."&appid=sexy";
			if ($shrsb_plugopts['shortyapi']['cligs']['chk'] == 1) //if user custom options are set
				$first_url .= "&key=".$shrsb_plugopts['shortyapi']['cligs']['key'];
			break;
		case 'supr':
      $method = 'GET';
			if($shrsb_plugopts['shortyapi']['supr']['chk'] == 1) //if user custom options are set
				$first_url = "http://su.pr/api/shorten?longUrl=".$perms."&login=".$shrsb_plugopts['shortyapi']['supr']['user']."&apiKey=".$shrsb_plugopts['shortyapi']['supr']['key']."&version=1.0";
      else 
        $first_url = "http://su.pr/api/simpleshorten?url=".$perms;
			break;
		case 'bitly':
			$first_url = "http://api.bit.ly/shorten?version=2.0.1&longUrl=".$perms."&history=1&login=".$shrsb_plugopts['shortyapi']['bitly']['user']."&apiKey=".$shrsb_plugopts['shortyapi']['bitly']['key']."&format=json";
			break;
		case 'tinyarrow':
			$first_url = "http://tinyarro.ws/api-create.php?";
			if($shrsb_plugopts['shortyapi']['tinyarrow']['chk'] == 1) //if user custom options are set
				$first_url .= "&userid=".$shrsb_plugopts['shortyapi']['tinyarrow']['user'];
			$first_url .= "&url=".$perms; //url has to be last param in tinyarrow
			break;
		case 'slly':
			$first_url = "http://sl.ly/?module=ShortURL&file=Add&mode=API&url=".$perms;
			break;
		case 'trim': //tr.im no longer exists, this only here for backwards compatibility
			$first_url = "http://b2l.me/api.php?alias=&url=".$perms;
			$shrsb_plugopts['shorty'] = 'b2l';
			update_option(SHRSB_OPTIONS, $shrsb_plugopts);
			break;
		case 'e7t': //e7t.us no longer exists, this only here for backwards compatibility
			$first_url = "http://b2l.me/api.php?alias=&url=".$perms;
			$shrsb_plugopts['shorty'] = 'b2l';
			update_option(SHRSB_OPTIONS, $shrsb_plugopts);
			break;
		case 'b2l': //goto default
		default:
			$first_url = "http://b2l.me/api.php?alias=&url=".$perms;
			break;
	}
	
	$fetch_url = trim(shrsb_nav_browse($first_url, $method, $POST_data));

	if ( !empty( $fetch_url ) ) {
		//if bitly, then decode the json string
		if($shrsb_plugopts['shorty'] == "bitly"){
			$fetch_array = json_decode($fetch_url, true);
			$fetch_url = $fetch_array['results'][urldecode($perms)]['shortUrl'];
		}
    //if bitly, then decode the json string
		if($shrsb_plugopts['shorty'] == "supr" && $shrsb_plugopts['shortyapi']['supr']['chk'] == 1){
			$fetch_array = json_decode($fetch_url, true);
			$fetch_url = $fetch_array['results'][urldecode($perms)]['shortUrl'];
		}
		// Remote call made and was successful
		// Add/update values
		// Tries to update first, then add if field does not already exist
		if (!update_post_meta($post->ID, '_sexybookmarks_shortUrl', $fetch_url)) {
			add_post_meta($post->ID, '_sexybookmarks_shortUrl', $fetch_url);
		}
		if (!update_post_meta($post->ID, '_sexybookmarks_permaHash', md5($perms))) {
			add_post_meta($post->ID, '_sexybookmarks_permaHash', md5($perms));
		}
    if(md5($perms) == get_post_meta($post->ID, '_sexybookmarks_permaHash')) {
      $fetched_array = get_post_meta($post->ID, '_sexybookmarks_shortUrl');
      $fetch_url = $fetched_array[0];
    }
    else {
      update_post_meta($post->ID, '_sexybookmarks_permaHash', md5($perms));
      update_post_meta($post->ID, '_sexybookmarks_shortUrl', $fetch_url);
      $postmeta_array = get_post_meta($post->ID, '_sexybookmarks_shortUrl');
      $fetch_url = $postmeta_array[0];
    }
	}
  else {
		$fetch_url = $perms;
	}
	return $fetch_url;
}





// Create an auto-insertion function
function shrsb_position_menu($post_content) {
	global $post, $shrsb_plugopts, $shrsb_is_mobile, $shrsb_is_bot;

	// If user selected manual positioning, get out.
	if ($shrsb_plugopts['position']=='manual') {
		return $post_content;
	}

	// If user selected hide from mobile and is mobile, get out.
	elseif ($shrsb_plugopts['mobile-hide']=='yes' && false!==$shrsb_is_mobile || $shrsb_plugopts['mobile-hide']=='yes' && false!==$shrsb_is_bot) {
		return $post_content;
	}

	// Decide whether or not to generate the bookmarks.
	if ((is_single() && false!==strpos($shrsb_plugopts['pageorpost'],"post")) ||
		(is_page() && false!==strpos($shrsb_plugopts['pageorpost'],"page")) ||
		(is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) ||
		(is_feed() && !empty($shrsb_plugopts['feed']))
	) { // socials should be generated and added
		if(!get_post_meta($post->ID, 'Hide SexyBookmarks')) {
			$socials=get_sexy();
		}
	}

	// Place of bookmarks and return w/ post content.
	if (empty($socials)) {
		return $post_content;
	} elseif ($shrsb_plugopts['position']=='above') {
		return $socials.$post_content;
	} elseif ($shrsb_plugopts['position']=='below') {
		return $post_content.$socials;
	} elseif ($shrsb_plugopts['position']=='both') {
    return $socials.$post_content.$socials;
  } else { // some other unexpected error, don't do anything. return.
		error_log(__('An unknown error occurred in SexyBookmarks', 'shrsb'));
		return $post_content;
	}
}
// End shrsb_position_menu...

function get_sexy() {
	global $shrsb_plugopts, $wp_query, $post;
	$post = $wp_query->post;


	if($shrsb_plugopts['position'] == 'manual') {

		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
			$title = get_bloginfo('name') . wp_title('-', false);
			$feedperms = strtolower('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
			$mail_subject = urlencode(get_bloginfo('name') . wp_title('-', false));
		}

		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
			$title = $post->post_title;
			$feedperms = strtolower($perms);
			$mail_subject = urlencode($post->post_title);
		}
	}

	//Check if index page...
	elseif(is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) {

		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
			$title = get_bloginfo('name') . wp_title('-', false);
			$feedperms = strtolower('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
			$mail_subject = urlencode(get_bloginfo('name') . wp_title('-', false));
		}

		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
			$title = $post->post_title;
			$feedperms = strtolower($perms);
			$mail_subject = urlencode($post->post_title);
		}
	}
	//Apparently isn't on index page...
	else {
		$perms = get_permalink($post->ID);
		$title = $post->post_title;
		$feedperms = strtolower($perms);
		$mail_subject = urlencode($post->post_title);
	}

  // Grab the short URL
  $fetch_url = shrsb_get_fetch_url();


	//Determine how to handle post titles for Twitter
	if (strlen($title) >= 80) {
		$short_title = urlencode(substr($title, 0, 80)."[..]");
	}
	else {
		$short_title = urlencode($title);
	}

	$title=urlencode($title);

	$shrsb_content	= urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));

	if ($shrsb_content == "") {	$shrsb_content = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,300)); }

	$shrsb_content	= str_replace('+','%20',$shrsb_content);
	$post_summary = stripslashes($shrsb_content);
	$site_name = get_bloginfo('name');
	$mail_subject = str_replace('+','%20',$mail_subject);
	$mail_subject = str_replace("&#8217;","'",$mail_subject);
	$y_cat = $shrsb_plugopts['ybuzzcat'];
	$y_med = $shrsb_plugopts['ybuzzmed'];
	$t_cat = $shrsb_plugopts['twittcat'];




	// Grab post tags for Twittley tags. If there aren't any, use default tags set in plugin options page
	$getkeywords = get_the_tags(); if ($getkeywords) { foreach($getkeywords as $tag) { $keywords=$keywords.$tag->name.','; } }
	if (!empty($getkeywords)) {
		$d_tags=substr($d_tags, 0, count($d_tags)-2);
	}
	else {
		$d_tags = $shrsb_plugopts['defaulttags'];
	}


	// Check permalink setup for proper feed link
	if (false !== strpos($feedperms,'?') || false !== strpos($feedperms,'.php',strlen($feedperms) - 4)) {
		$feedstructure = '&amp;feed=comments-rss2';
	} else {
		if ('/' == $feedperms[strlen($feedperms) - 1]) {
			$feedstructure = 'feed';
		}
		else {
			$feedstructure = '/feed';
		}
	}


	// Compatibility fix for NextGen Gallery Plugin...
	if( (strpos($post_summary, '[') || strpos($post_summary, ']')) ) {
		$post_summary = "";
	}
	if( (strpos($shrsb_content, '[') || strpos($shrsb_content,']')) ) {
		$shrsb_content = "";
	}

	// Select the background image
	if(!isset($shrsb_plugopts['bgimg-yes'])) {
		$bgchosen = '';
	} elseif($shrsb_plugopts['bgimg'] == 'shr') {
		$bgchosen = ' shr-bookmarks-bg-shr';
	} elseif($shrsb_plugopts['bgimg'] == 'caring') {
		$bgchosen = ' shr-bookmarks-bg-caring';
	} elseif($shrsb_plugopts['bgimg'] == 'care-old') {
		$bgchosen = ' shr-bookmarks-bg-caring-old';
	} elseif($shrsb_plugopts['bgimg'] == 'love') {
		$bgchosen = ' shr-bookmarks-bg-love';
	} elseif($shrsb_plugopts['bgimg'] == 'wealth') {
		$bgchosen = ' shr-bookmarks-bg-wealth';
	} elseif($shrsb_plugopts['bgimg'] == 'enjoy') {
		$bgchosen = ' shr-bookmarks-bg-enjoy';
	} elseif($shrsb_plugopts['bgimg'] == 'german') {
		$bgchosen = ' shr-bookmarks-bg-german';
	} elseif($shrsb_plugopts['bgimg'] == 'knowledge') {
		$bgchosen = ' shr-bookmarks-bg-knowledge';
	}


	$expand=$shrsb_plugopts['expand']?' shr-bookmarks-expand':'';
	if ($shrsb_plugopts['autocenter']==1) {
		$autocenter=' shr-bookmarks-center';
	} elseif ($shrsb_plugopts['autocenter']==2) {
		$autocenter=' shr-bookmarks-spaced';
	} else {
		$autocenter='';
	}

	//Write the sexybookmarks menu
	$socials = "\n\n";
	$socials .= '<div class="shr-bookmarks'.$expand.$autocenter.$bgchosen.'">'."\n".'<ul class="socials">'."\n";
	foreach ($shrsb_plugopts['bookmark'] as $name) {
		switch ($name) {
			case 'shr-twitter':
				$socials.=bookmark_list_item($name, array(
					'short_title'=>$short_title,
					'fetch_url'=>$fetch_url,
				));
				break;
			case 'shr-identica':
				$socials.=bookmark_list_item($name, array(
					'short_title'=>$short_title,
					'fetch_url'=>$fetch_url,
				));
				break;
			case 'shr-mail':
				$socials.=bookmark_list_item($name, array(
					'title'=>$mail_subject,
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
				));
				break;
			case 'shr-tomuse':
				$socials.=bookmark_list_item($name, array(
					'title'=>$mail_subject,
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
				));
				break;
			case 'shr-diigo':
				$socials.=bookmark_list_item($name, array(
					'sexy_teaser'=>$shrsb_content,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-linkedin':
				$socials.=bookmark_list_item($name, array(
					'post_summary'=>$post_summary,
					'site_name'=>$site_name,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-comfeed':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>urldecode($feedperms).$feedstructure,
				));
				break;
			case 'shr-yahoobuzz':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>$perms,
					'title'=>$title,
					'yahooteaser'=>$shrsb_content,
					'yahoocategory'=>$y_cat,
					'yahoomediatype'=>$y_med,
				));
				break;
			case 'shr-twittley':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>urlencode($perms),
					'title'=>$title,
					'post_summary'=>$post_summary,
					'twitt_cat'=>$t_cat,
					'default_tags'=>$d_tags,
				));
				break;
			case 'shr-tumblr':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>urlencode($perms),
					'title'=>$title,
				));
				break;
			default:
				$socials.=bookmark_list_item($name, array(
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
		}
	}
	$socials.='</ul>'."\n".'<div style="clear:both;"></div>'."\n".'</div>';
	$socials.="\n\n";

	return $socials;
}

// This function is what allows people to insert the menu wherever they please rather than above/below a post...
function selfserv_sexy() {
	global $post;
	if(!get_post_meta($post->ID, 'Hide SexyBookmarks'))
		echo get_sexy();
}

// Write the <head> code only on pages that the menu is set to display
function shrsb_publicStyles() {
	global $shrsb_plugopts, $post, $shrsb_custom_sprite;

	// If custom field is set, do not display sexybookmarks
	if(get_post_meta($post->ID, 'Hide SexyBookmarks')) {
		echo "\n\n".'<!-- '.__('SexyBookmarks has been disabled on this page', 'shrsb').' -->'."\n\n";
	} 
  else {
		//custom mods rule over custom css
    if($shrsb_plugopts['custom-mods'] != 'yes') {
      if($shrsb_custom_sprite != '') {
        $surl = $shrsb_custom_sprite;
      }
      else {
        $surl = SHRSB_PLUGPATH.'css/style.css';
      }
    }
    elseif($shrsb_plugopts['custom-mods'] == 'yes') {
      $surl = WP_CONTENT_URL.'/sexy-mods/css/style.css';
    }
		wp_enqueue_style('sexy-bookmarks', $surl, false, SHRSB_vNum, 'all');
	}
}
function shrsb_publicScripts() {
	global $shrsb_plugopts, $post;
	
	if (($shrsb_plugopts['expand'] || $shrsb_plugopts['autocenter'] || $shrsb_plugopts['targetopt']=='_blank') && !get_post_meta($post->ID, 'Hide SexyBookmarks')) { // If any javascript dependent options are selected, load the scripts

    // If custom mods is selected, pull files from new location
    if($shrsb_plugopts['custom-mods'] == 'yes') {
      $surl = WP_CONTENT_URL.'/sexy-mods/js/sexy-bookmarks-public.js';
    }
    else {
      $surl = SHRSB_PLUGPATH.'js/sexy-bookmarks-public.js';
    }

		$jquery = ($shrsb_plugopts['doNotIncludeJQuery'] != '1') ? array('jquery') : array(); // If jQuery compatibility fix is not selected, go ahead and load jQuery
		$infooter = ($shrsb_plugopts['scriptInFooter'] == '1') ? true : false;
		wp_enqueue_script('sexy-bookmarks-public-js', $surl, $jquery, SHRSB_vNum, $infooter);
	}
}

add_action('wp_print_styles', 'shrsb_publicStyles');
add_action('wp_print_scripts', 'shrsb_publicScripts');
add_filter('the_content', 'shrsb_position_menu');
