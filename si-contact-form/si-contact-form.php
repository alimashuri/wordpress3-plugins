<?php
/*
Plugin Name: Fast and Secure Contact Form
Plugin URI: http://www.642weather.com/weather/scripts-wordpress-si-contact.php
Description: Fast and Secure Contact Form for WordPress. The contact form lets your visitors send you a quick E-mail message. Blocks all common spammer tactics. Spam is no longer a problem. Includes a CAPTCHA and Akismet support. Does not require JavaScript. <a href="plugins.php?page=si-contact-form/si-contact-form.php">Settings</a> | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8086141">Donate</a>
Version: 2.6.4
Author: Mike Challis
Author URI: http://www.642weather.com/weather/scripts.php
*/

/*  Copyright (C) 2008-2010 Mike Challis  (http://www.642weather.com/weather/contact_us.php)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// settings get deleted when plugin is deleted from admin plugins page
// this must be outside the class or it does not work
function si_contact_unset_options() {

  delete_option('si_contact_form');
  delete_option('si_contact_form_gb');

  // multi-forms (a unique configuration for each contact form)
  for ($i = 2; $i <= 100; $i++) {
    delete_option("si_contact_form$i");
  }
} // end function si_contact_unset_options

if (!class_exists('siContactForm')) {

 class siContactForm {
     var $si_contact_error;

function si_contact_add_tabs() {
    add_submenu_page('plugins.php', __('SI Contact Form Options', 'si-contact-form'), __('SI Contact Form Options', 'si-contact-form'), 'manage_options', __FILE__,array(&$this,'si_contact_options_page'));
}

function si_contact_update_lang() {
  global $si_contact_opt, $si_contact_option_defaults;

   // a few language options need to be re-translated now.
   // had to do this becuse the options were actually needed to be set before the language translator was initialized

  // update translation for these options (for when switched from English to another lang)
  if ($si_contact_opt['welcome'] == '<p>Comments or questions are welcome.</p>' ) {
     $si_contact_opt['welcome'] = __('<p>Comments or questions are welcome.</p>', 'si-contact-form');
     $si_contact_option_defaults['welcome'] = $si_contact_opt['welcome'];
  }

  if ($si_contact_opt['email_to'] == 'Webmaster,'.get_option('admin_email')) {
       $si_contact_opt['email_to'] = __('Webmaster', 'si-contact-form').','.get_option('admin_email');
       $si_contact_option_defaults['email_to'] = $si_contact_opt['email_to'];
  }

  if ($si_contact_opt['email_subject'] == get_option('blogname') . ' ' .'Contact:') {
      $si_contact_opt['email_subject'] =  get_option('blogname') . ' ' .__('Contact:', 'si-contact-form');
      $si_contact_option_defaults['email_subject'] = $si_contact_opt['email_subject'];
  }

} // end function si_contact_update_lang

function si_contact_options_page() {
  global $captcha_url_cf, $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults;

  require_once(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-admin.php');

} // end function si_contact_options_page

function si_contact_captcha_perm_dropdown($select_name, $checked_value='') {
        // choices: Display text => permission_level
        $choices = array (
                 esc_attr( __('All registered users', 'si-contact-form')) => 'read',
                 esc_attr( __('Edit posts', 'si-contact-form')) => 'edit_posts',
                 esc_attr( __('Publish Posts', 'si-contact-form')) => 'publish_posts',
                 esc_attr( __('Moderate Comments', 'si-contact-form')) => 'moderate_comments',
                 esc_attr( __('Administer site', 'si-contact-form')) => 'level_10'
                 );
        // print the <select> and loop through <options>
        echo '<select name="' . $select_name . '" id="' . $select_name . '">' . "\n";
        foreach ($choices as $text => $capability) :
                if ($capability == $checked_value) $checked = ' selected="selected" ';
                echo "\t". '<option value="' . $capability . '"' . $checked . ">$text</option> \n";
                $checked = '';
        endforeach;
        echo "\t</select>\n";
} // end function si_contact_captcha_perm_dropdown

// this function prints the contact form
// and does all the decision making to send the email or not
// [si_contact_form form='2']
function si_contact_form_short_code($atts) {
   global $captcha_path_cf, $si_contact_opt, $si_contact_gb;

  // get options
  $si_contact_gb_mf = get_option("si_contact_form_gb");

   extract(shortcode_atts(array( 'form' => '' ), $atts));
    $form_num = '';
    $form_id_num = 1;
    if ( isset($form) && is_numeric($form) && $form <= $si_contact_gb_mf['max_forms'] ) {
       $form_num = (int)$form;
       $form_id_num = (int)$form;
       if ($form_num == 1)
         $form_num = '';
    }

  // get options
  $this->si_contact_get_options($form_num);

  // a couple language options need to be translated now.
  $this->si_contact_update_lang();

// Email address(s) to receive Bcc (Blind Carbon Copy) messages
$ctf_email_address_bcc = $si_contact_opt['email_bcc']; // optional

// optional subject list
$subjects = array ();
$subjects_test = explode("\n",trim($si_contact_opt['email_subject_list']));
if(!empty($subjects_test) ) {
  $ct = 1;
  foreach($subjects_test as $v) {
       $v = trim($v);
       if ($v != '') {
          $subjects["$ct"] = $v;
          $ct++;
       }
  }
}

// E-mail Contacts
// the drop down list array will be made automatically by this code
// checks for properly configured E-mail To: addresses in options.
$ctf_contacts = array ();
$ctf_contacts_test = trim($si_contact_opt['email_to']);
if(!preg_match("/,/", $ctf_contacts_test) ) {
    if($this->ctf_validate_email($ctf_contacts_test)) {
        // user1@example.com
       $ctf_contacts[] = array('CONTACT' => __('Webmaster', 'si-contact-form'),  'EMAIL' => $ctf_contacts_test );
    }
} else {
  $ctf_ct_arr = explode("\n",$ctf_contacts_test);
  if (is_array($ctf_ct_arr) ) {
    foreach($ctf_ct_arr as $line) {
       // echo '|'.$line.'|' ;
       list($key, $value) = explode(",",$line);
       $key   = trim($key);
       $value = trim($value);
       if ($key != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // just one email here
               // Webmaster,user1@example.com
               if ($this->ctf_validate_email($value)) {
                  $ctf_contacts[] = array('CONTACT' => $this->ctf_output_string($key),  'EMAIL' => $value);
               }
          } else {
               // multiple emails here (additional ones will be Cc:)
               // Webmaster,user1@example.com;user2@example.com
               $multi_cc_arr = explode(";",$value);
               $multi_cc_string = '';
               foreach($multi_cc_arr as $multi_cc) {
                   if ($this->ctf_validate_email($multi_cc)) {
                     $multi_cc_string .= "$multi_cc,";
                   }
               }
               if ($multi_cc_string != '') { // multi cc emails
                  $ctf_contacts[] = array('CONTACT' => $this->ctf_output_string($key),  'EMAIL' => rtrim($multi_cc_string, ','));
               }
         }
      }

   } // end foreach
  } // end if (is_array($ctf_ct_arr) ) {
} // end else

//print_r($ctf_contacts);

// Normally this setting will be left blank in options.
$ctf_email_on_this_domain =  $si_contact_opt['email_from']; // optional

// Site Name / Title
$ctf_sitename = get_option('blogname');

// Site Domain without the http://www like this: $domain = '642weather.com';
// Can be a single domain:      $ctf_domain = '642weather.com';
// Can be an array of domains:  $ctf_domain = array('642weather.com','someothersite.com');
        // get blog domain
        $uri = parse_url(get_option('home'));
        $blogdomain = preg_replace("/^www\./i",'',$uri['host']);

$this->ctf_domain = $blogdomain;

// Make sure the form was posted from your host name only.
// This is a security feature to prevent spammers from posting from files hosted on other domain names
// "Input Forbidden" message will result if host does not match
$this->ctf_domain_protect = $si_contact_opt['domain_protect'];

// Double E-mail entry is optional
// enabling this requires user to enter their email two times on the contact form.
$ctf_enable_double_email = $si_contact_opt['double_email'];

// You can ban known IP addresses
// SET  $ctf_enable_ip_bans = 1;  ON,  $ctf_enable_ip_bans = 0; for OFF.
$ctf_enable_ip_bans = 0;

// Add IP addresses to ban here:  (be sure to SET  $ctf_enable_ip_bans = 1; to use this feature
$ctf_banned_ips = array(
'22.22.22.22', // example (add, change, or remove as needed)
'33.33.33.33', // example (add, change, or remove as needed)
);

// Wordwrap E-Mail message text so lines are no longer than 70 characters.
// SET  $ctf_wrap_message = 1;  ON,  $ctf_wrap_message = 0; for OFF.
$ctf_wrap_message = 1;

// Redirect to Home Page after message is sent
$ctf_redirect_enable = $si_contact_opt['redirect_enable'];
// Used for the delay timer once the message has been sent
$ctf_redirect_timeout = $si_contact_opt['redirect_seconds']; // time in seconds to wait before loading another Web page
// Web page to send the user to after the time has expired
$ctf_redirect_url = $si_contact_opt['redirect_url'];

// The $ctf_welcome_intro is what gets printed when the contact form is first presented.
// It is not printed when there is an input error and not printed after the form is completed
$ctf_welcome_intro = '

'.$si_contact_opt['welcome'];

// The $thank_you is what gets printed after the form is sent.
$ctf_thank_you = '
<p>
';
if ($si_contact_opt['text_message_sent'] != '') {
        $ctf_thank_you .= $si_contact_opt['text_message_sent'];
} else {
        $ctf_thank_you .= __('Your message has been sent, thank you.', 'si-contact-form');
}
$ctf_thank_you .= '
</p>
';

if ($ctf_redirect_enable == 'true') {
  $wp_plugin_url = WP_PLUGIN_URL;

 $ctf_thank_you .= <<<EOT

<script type="text/javascript" language="javascript">
<!--
var count=$ctf_redirect_timeout;
var time;
function timedCount() {
  document.title='Redirecting in ' + count + ' seconds';
  count=count-1;
  time=setTimeout("timedCount()",1000);
  if (count==-1) {
    clearTimeout(time);
    document.title='Redirecting ...';
    self.location='$ctf_redirect_url';
  }
}
window.onload=timedCount;
//-->
</script>
EOT;

$ctf_thank_you .= '
<img src="'.$wp_plugin_url.'/si-contact-form/ctf-loading.gif" alt="'.esc_attr(__('Redirecting', 'si-contact-form')).'" />&nbsp;&nbsp;
'.__('Redirecting', 'si-contact-form').' ... ';


// do not remove the above EOT line

}

// add numbered keys starting with 1 to the $contacts array
$cont = array();
$ct = 1;
foreach ($ctf_contacts as $v)  {
    $cont["$ct"] = $v;
    $ct++;
}
$contacts = $cont;
unset($cont);

// initialize vars
$string = '';
$this->si_contact_error = 0;
$si_contact_error_print = '';
$message_sent = 0;
$mail_to    = '';
$to_contact = '';
$name       = '';
$email      = '';
$email2     = '';
$subject    = '';
$message       = '';
$captcha_code  = '';

// optional extra fields
for ($i = 1; $i <= $si_contact_gb['max_fields']; $i++) {
   if ($si_contact_opt['ex_field'.$i.'_label'] != '') {
      ${'ex_field'.$i} = '';
      ${'si_contact_error_ex_field'.$i} = '';
   }
}
$req_field_ind = ( $si_contact_opt['req_field_indicator_enable'] == 'true' ) ? ' <span class="required">'.$si_contact_opt['req_field_indicator'].'</span>' : '';
$si_contact_error_captcha = '';
$si_contact_error_contact = '';
$si_contact_error_name    = '';
$si_contact_error_email   = '';
$si_contact_error_email2  = '';
$si_contact_error_double_email = '';
$si_contact_error_subject = '';
$si_contact_error_message = '';

// see if WP user
global $current_user, $user_ID;
get_currentuserinfo();

// process form now
if (isset($_POST['si_contact_action']) && ($_POST['si_contact_action'] == 'send')
   && isset($_POST['si_contact_form_id']) && ($_POST['si_contact_form_id'] == $form_id_num)
) {

  // include the code to process the form and send the mail
  include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-process.php');

} // end if posted si_contact_action = send

if($message_sent) {
      // thank you message is printed here
      $string .= $ctf_thank_you;
}else{
      if (!$this->si_contact_error) {
        // welcome intro is printed here unless message is sent
        $string .= $ctf_welcome_intro;
      }

  // include the code to display the form
  include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-display.php');

}

 return $string;
} // end function si_contact_form_short_code

// checks if captcha is enabled based on the current captcha permission settings set in the plugin options
function isCaptchaEnabled() {
   global $si_contact_opt;

   if ($si_contact_opt['captcha_enable'] !== 'true') {
        return false; // captcha setting is disabled for si contact
   }
   // skip the captcha if user is loggged in and the settings allow
   if (is_user_logged_in() && $si_contact_opt['captcha_perm'] == 'true') {
       // skip the CAPTCHA display if the minimum capability is met
       if ( current_user_can( $si_contact_opt['captcha_perm_level'] ) ) {
               // skip capthca
               return false;
        }
   }
   return true;
} // end function isCaptchaEnabled

function captchaCheckRequires() {
  global $captcha_path_cf;

  $ok = 'ok';
  // Test for some required things, print error message if not OK.
  if ( !extension_loaded('gd') || !function_exists('gd_info') ) {
      $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says GD image support not detected in PHP!', 'si-contact-form').'</p>';
      $this->captchaRequiresError .= '<p>'.__('Contact your web host and ask them why GD image support is not enabled for PHP.', 'si-contact-form').'</p>';
      $ok = 'no';
  }
  if ( !function_exists('imagepng') ) {
      $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says imagepng function not detected in PHP!', 'si-contact-form').'</p>';
      $this->captchaRequiresError .= '<p>'.__('Contact your web host and ask them why imagepng function is not enabled for PHP.', 'si-contact-form').'</p>';
      $ok = 'no';
  }
  if ( !@strtolower(ini_get('safe_mode')) == 'on' && !file_exists("$captcha_path_cf/securimage.php") ) {
       $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says captcha_library not found.', 'si-contact-form').'</p>';
       $ok = 'no';
  }
  if ($ok == 'no')  return false;
  return true;
}

function ctf_sfc_filter($classes) {
$classes[] = 'ctf-captcha';
return $classes;
}


// this function adds the captcha to the contact form
function addCaptchaToContactForm($si_contact_error_captcha,$form_id_num) {
   global $captcha_url_cf, $si_contact_opt;
   $req_field_ind = ( $si_contact_opt['req_field_indicator_enable'] == 'true' ) ? ' <span class="required">'.$si_contact_opt['req_field_indicator'].'</span>' : '';

// fix for simple facebook connect plugin
// http://wordpress.org/support/topic/402560
add_filter('sfc_img_exclude',array(&$this,'ctf_sfc_filter'),1);

  $string = '';

// Test for some required things, print error message right here if not OK.
if ($this->captchaCheckRequires()) {

  $captcha_level_file = 'securimage_show_medium.php';
  if ($si_contact_opt['captcha_difficulty'] == 'low') {
      $captcha_level_file = 'securimage_show_low.php';
  } else if ($si_contact_opt['captcha_difficulty'] == 'high') {
      $captcha_level_file = 'securimage_show_high.php';
  }
  if ($si_contact_opt['captcha_no_trans'] == 'true')
     $captcha_level_file = 'securimage_show_no_trans.php';

// the captch html
$string = '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_captcha_code'.$form_id_num.'">';
     $string .= ($si_contact_opt['title_capt'] != '') ? $si_contact_opt['title_capt'] : __('CAPTCHA Code', 'si-contact-form').':';
     $string .= $req_field_ind.'</label>
        </div> '.$this->ctf_echo_if_error($si_contact_error_captcha).'
        <div '.$this->si_contact_convert_css($si_contact_opt['field_div_style']).'>
                <input '.$this->ctf_field_style.' type="text" value="" id="si_contact_captcha_code'.$form_id_num.'" name="si_contact_captcha_code" '.$this->ctf_aria_required.' size="'.absint($si_contact_opt['captcha_field_size']).'" />
        </div>

<div style="'.$si_contact_opt['captcha_div_style'].'">
         <img class="ctf-captcha" id="si_image_ctf'.$form_id_num.'" ';
         $string .= ($si_contact_opt['captcha_image_style'] != '') ? 'style="' . esc_attr( $si_contact_opt['captcha_image_style'] ).'"' : '';
         $string .= ' src="'.$captcha_url_cf.'/'.$captcha_level_file.'?';
         if($si_contact_opt['captcha_small'] == 'true')
             $string .= 'ctf_sm_captcha=1&amp;';
         $string .= 'ctf_form_num='.$form_id_num.'&amp;sid='.md5(uniqid(time())).'" alt="';
         $string .= ($si_contact_opt['tooltip_captcha'] != '') ? esc_attr( $si_contact_opt['tooltip_captcha'] ) : esc_attr(__('CAPTCHA Image', 'si-contact-form'));
         $string .='" title="';
         $string .= ($si_contact_opt['tooltip_captcha'] != '') ? esc_attr( $si_contact_opt['tooltip_captcha'] ) : esc_attr(__('CAPTCHA Image', 'si-contact-form'));
         $string .= '" />';

    if($si_contact_opt['enable_audio'] == 'true') {
       if($si_contact_opt['enable_audio_flash'] == 'true') {
        $parseUrl = parse_url($captcha_url_cf);
        $secureimage_url = $parseUrl['path'];
        $string .= '
        <object type="application/x-shockwave-flash"
                data="'.$secureimage_url.'/securimage_play.swf?ctf_form_num='.$form_id_num.'&amp;bgColor1=#8E9CB6&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5&amp;audio='.$secureimage_url.'/securimage_play.php?ctf_form_num='.$form_id_num.'"
                id="SecurImage_as3_'.$form_id_num.'" width="19" height="19">
			    <param name="allowScriptAccess" value="sameDomain" />
			    <param name="allowFullScreen" value="false" />
			    <param name="movie" value="'.$secureimage_url.'/securimage_play.swf?ctf_form_num='.$form_id_num.'&amp;bgColor1=#8E9CB6&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5&amp;audio='.$secureimage_url.'/securimage_play.php?ctf_form_num='.$form_id_num.'" />
			    <param name="quality" value="high" />
			    <param name="bgcolor" value="#ffffff" />
		</object>
        <br />';
      }else{
         $string .= '<a href="'.$captcha_url_cf.'/securimage_play.php?ctf_form_num='.$form_id_num.'" title="';
         $string .= ($si_contact_opt['tooltip_audio'] != '') ? esc_attr( $si_contact_opt['tooltip_audio'] ) : esc_attr(__('CAPTCHA Audio', 'si-contact-form'));
         $string .= '">
         <img src="'.$captcha_url_cf.'/images/audio_icon.gif" alt="';
         $string .= ($si_contact_opt['tooltip_audio'] != '') ? esc_attr( $si_contact_opt['tooltip_audio'] ) : esc_attr(__('CAPTCHA Audio', 'si-contact-form'));
         $string .= '" ';
         $string .= ($si_contact_opt['audio_image_style'] != '') ? 'style="' . esc_attr( $si_contact_opt['audio_image_style'] ).'"' : '';
         $string .= ' onclick="this.blur()" /></a><br />';
      }
   }

         $string .= '<a href="#" title="';
         $string .= ($si_contact_opt['tooltip_refresh'] != '') ? esc_attr( $si_contact_opt['tooltip_refresh'] ) : esc_attr(__('Refresh Image', 'si-contact-form'));
         $string .= '" onclick="document.getElementById(\'si_image_ctf'.$form_id_num.'\').src = \''.$captcha_url_cf.'/'.$captcha_level_file.'?';
         if($si_contact_opt['captcha_small'] == 'true')
             $string .= 'ctf_sm_captcha=1&amp;';
         $string .= 'ctf_form_num='.$form_id_num.'&amp;sid=\' + Math.random(); return false">
         <img src="'.$captcha_url_cf.'/images/refresh.gif" alt="';
         $string .= ($si_contact_opt['tooltip_refresh'] != '') ? esc_attr( $si_contact_opt['tooltip_refresh'] ) : esc_attr(__('Refresh Image', 'si-contact-form'));
         $string .=  '" ';
         $string .= ($si_contact_opt['reload_image_style'] != '') ? 'style="' . esc_attr( $si_contact_opt['reload_image_style'] ).'"' : '';
         $string .=  ' onclick="this.blur()" /></a>
   </div>
';
} else {
      $string .= $this->captchaRequiresError;
}
  return $string;
} // end function addCaptchaToContactForm

// shows contact form errors
function ctf_echo_if_error($this_error){
  if ($this->si_contact_error) {
    if (!empty($this_error)) {
         return '
         <div '.$this->ctf_error_style.'>'. $this_error . '</div>'."\n";
    }
  }
} // end function ctf_echo_if_error

// functions for protecting and validating form input vars
function ctf_clean_input($string) {
    if (is_string($string)) {
      return trim($this->ctf_sanitize_string(strip_tags($this->ctf_stripslashes($string))));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = $this->ctf_clean_input($value);
      }
      return $string;
    } else {
      return $string;
    }
} // end function ctf_clean_input

// functions for protecting and validating form vars
function ctf_sanitize_string($string) {
    $string = preg_replace("/ +/", ' ', trim($string));
    return preg_replace("/[<>]/", '_', $string);
} // end function ctf_sanitize_string

// functions for protecting and validating form vars
function ctf_stripslashes($string) {
       // if (get_magic_quotes_gpc()) {
                return stripslashes($string);
       // } else {
       //        return $string;
       // }
} // end function ctf_stripslashes

// functions for protecting and validating form input vars
function ctf_output_string($string) {
    return str_replace('"', '&quot;', $string);
} // end function ctf_output_string

// A function knowing about name case (i.e. caps on McDonald etc)
// $name = name_case($name);
function ctf_name_case($name) {
   global $si_contact_opt;

   if ($si_contact_opt['name_case_enable'] !== 'true') {
        return $name; // name_case setting is disabled for si contact
   }
   if ($name == '') return '';
   $break = 0;
   $newname = strtoupper($name[0]);
   for ($i=1; $i < strlen($name); $i++) {
       $subed = substr($name, $i, 1);
       if (((ord($subed) > 64) && (ord($subed) < 123)) ||
           ((ord($subed) > 48) && (ord($subed) < 58))) {
           $word_check = substr($name, $i - 2, 2);
           if (!strcasecmp($word_check, 'Mc') || !strcasecmp($word_check, "O'")) {
               $newname .= strtoupper($subed);
           }else if ($break){
               $newname .= strtoupper($subed);
           }else{
               $newname .= strtolower($subed);
           }
             $break = 0;
       }else{
             // not a letter - a boundary
             $newname .= $subed;
             $break = 1;
       }
   }
   return $newname;
} // end function ctf_name_case


// checks proper email syntax (not perfect, none of these are, but this is the best I can find)
function ctf_validate_email($email) {
   global $si_contact_opt;

   //check for all the non-printable codes in the standard ASCII set,
   //including null bytes and newlines, and return false immediately if any are found.
   if (preg_match("/[\\000-\\037]/",$email)) {
      return false;
   }
   // regular expression used to perform the email syntax check
   // http://fightingforalostcause.net/misc/2006/compare-email-regex.php
   //$pattern = "/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|asia|cat|jobs|tel|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i";
   //$pattern = "/^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+)(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/i";
   $pattern = "/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD";
   if(!preg_match($pattern, $email)){
      return false;
   }
   // Make sure the domain exists with a DNS check (if enabled in options)
   // MX records are not mandatory for email delivery, this is why this function also checks A and CNAME records.
   // if the checkdnsrr function does not exist (skip this extra check, the syntax check will have to do)
   // checkdnsrr available in Linux: PHP 4.3.0 and higher & Windows: PHP 5.3.0 and higher
   if ($si_contact_opt['email_check_dns'] == 'true') {
      if( function_exists('checkdnsrr') ) {
         list($user,$domain) = explode('@',$email);
         if(!checkdnsrr($domain.'.', 'MX') &&
            !checkdnsrr($domain.'.', 'A') &&
            !checkdnsrr($domain.'.', 'CNAME')) {
            // domain not found in DNS
            return false;
         }
      }
   }
   return true;
} // end function ctf_validate_email

// helps spam protect email input
// finds new lines injection attempts
function ctf_forbidifnewlines($input) {
   if (
       stristr($input, "\r")  !== false ||
       stristr($input, "\n")  !== false ||
       stristr($input, "%0a") !== false ||
       stristr($input, "%0d") !== false) {
         //wp_die(__('Contact Form has Invalid Input', 'si-contact-form'));
         $this->si_contact_error = 1;

   }
} // end function ctf_forbidifnewlines

// helps spam protect email input
// blocks contact form posted from other domains
function ctf_spamcheckpost() {

 if(!isset($_SERVER['HTTP_USER_AGENT'])){
     return 1;
 }

 // Make sure the form was indeed POST'ed:
 //  (requires your html form to use: si_contact_action="post")
 if(!$_SERVER['REQUEST_METHOD'] == "POST"){
    return 2;
 }

  // Make sure the form was posted from an approved host name.
 if ($this->ctf_domain_protect == 'true') {
   // Host names from where the form is authorized to be posted from:
   if (is_array($this->ctf_domain)) {
      $this->ctf_domain = array_map(strtolower, $this->ctf_domain);
      $authHosts = $this->ctf_domain;
   } else {
      $this->ctf_domain =  strtolower($this->ctf_domain);
      $authHosts = array("$this->ctf_domain");
   }

   // Where have we been posted from?
   if( isset($_SERVER['HTTP_REFERER']) and trim($_SERVER['HTTP_REFERER']) != '' ) {
      $fromArray = parse_url(strtolower($_SERVER['HTTP_REFERER']));
      // Test to see if the $fromArray used www to get here.
      $wwwUsed = preg_match("/^www\./i",$fromArray['host']);
      if(!in_array((!$wwwUsed ? $fromArray['host'] : preg_replace("/^www\./i",'',$fromArray['host'])), $authHosts ) ){
         return 3;
      }
   }
 } // end if domain protect

 // check posted input for email injection attempts
 // Check for these common exploits
 // if you edit any of these do not break the syntax of the regex
 $input_expl = "/(content-type|mime-version|content-transfer-encoding|to:|bcc:|cc:|document.cookie|document.write|onmouse|onkey|onclick|onload)/i";
 // Loop through each POST'ed value and test if it contains one of the exploits fromn $input_expl:
 foreach($_POST as $k => $v){
   $v = strtolower($v);
   $v = str_replace('donkey','',$v); // fixes invalid input with "donkey" in string
   $v = str_replace('monkey','',$v); // fixes invalid input with "monkey" in string
   if( preg_match($input_expl, $v) ){
     return 4;
   }
 }

 return 0;
} // end function ctf_spamcheckpost

function si_contact_plugin_action_links( $links, $file ) {
    //Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
        $settings_link = '<a href="plugins.php?page=si-contact-form/si-contact-form.php">' . __( 'Settings', 'si-contact-form' ) . '</a>';
	    array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
} // end function si_contact_plugin_action_links

function si_contact_form_num() {

     // get options
    $si_contact_gb_mf = get_option("si_contact_form_gb");

    $form_num = '';
    if ( isset($_GET['ctf_form_num']) && is_numeric($_GET['ctf_form_num']) && $_GET['ctf_form_num'] <= $si_contact_gb_mf['max_forms'] ) {
       $form_num = (int)$_GET['ctf_form_num'];
    }
    return $form_num;
} // end function si_contact_form_num

// load things during init
function si_contact_init() {

   if (function_exists('load_plugin_textdomain')) {
      load_plugin_textdomain('si-contact-form', false, dirname(plugin_basename(__FILE__)).'/languages' );
   }

} // end function si_contact_init

function si_contact_get_options($form_num) {
   global $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults;

      $si_contact_gb_defaults = array(
         'donated' => 'false',
         'max_forms' => '4',
         'max_fields' => '8',
      );

     $si_contact_option_defaults = array(
         'welcome' => __('<p>Comments or questions are welcome.</p>', 'si-contact-form'),
         'email_to' => __('Webmaster', 'si-contact-form').','.get_option('admin_email'),
         'php_mailer_enable' => 'wordpress',
         'email_from' => '',
         'email_bcc' => '',
         'email_subject' => get_option('blogname') . ' ' .__('Contact:', 'si-contact-form'),
         'email_subject_list' => '',
         'name_type' => 'required',
         'email_type' => 'required',
         'subject_type' => 'required',
         'message_type' => 'required',
         'double_email' => 'false',
         'name_case_enable' => 'false',
         'domain_protect' => 'true',
         'email_check_dns' => 'true',
         'captcha_enable' => 'true',
         'captcha_small' => 'false',
         'captcha_difficulty' => 'medium',
         'captcha_no_trans' => 'false',
         'enable_audio' => 'true',
         'enable_audio_flash' => 'false',
         'captcha_perm' => 'false',
         'captcha_perm_level' => 'read',
         'redirect_enable' => 'true',
         'redirect_seconds' => '3',
         'redirect_url' => 'index.php',
         'date_format' => 'mm/dd/yyyy',
         'req_field_indicator_enable' => 'true',
         'req_field_label_enable' => 'true',
         'req_field_indicator' => '*',
         'border_enable' => 'false',
         'form_style' => 'width:375px;',
         'border_style' => 'border:1px solid black;',
         'required_style' => 'text-align:left;',
         'title_style' => 'text-align:left; padding-top:10px;',
         'select_style' => 'text-align:left;',
         'field_style' => 'text-align:left;',
         'field_div_style' => 'text-align:left;',
         'error_style' => 'color:red; text-align:left;',
         'captcha_div_style' => 'width: 250px; height: 55px; padding-top:10px;',
         'captcha_image_style' => 'border-style:none; margin:0; padding-right:5px; float:left;',
         'audio_image_style' => 'border-style:none; margin:0; vertical-align:top;',
         'reload_image_style' => 'border-style:none; margin:0; vertical-align:bottom;',
         'button_style' => 'margin:0; cursor:pointer;',
         'field_size' => '40',
         'captcha_field_size' => '6',
         'text_cols' => '40',
         'text_rows' => '15',
         'aria_required' => 'false',
         'auto_fill_enable' => 'true',
         'title_border' => '',
         'title_dept' => '',
         'title_select' => '',
         'title_name' => '',
         'title_email' => '',
         'title_email2' => '',
         'title_email2_help' => '',
         'title_subj' => '',
         'title_mess' => '',
         'title_capt' => '',
         'title_submit' => '',
         'text_message_sent' => '',
         'tooltip_required' => '',
         'tooltip_captcha' => '',
         'tooltip_audio' => '',
         'tooltip_refresh' => '',
         'enable_credit_link' => 'true',
         'error_contact_select' => '',
         'error_name'           => '',
         'error_email'          => '',
         'error_email2'         => '',
         'error_field'          => '',
         'error_subject'        => '',
         'error_message'        => '',
         'error_input'          => '',
         'error_captcha_blank'  => '',
         'error_captcha_wrong'  => '',
         'error_correct'        => '',
  );

   // optional extra fields
  $si_contact_max_fields = ( isset($_POST['si_contact_max_fields']) && is_numeric($_POST['si_contact_max_fields']) ) ? $_POST['si_contact_max_fields'] : $si_contact_gb_defaults['max_fields'];
  for ($i = 1; $i <= $si_contact_max_fields; $i++) {
        $si_contact_option_defaults['ex_field'.$i.'_default'] = '0';
        $si_contact_option_defaults['ex_field'.$i.'_req'] = 'false';
        $si_contact_option_defaults['ex_field'.$i.'_label'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_type'] = 'text';
  }


  // upgrade path from old version
  if (!get_option('si_contact_form') && get_option('si_contact_email_to')) {
    // just now updating, migrate settings
    $si_contact_option_defaults = $this->si_contact_migrate($si_contact_option_defaults);
  }

    // upgrade path from old version  2.0.1 or older
  if (!get_option('si_contact_form_gb') && get_option('si_contact_form')) {
    // just now updating, migrate settings
    $si_contact_gb_defaults = $this->si_contact_migrate2($si_contact_gb_defaults);
  }

  // install the global option defaults
  add_option('si_contact_form_gb',  $si_contact_gb_defaults, '', 'yes');

  // install the option defaults
  add_option('si_contact_form',  $si_contact_option_defaults, '', 'yes');

  // multi-form
  $si_contact_max_forms = ( isset($_POST['si_contact_max_forms']) && is_numeric($_POST['si_contact_max_forms']) ) ? $_POST['si_contact_max_forms'] : $si_contact_gb_defaults['max_forms'];
  for ($i = 2; $i <= $si_contact_max_forms; $i++) {
     add_option("si_contact_form$i", $si_contact_option_defaults, '', 'yes');
  }

    // get the options from the database
  $si_contact_gb = get_option("si_contact_form_gb");

  // array merge incase this version has added new options
  $si_contact_gb = array_merge($si_contact_gb_defaults, $si_contact_gb);

  // get the options from the database
  $si_contact_opt = get_option("si_contact_form$form_num");

  // array merge incase this version has added new options
  $si_contact_opt = array_merge($si_contact_option_defaults, $si_contact_opt);

  // strip slashes on get options array
  foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
  }
  if ($si_contact_opt['captcha_image_style'] == '' && $si_contact_opt['audio_image_style'] == '') {
     // if styles seem to be blank, reset styles
     $style_resets_arr = array('border_enable','border_width','border_style','title_style','field_style','error_style','captcha_div_style','captcha_image_style','audio_image_style','reload_image_style','button_style','field_size','text_cols','text_rows');
     foreach($style_resets_arr as $style_reset) {
           $si_contact_opt[$style_reset] = $si_contact_option_defaults[$style_reset];
     }
  }

  // reset captcha styles on version 2.5.7
  if ( !isset($si_contact_gb['2.5.7']) ) {
     $style_resets_arr = array('captcha_div_style','captcha_image_style','audio_image_style','reload_image_style');
       foreach($style_resets_arr as $style_reset) {
           $si_contact_opt[$style_reset] = $si_contact_option_defaults[$style_reset];
       }
       if(isset($si_contact_opt{$i}['hidden_subject_enable']) && $si_contact_opt{$i}['hidden_subject_enable'] == 'true')
            $si_contact_opt{$i}['subject_type'] = 'not_available';
       if(isset($si_contact_opt{$i}['hidden_message_enable']) && $si_contact_opt{$i}['hidden_message_enable'] == 'true')
            $si_contact_opt{$i}['message_type'] = 'not_available';
       update_option("si_contact_form", $si_contact_opt);
     for ($i = 2; $i <= $si_contact_gb['max_forms']; $i++) {
       // get the options from the database
       $si_contact_opt{$i} = get_option("si_contact_form$i");
       foreach($style_resets_arr as $style_reset) {
           $si_contact_opt{$i}[$style_reset] = $si_contact_option_defaults[$style_reset];
       }
       if(isset($si_contact_opt{$i}['hidden_subject_enable']) && $si_contact_opt{$i}['hidden_subject_enable'] == 'true')
            $si_contact_opt{$i}['subject_type'] = 'not_available';
       if(isset($si_contact_opt{$i}['hidden_message_enable']) && $si_contact_opt{$i}['hidden_message_enable'] == 'true')
            $si_contact_opt{$i}['message_type'] = 'not_available';
       update_option("si_contact_form$i", $si_contact_opt{$i});
       unset($si_contact_opt{$i});
     }
       $si_contact_opt = get_option("si_contact_form$form_num");
       $si_contact_opt = array_merge($si_contact_option_defaults, $si_contact_opt);
       foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
       }
      $si_contact_gb['2.5.7'] = 1;
      update_option("si_contact_form_gb", $si_contact_gb);
      $si_contact_gb = get_option("si_contact_form_gb");
      $si_contact_gb = array_merge($si_contact_gb_defaults, $si_contact_gb);
  }

  // new field type defaults on version 2.6.3
  if ( !isset($si_contact_gb['2.6.3']) ) {
          // optional extra fields
    for ($i = 1; $i <= $si_contact_gb['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] != 'radio' && $si_contact_opt['ex_field'.$i.'_type'] != 'select' ) {
                $si_contact_opt['ex_field'.$i.'_default'] = '0';
        }
        if ($si_contact_opt['ex_field'.$i.'_label'] == '') {
          $si_contact_opt['ex_field'.$i.'_default'] = '0';
        }
    }
    update_option("si_contact_form", $si_contact_opt);
    for ($i = 2; $i <= $si_contact_gb['max_forms']; $i++) {
       // get the options from the database
       $si_contact_opt{$i} = get_option("si_contact_form$i");
       for ($f = 1; $f <= $si_contact_gb['max_fields']; $f++) {
         if ($si_contact_opt{$i}['ex_field'.$f.'_label'] != '' && $si_contact_opt{$i}['ex_field'.$f.'_type'] != 'radio' && $si_contact_opt{$i}['ex_field'.$f.'_type'] != 'select' ) {
                $si_contact_opt{$i}['ex_field'.$f.'_default'] = '0';
         }
         if ($si_contact_opt{$i}['ex_field'.$f.'_label'] == '') {
          $si_contact_opt{$i}['ex_field'.$f.'_default'] = '0';
         }
       }
       update_option("si_contact_form$i", $si_contact_opt{$i});
       unset($si_contact_opt{$i});
    }
    $si_contact_opt = get_option("si_contact_form$form_num");
    $si_contact_opt = array_merge($si_contact_option_defaults, $si_contact_opt);
    foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
    }
    $si_contact_gb['2.6.3'] = 1;
    update_option("si_contact_form_gb", $si_contact_gb);
    $si_contact_gb = get_option("si_contact_form_gb");
    $si_contact_gb = array_merge($si_contact_gb_defaults, $si_contact_gb);
  }

} // end function si_contact_get_options

function si_contact_start_session() {
  // a PHP session cookie is set so that the captcha can be remembered and function
  // this has to be set before any header output
  // echo "starting session ctf";
  // start cookie session, but do not start session if captcha is disabled in options
  if( !isset( $_SESSION ) ) { // play nice with other plugins
    session_cache_limiter ('private, must-revalidate');
    session_start();
    //echo "session started ctf";
  }
} // end function si_contact_start_session

function si_contact_migrate($si_contact_option_defaults) {
  // read the options from the prior version
   $new_options = array ();
   foreach($si_contact_option_defaults as $key => $val) {
      $new_options[$key] = $this->ctf_stripslashes( get_option( "si_contact_$key" ));
      // now delete the options from the prior version
      delete_option("si_contact_$key");
   }
   // delete settings no longer used
   delete_option('si_contact_email_language');
   delete_option('si_contact_email_charset');
   delete_option('si_contact_email_encoding');
   // by returning this the old settings will carry over to the new version
   return $new_options;
} //  end function si_contact_migrate

function si_contact_migrate2($si_contact_gb_defaults) {
  // read the options from the prior version

   $new_options = array ();
   $migrate_opt = get_option("si_contact_form");
   $new_options['donated'] = $migrate_opt['donated'];
   $new_options['max_forms'] = $si_contact_gb_defaults['max_forms'];
   $new_options['max_fields'] = $si_contact_gb_defaults['max_fields'];
   if(defined('SI_CONTACT_FORM_MAX_FORMS') && SI_CONTACT_FORM_MAX_FORMS > $si_contact_gb_defaults['max_forms']) {
    $new_options['max_forms'] = SI_CONTACT_FORM_MAX_FORMS;
   }
   if(defined('SI_CONTACT_FORM_MAX_FIELDS') && SI_CONTACT_FORM_MAX_FIELDS > $si_contact_gb_defaults['max_fields']) {
    $new_options['max_fields'] = SI_CONTACT_FORM_MAX_FIELDS;
   }
   unset($migrate_opt);

   // by returning this the old settings will carry over to the new version
   //print_r($new_options); exit;
   return $new_options;
} //  end function si_contact_migrate2


function get_captcha_url_cf() {

  // The captcha URL cannot be on a different domain as the site rewrites to or the cookie won't work
  // also the path has to be correct or the image won't load.
  // WP_PLUGIN_URL was not getting the job done! this code should fix it.

  //http://media.example.com/wordpress   WordPress address get_option( 'siteurl' )
  //http://tada.example.com              Blog address      get_option( 'home' )

  //http://example.com/wordpress  WordPress address get_option( 'siteurl' )
  //http://example.com/           Blog address      get_option( 'home' )

  $site_uri = parse_url(get_option('home'));
  $home_uri = parse_url(get_option('siteurl'));

  $captcha_url_cf  = WP_PLUGIN_URL . '/si-contact-form/captcha-secureimage';

  if ($site_uri['host'] == $home_uri['host']) {
      $captcha_url_cf  = WP_PLUGIN_URL . '/si-contact-form/captcha-secureimage';
  } else {
      $captcha_url_cf  = get_option( 'home' ) . '/'.PLUGINDIR.'/si-contact-form/captcha-secureimage';
  }

  return $captcha_url_cf;
}

function si_contact_form_mail_from() {
 return $this->si_contact_mail_from;
}

function si_contact_form_from_name() {
 return $this->si_contact_from_name;
}

function si_contact_convert_css($string) {

    if( preg_match("/^style=\"(.*)\"$/i", $string) ){
      return $string;
    }
    if( preg_match("/^class=\"(.*)\"$/i", $string) ){
      return $string;
    }
    return 'style="'.$string.'"';

} // end function si_contact_convert_css

} // end of class
} // end of if class

// Pre-2.8 compatibility
if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return wp_specialchars( $text );
	}
}

// Pre-2.8 compatibility
if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return attribute_escape( $text );
	}
}

if (class_exists("siContactForm")) {
 $si_contact_form = new siContactForm();
}

if (isset($si_contact_form)) {

  $captcha_url_cf  = $si_contact_form->get_captcha_url_cf();
  $captcha_path_cf = WP_PLUGIN_DIR . '/si-contact-form/captcha-secureimage';

  // si_contact initialize options
  add_action('init', array(&$si_contact_form, 'si_contact_init'),1);

  //wp_enqueue_style('si_contact_form', plugins_url('si-contact-form/ctf_epoch_styles.css'), false, false, 'all');
  //wp_enqueue_script('si_contact_form', plugins_url('si-contact-form/ctf_epoch_classes.js'), '', '', true);

  // start the PHP session
  add_action('init', array(&$si_contact_form,'si_contact_start_session'),2);
  //add_action('parse_request', array(&$si_contact_form,'si_contact_start_session'),2);
  //add_action('plugins_loaded', array(&$si_contact_form,'si_contact_start_session'),2);

  // si contact form admin options
  add_action('admin_menu', array(&$si_contact_form,'si_contact_add_tabs'),1);

  // adds "Settings" link to the plugin action page
  add_filter( 'plugin_action_links', array(&$si_contact_form,'si_contact_plugin_action_links'),10,2);

  // use shortcode to print the contact form or process contact form logic
  // can use dashes or underscores: [si-contact-form] or [si_contact_form]
  add_shortcode('si_contact_form', array(&$si_contact_form,'si_contact_form_short_code'),1);
  add_shortcode('si-contact-form', array(&$si_contact_form,'si_contact_form_short_code'),1);

    // options deleted when this plugin is deleted in WP 2.7+
  if ( function_exists('register_uninstall_hook') )
     register_uninstall_hook(__FILE__, 'si_contact_unset_options');

}

?>