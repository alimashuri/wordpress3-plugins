<?php
//list all bookmarks in the plugin options page
function shrsb_network_input_select($name, $hint) {
	global $shrsb_plugopts;
	return sprintf('<label class="%s" title="%s"><input %sname="bookmark[]" type="checkbox" value="%s"  id="%s" /></label>',
		$name,
		$hint,
		@in_array($name, $shrsb_plugopts['bookmark'])?'checked="checked" ':"",
		$name,
		$name
	);
}

// returns the option tag for a form select element
// $opts array expecting keys: field, value, text
function shrsb_form_select_option($opts) {
	global $shrsb_plugopts;
	$opts=array_merge(
		array(
			'field'=>'',
			'value'=>'',
			'text'=>'',
		),
		$opts
	);
	return sprintf('<option%s value="%s">%s</option>',
		($shrsb_plugopts[$opts['field']]==$opts['value'])?' selected="selected"':"",
		$opts['value'],
		$opts['text']
	);
}

// given an array $options of data and $field to feed into shrsb_form_select_option
function shrsb_select_option_group($field, $options) {
	$h='';
	foreach ($options as $value=>$text) {
		$h.=shrsb_form_select_option(array(
			'field'=>$field,
			'value'=>$value,
			'text'=>$text,
		));
	}
	return $h;
}

// function to list bookmarks that have been chosen by admin
function bookmark_list_item($name, $opts=array()) {
	global $shrsb_plugopts, $shrsb_bookmarks_data;

  // If Twitter, check for custom tweet configuration and modify tweet accordingly
  if($name == 'shr-twitter') {
    $tsrc='&amp;source=shareaholic';
    if(!empty($shrsb_plugopts['tweetconfig'])) {
      $needle = array('${title}', '${short_link}');
      $new_needle = array('SHORT_TITLE', 'FETCH_URL');
      $tconfig = str_replace($needle, $new_needle, $shrsb_plugopts['tweetconfig']);
      $url=$shrsb_bookmarks_data[$name]['baseUrl'].urlencode($tconfig).$tsrc;
    }
    // Otherwise, use default tweet format
    else {
      $url=$shrsb_bookmarks_data[$name]['baseUrl'].'SHORT_TITLE+-+FETCH_URL'.$tsrc;
    }
  }
  // Otherwise, use default baseUrl format
  else {
	  $url=$shrsb_bookmarks_data[$name]['baseUrl'];
  }


	$onclick = "";
	if($name == 'shr-facebook') {
		$onclick = " onclick=\"window.open(this.href,'sharer','toolbar=0,status=0,width=626,height=436'); return false;\"";
	}
  if($name == 'shr-buzzster') {
    $topt = '';
  }
  else {
    if($shrsb_plugopts['targetopt'] == '_blank') {
      $topt = ' class="external"';
    }
    else {
      $topt = '';
    }
  }
	foreach ($opts as $key=>$value) {
		$url=str_replace(strtoupper($key), $value, $url);
	}
	if(is_feed()) {
		return sprintf(
			"\t\t".'<li class="%s">'."\n\t\t\t".'<a href="%s" rel="%s"%s title="%s">%s</a>'."\n\t\t".'</li>'."\n",
			$name,
			$url,
			$shrsb_plugopts['reloption'],
			$topt,
			$shrsb_bookmarks_data[$name]['share'],
			$shrsb_bookmarks_data[$name]['share']
		);
	}
	else {
		return sprintf(
			"\t\t".'<li class="%s">'."\n\t\t\t".'<a href="%s" rel="%s"%s title="%s"%s>&nbsp;</a>'."\n\t\t".'</li>'."\n",
			$name,
			$url,
			$shrsb_plugopts['reloption'],
			$topt,
			$shrsb_bookmarks_data[$name]['share'],
			$onclick
		);
	}
}

?>