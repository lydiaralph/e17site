<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

/*
 * Handles form input once JS validation is completed
 */

// FirePHP is not dependent on anything else, i.e. doesn't require config.php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once (__ROOT__ . '/FirePHPCore/fb.php');
ob_start();
$firephp = FirePHP::getInstance(true);

define('__RESOURCES__', dirname(dirname(dirname(__FILE__))));
require_once(__RESOURCES__ . "/config.php");

require_once(TEMPLATES_PATH . "/admin/getFileName.php");
require_once(TEMPLATES_PATH . "/admin/phpFunctions.php");
require_once(TEMPLATES_PATH . "/admin/phpValidation.php");


// TODO: replace this with variables from mysql
// Check as "bible_ch_start", "bible_verse_start", "bible_ch_end"
// and "bible_verse_end" will come as "bible_ref"
// "day", "month", "year" will come as "date"
$field_names = array("day", "month", "year", "time", "sermon_title", "series",
    "preacher", "bible_book", "bible_ch_start", "bible_verse_start",
    "bible_ch_end", "bible_verse_end", "file_name");

if ($_POST['submitted'] == "yes") {
    FB::log('Processing insertRecord()');

// Get field values and check not blank
    $field_values = array(getFieldValues($field_names));
    if (checkFieldValuesAreNotBlank($field_values)) {
        FB::error('Some blank fields. Terminating process...');
// TODO: replace with going back to admin screen with message "Please
// check fields"
        exit;
    } else if (!$_FILES) {
        FB::error("No file was posted. Terminating process...");
        exit;
    }

// Get formatted date and add to $field_values array
    $field_values['date'] = getFormattedDate($field_values);

// Get formatted Bible ref and add to $field_values array
    $field_values['bible_ref'] = getFormattedBibleRef($field_values);

// Get clean file name
    $field_values['file_name'] = getFileName($field_values);

// Insert record into MySQL DB
    // TODO: currently inserts one record at a time - lots of DB connections
    // if multiple records being inserted
    $success = insertRecord($field_values);

//// If fails, reloads the HTML form with error
//// TODO: successful outcome??
////        else { 
//    echo "Success!<br />";
////TODO: replace with soft link to createNewRecord.php
//    echo "<form action=\"createNewRecord.php\"><input type=\"submit\" value=\"Enter another record\"></form>";
////TODO: replace with soft link to sermons.php
//    echo "<form action=\"sermons.php\"><input type=\"submit\" value=\"Go to sermons page\"></form>";
////        }
    

    
    exit;
}

ob_end_flush();
?>