<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// FirePHP is not dependent on anything else, i.e. doesn't require config.php
//define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__))))); 
require_once (__ROOT__ . '/FirePHPCore/fb.php');
ob_start();
//$firephp = FirePHP::getInstance(true);

function checkFieldValuesAreNotBlank($field_values) {
    FB::log('Checking fields are not blank...');
    $found_error = "";
    foreach ($field_values as $field) {
        if(!isNotBlank($field)){
            $found_error .= "$field must not be blank";
            FB::log($found_error);
        }
    }
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

ob_end_flush(); 