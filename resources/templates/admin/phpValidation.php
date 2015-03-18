<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// FirePHP is not dependent on anything else, i.e. doesn't require config.php
//define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__))))); 
require_once (__ROOT__ . '/FirePHPCore/fb.php');
ob_start();
//$firephp = FirePHP::getInstance(true);

function checkFieldValuesAreNotBlank($field_list) {
    FB::log('Checking fields are not blank...');
    $found_error = "";
    
    // File name is handled separately
    $expected_blank_fields = array('', 'id', 'bible_ref', 'date', 'file_name');
    
//    foreach ($field_lists as $field_list) {
//            FB::log("Field list: ");
//            FB::log($field_list);
        
        foreach ($field_list as $key => $value) {
            if (in_array($key, $expected_blank_fields)) {
//                FB::log("Skipping " . $key . " as is expected to be blank");
                continue;
            }
//            FB::log("Checking field " . $key);

            if (isBlank($value)) {
                $found_error .= "'$key' must not be blank";
                FB::log($found_error);
            }
        }
//    }
    return $found_error;
}


function validateBibleRef($bible_ref, $bible_ch_start, $bible_ch_end, 
        $bible_verse_start, $bible_verse_end) {
    if ($bible_ch_end < $bible_ch_start)
      {
          $bible_ch_end = $bible_ch_start;
          // TODO log that bible ref has been altered
      }
      else if ($bible_ch_end == $bible_ch_start)
      {
          if ($bible_verse_end < $bible_verse_start)
              $bible_verse_end = $bible_verse_start;
          // TODO log that bible ref has been altered
      }

      return "$bible_ch_start:$bible_verse_start - $bible_ch_end:$bible_verse_end";
}

function validateUploadedFile ($_FILES) {

}

if (ob_get_level()>1) {
    ob_end_flush();
}