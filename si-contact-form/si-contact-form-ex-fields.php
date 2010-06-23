<?php

      for ($i = 1; $i <= $si_contact_gb['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '') {
           $ex_req_field_ind = ($si_contact_opt['ex_field'.$i.'_req'] == 'true') ? $req_field_ind : '';
           $ex_req_field_aria = ($si_contact_opt['ex_field'.$i.'_req'] == 'true') ? $this->ctf_aria_required : '';
           if(!$si_contact_opt['ex_field'.$i.'_type'] ) $si_contact_opt['ex_field'.$i.'_type'] = 'text';
           if(!$si_contact_opt['ex_field'.$i.'_default'] ) $si_contact_opt['ex_field'.$i.'_default'] = '0';

          switch ($si_contact_opt['ex_field'.$i.'_type']) {
           case 'text':

                 $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div> '.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
        <div '.$this->ctf_field_div_style.'>
                <input '.$this->ctf_field_style.' type="text" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="' . $this->ctf_output_string(${'ex_field'.$i}) . '" '.$ex_req_field_aria.' size="'.$ctf_field_size.'" />
        </div>';
              break;
           case 'textarea':

                $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div> '.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
        <div '.$this->ctf_field_div_style.'>
                <textarea '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" '.$ex_req_field_aria.' cols="'.absint($si_contact_opt['text_cols']).'" rows="'.absint($si_contact_opt['text_rows']).'">' . $this->ctf_output_string(${'ex_field'.$i}) . '</textarea>
        </div>';
              break;

           case 'checkbox':

$exf_opts_array = array();
$exf_opts_label = '';
$exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
if(preg_match("/,/", $exf_array_test) && preg_match("/;/", $exf_array_test)  ) {
       list($exf_opts_label, $value) = explode(",",$exf_array_test);
       $exf_opts_label   = trim($exf_opts_label);
       $value = trim($value);
       if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A checkbox field is not configured properly in settings.', 'si-contact-form'));
          } else {
               // multiple options
               $exf_opts_array = explode(";",$value);
         }
      }

  // checkbox children

           $string .=   '
        <div '.$this->ctf_title_style.'>
          <label>' . $exf_opts_label .'</label>'."\n";

     $ex_cnt = 1;
  foreach ($exf_opts_array as $k) {

     $string .=   '<br /><input type="checkbox" id="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'" name="si_contact_ex_field'.$i.'_'.$ex_cnt.'" value="selected"  ';
                 if ( isset(${'ex_field'.$i.'_'.$ex_cnt}) && ${'ex_field'.$i.'_'.$ex_cnt} == 'selected' )
                    $string .= ' checked="checked" ';
                 $string .= '/>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'">' . $k .'</label>'."\n";
     $ex_cnt++;
  }

   $string .=   '
        </div> '.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i})."\n";

} else {

  // single
               $string .=   '
        <div '.$this->ctf_title_style.'>
            <input type="checkbox" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="selected" ';
    if (${'ex_field'.$i} != '') {
      if (${'ex_field'.$i} == 'selected') {
         $string .= 'checked="checked" ';
      }
    }else{
      if (!isset($_POST['si_contact_action']) && $si_contact_opt['ex_field'.$i.'_default'] == '1') {
         $string .= 'checked="checked" ';
      }
    }
                 $string .= '/>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div> '.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
';

} // end else

             break;

           case 'select':

           // find the label and the options inside $si_contact_opt['ex_field'.$i.'_label']
           // the drop down list array will be made automatically by this code
$exf_opts_array = array();
$exf_opts_label = '';
$exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
if(!preg_match("/,/", $exf_array_test) ) {
       // error
       $this->si_contact_error = 1;
       $string .= $this->ctf_echo_if_error(__('Error: A select field is not configured properly in settings.', 'si-contact-form'));
} else {
       list($exf_opts_label, $value) = explode(",",$exf_array_test);
       $exf_opts_label   = trim($exf_opts_label);
       $value = trim($value);
       if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A select field is not configured properly in settings.', 'si-contact-form'));
          } else {
               // multiple options
               $exf_opts_array = explode(";",$value);
         }
      }
} // end else

           $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' . $exf_opts_label .$ex_req_field_ind.'</label>
        </div> '.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
        <div '.$this->ctf_field_div_style.'>
               <select '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'">
        ';

$exf_opts_ct = 1;
$selected = '';
foreach ($exf_opts_array as $k) {
 if (${'ex_field'.$i} != '') {
    if (${'ex_field'.$i} == "$k") {
      $selected = ' selected="selected"';
    }
 }else{
    if ($exf_opts_ct == $si_contact_opt['ex_field'.$i.'_default']) {
      $selected = ' selected="selected"';
    }
 }

 $string .= '<option value="'.$this->ctf_output_string($k).'"'.$selected.'>'.$this->ctf_output_string($k).'</option>'."\n";
 $exf_opts_ct++;
 $selected = '';

}
$string .= '</select>
        </div>';
             break;
           case 'radio':

           // find the label and the options inside $si_contact_opt['ex_field'.$i.'_label']
           // the radio list array will be made automatically by this code
$exf_opts_array = array();
$exf_opts_label = '';
$exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
if(!preg_match('/,/', $exf_array_test) ) {
       // error
       $this->si_contact_error = 1;
       $string .= $this->ctf_echo_if_error(__('Error: A radio field is not configured properly in settings.', 'si-contact-form'));
} else {
       list($exf_opts_label, $value) = explode(",",$exf_array_test);
       $exf_opts_label   = trim($exf_opts_label);
       $value = trim($value);
       if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // error
               $this->si_contact_error = 1;
               $string .= $this->ctf_echo_if_error(__('Error: A radio field is not configured properly in settings.', 'si-contact-form'));
          } else {
               // multiple options
               $exf_opts_array = explode(";",$value);
         }
      }
} // end else

           $string .=   '
        <div '.$this->ctf_title_style.'>
          <label>' . $exf_opts_label .$ex_req_field_ind.'</label>'."\n";

$selected = '';
$ex_cnt = 1;
foreach ($exf_opts_array as $k) {
 if (${'ex_field'.$i} != '') {
    if (${'ex_field'.$i} == "$k") {
      $selected = ' checked="checked"';
    }
 }else{
    if ($ex_cnt == $si_contact_opt['ex_field'.$i.'_default']) {
      $selected = ' checked="checked"';
    }
 }
 $string .= '<br /><input type="radio" '.$this->ctf_field_style.' id="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'" name="si_contact_ex_field'.$i.'" value="'.$this->ctf_output_string($k).'"'.$selected.' />
 <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'_'.$ex_cnt.'">' . $k .'</label>'."\n";
 $selected = '';
 $ex_cnt++;
}
$string .= $this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
        </div>';
             break;
             case 'date':

                 $string .=   '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_ex_field'.$form_id_num.'_'.$i.'">' .$si_contact_opt['ex_field'.$i.'_label'] .$ex_req_field_ind.'</label>
        </div> '.$this->ctf_echo_if_error(${'si_contact_error_ex_field'.$i}).'
        <div '.$this->ctf_field_div_style.'>
                <input '.$this->ctf_field_style.' type="text" id="si_contact_ex_field'.$form_id_num.'_'.$i.'" name="si_contact_ex_field'.$i.'" value="';
                $string .=   ( isset(${'ex_field'.$i}) && ${'ex_field'.$i} != '') ? $this->ctf_output_string(${'ex_field'.$i}): $si_contact_opt['date_format'];
                $string .=   '" '.$ex_req_field_aria.' size="15" />
        </div>';

             break;
          }

        } // end if label
      } // end foreach

 // how many extra fields are date fields?
     $ex_date_found = array();
     for ($i = 1; $i <= $si_contact_gb['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] == 'date') {
          $ex_date_found[$i] = $i;
        }
     }
     if (isset($ex_date_found) && count($ex_date_found) > 0 ) {
     $string .=   '
<link rel="stylesheet" type="text/css" href="'.WP_PLUGIN_URL.'/si-contact-form/date/ctf_epoch_styles.css?'.time().'" />
<script type="text/javascript">
	var ctf_daylist = new Array( \''.__('Su', 'si-contact-form').'\',\''.__('Mo', 'si-contact-form').'\',\''.__('Tu', 'si-contact-form').'\',\''.__('We', 'si-contact-form').'\',\''.__('Th', 'si-contact-form').'\',\''.__('Fr', 'si-contact-form').'\',\''.__('Sa', 'si-contact-form').'\',\''.__('Su', 'si-contact-form').'\',\''.__('Mo', 'si-contact-form').'\',\''.__('Tu', 'si-contact-form').'\',\''.__('We', 'si-contact-form').'\',\''.__('Th', 'si-contact-form').'\',\''.__('Fr', 'si-contact-form').'\',\''.__('Sa', 'si-contact-form').'\' );
	var ctf_months_sh = new Array( \''.__('Jan', 'si-contact-form').'\',\''.__('Feb', 'si-contact-form').'\',\''.__('Mar', 'si-contact-form').'\',\''.__('Apr', 'si-contact-form').'\',\''.__('May', 'si-contact-form').'\',\''.__('Jun', 'si-contact-form').'\',\''.__('Jul', 'si-contact-form').'\',\''.__('Aug', 'si-contact-form').'\',\''.__('Sep', 'si-contact-form').'\',\''.__('Oct', 'si-contact-form').'\',\''.__('Nov', 'si-contact-form').'\',\''.__('Dec', 'si-contact-form').'\' );
	var ctf_monthup_title = \''.__('Go to the next month', 'si-contact-form').'\';
	var ctf_monthdn_title = \''.__('Go to the previous month', 'si-contact-form').'\';
	var ctf_clearbtn_caption = \''.__('Clear', 'si-contact-form').'\';
	var ctf_clearbtn_title = \''.__('Clears any dates selected on the calendar', 'si-contact-form').'\';
	var ctf_maxrange_caption = \''.__('This is the maximum range', 'si-contact-form').'\';
    var ctf_date_format = \'';
 if($si_contact_opt['date_format'] == 'mm/dd/yyyy')
      $string .=   'm/d/Y';
  if($si_contact_opt['date_format'] == 'dd/mm/yyyy')
      $string .=   'd/m/Y';
 $string .= '\';
</script>
<script type="text/javascript" src="'.WP_PLUGIN_URL.'/si-contact-form/date/ctf_epoch_classes.js?'.time().'"></script>
<script type="text/javascript">
var ';
        $ex_date_var_string = '';
        foreach ($ex_date_found as $v) {
          $ex_date_var_string .= "dp_cal$form_id_num".'_'."$v,";
        }
        $ex_date_var_string = substr($ex_date_var_string,0,-1);
$string .= "$ex_date_var_string;\n";
$string .= 'window.onload = function () {
';
        foreach ($ex_date_found as $v) {
          $string .= "dp_cal$form_id_num".'_'."$v  = new Epoch('epoch_popup$form_id_num".'_'."$v','popup',document.getElementById('si_contact_ex_field$form_id_num".'_'."$v'));\n";
        }
$string .=   "};\n</script>\n";

     }

?>