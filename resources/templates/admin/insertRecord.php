<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function insertRecord($field_names){
    
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

    // If fails, reloads the HTML form with error
    if ($found_error == "") {
        // TODO: replace with variables as order has changed
        $query = "INSERT INTO sermon_files VALUES" .
           "(NULL, '$sermon_title', '$series', '$date', '$time', '$bible_book', '$bible_ref', '$preacher', '$cleanFileName')";

        if(!mysql_query($query, $connected_to_db)) {
         $found_error .= "INSERT failed: $query<br />" .
           mysql_error() . "<br /><br />";
         echo $found_error;
        }
        else { 
          echo "Success!<br />";
          echo "<form action=\"create_new_record.php\"><input type=\"submit\" value=\"Enter another record\"></form>";
          echo "<form action=\"sermons.php\"><input type=\"submit\" value=\"Go to sermons page\"></form>";
        }
        exit; 
    }    
    return $found_error;
}
?>