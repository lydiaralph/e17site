<?php

/* 
 * Handles form input once JS validation is completed
 */

define('__RESOURCES__', dirname(dirname(dirname(__FILE__)))); 
require_once(__RESOURCES__. "/config.php"); 

require_once(TEMPLATES_PATH . "/admin/getFileName.php");
require_once(TEMPLATES_PATH . "/admin/phpFunctions.php");
require_once(TEMPLATES_PATH . "/admin/phpValidation.php");

function insertRecord($field_names){
    
    //echo "<div>Got to insertRecord</div>";
    
    $cleanFileName = "";
    $date = "";
    
    // Variables supplied by HTML form
    foreach ($field_names as $field) {
        ${$field} = "";
    }
        
    $found_error = "";

    $found_error .= checkInput($field_names);
    
    if( checkdate($month, $day, $year)){
      $date = $year . "-" . $month . "-" . $day;
    }
    else{
      $found_error .= "Invalid date entered<br />";
    }
 
    $bible_ref = $bible_ch_start . ":" . $bible_verse_start . " - " . $bible_ch_end . ":" . $bible_verse_end;
    // TODO: Warn if date is not a Sunday

    if (!$_FILES) {
        $found_error .= "No file was posted<br />";
    }
    else {
        $found_error .= getFileName($date, $time, $sermon_title, $cleanFileName);
    }

    //echo "<div>Found error: $found_error</div>";
    
    // If fails, reloads the HTML form with error
    if ($found_error == "") {
        // TODO: replace with variables as order has changed
        // TODO: replace db table name with variable
        $query = "INSERT INTO sermon_files VALUES" .
           "(NULL, '$sermon_title', '$series', '$date', '$time', '$bible_book', '$bible_ref', '$preacher', '$cleanFileName')";

        if(!mysql_query($query, $connected_to_db)) {
         $found_error .= "INSERT failed: $query<br />" .
           mysql_error() . "<br /><br />";
         echo $found_error;
        }
        else { 
          echo "Success!<br />";
          //TODO: replace with soft link to createNewRecord.php
          echo "<form action=\"createNewRecord.php\"><input type=\"submit\" value=\"Enter another record\"></form>";
          //TODO: replace with soft link to sermons.php
          echo "<form action=\"sermons.php\"><input type=\"submit\" value=\"Go to sermons page\"></form>";
        }
        exit; 
    }    
    return $found_error;
}
?>