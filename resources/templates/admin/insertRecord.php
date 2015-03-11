<?php

/* 
 * Handles form input once JS validation is completed
 */

// FirePHP is not dependent on anything else, i.e. doesn't require config.php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__))))); 
require_once (__ROOT__ . '/FirePHPCore/fb.php');
ob_start();
$firephp = FirePHP::getInstance(true);

define('__RESOURCES__', dirname(dirname(dirname(__FILE__)))); 
require_once(__RESOURCES__. "/config.php"); 

require_once(TEMPLATES_PATH . "/admin/getFileName.php");
require_once(TEMPLATES_PATH . "/admin/phpFunctions.php");
require_once(TEMPLATES_PATH . "/admin/phpValidation.php");

//$firephp->log('Found all required files for insertRecord.php');

// TODO: replace this with variables from mysql
$field_names = array("day", "month", "year", "time", "sermon_title", "series",
    "preacher", "bible_book", "bible_ch_start", "bible_verse_start",
    "bible_ch_end", "bible_verse_end", "file_name");

$found_error = "";
$count = 0;

// New version but perhaps doesn't work
if ($_POST['submitted'] == "yes") {
    $firephp->log('Processing insertRecord()');
    
    $cleanFileName = "";
    $date = "";
    
    // Variables supplied by HTML form
    $firephp->log('Generating variables...');
    foreach ($field_names as $field) {
        $firephp->log('Generating variable ' . $field);
        ${$field} = "";
        $firephp->log('Generated variable ' . ${$field});
    }
        
    $found_error = "";

    $firephp->log('Checking input of fields...');
    $found_error .= checkInput($field_names);
    $firephp->log('found_error: ' . $found_error);
    // TODO: also check for sanitized strings
    
    // checkdate() is an inbuilt function
    if( checkdate($month, $day, $year)){
      $date = $year . "-" . $month . "-" . $day;
    }
    else{
      $found_error .= "Invalid date entered<br />";
    }
    $firephp->log('found_error: ' . $found_error);
 
    $bible_ref = $bible_ch_start . ":" . $bible_verse_start . " - " . $bible_ch_end . ":" . $bible_verse_end;
    // TODO: Warn if date is not a Sunday

    if (!$_FILES) {
        $found_error .= "No file was posted<br />";
    }
    else {
        $found_error .= getFileName($date, $time, $sermon_title, $cleanFileName);
    }
    $firephp->log('found_error: ' . $found_error);

    //echo "<div>Found error: $found_error</div>";
    
    // If fails, reloads the HTML form with error
    if ($found_error == "") {
        // TODO: replace with variables as order has changed
        // TODO: replace db table name with variable
        $query = "INSERT INTO sermon_files VALUES" .
           "(NULL, '$sermon_title', '$series', '$date', '$time', '$bible_book', '$bible_ref', '$preacher', '$cleanFileName')";

        // LER TEMP REMOVED
//        if(!mysql_query($query, $connected_to_db)) {
//         $found_error .= "INSERT failed: $query<br />" .
//           mysql_error() . "<br /><br />";
//         echo $found_error;
//         $firephp->log('found_error: ' . $found_error);
//        }
//        else { 
          echo "Success!<br />";
          //TODO: replace with soft link to createNewRecord.php
          echo "<form action=\"createNewRecord.php\"><input type=\"submit\" value=\"Enter another record\"></form>";
          //TODO: replace with soft link to sermons.php
          echo "<form action=\"sermons.php\"><input type=\"submit\" value=\"Go to sermons page\"></form>";
//        }
        exit; 
    }    
    $firephp->log('found_error: ' . $found_error);
    return $found_error;
}
ob_end_flush(); 


?>