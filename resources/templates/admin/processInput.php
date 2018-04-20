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
require_once(TEMPLATES_PATH . "/admin/getFieldValues.php");
require_once(TEMPLATES_PATH . "/admin/getFormattedDate.php");
require_once(TEMPLATES_PATH . "/admin/getFormattedBibleRef.php");
require_once(TEMPLATES_PATH . "/admin/phpFunctions.php");
require_once(TEMPLATES_PATH . "/admin/insertRecord.php");
require_once(TEMPLATES_PATH . "/admin/phpValidation.php");
require_once(LIBRARY_PATH . "/readDbRecords.php");
require_once(LIBRARY_PATH . "/userAuthentication.php");


$db_connection = openConnection('authorised');

// TODO: replace this with variables from mysql (see createNewRecord)
// Check as "bible_ch_start", "bible_verse_start", "bible_ch_end"
// and "bible_verse_end" will come as "bible_ref"
// "day", "month", "year" will come as "date"

$records_to_fetch = array('column_names');
$records = readDbRecords($records_to_fetch);
$column_names_from_db = $records['column_names'];

$additional_fields_for_js = array('day', 'month', 'year',
    'bible_ch_start', 'bible_verse_start', 'bible_ch_end', 'bible_verse_end');

$field_names = array_merge($column_names_from_db, $additional_fields_for_js);
//
//FB::log("Field names: ");
//FB::log($field_names);
// Old version
//$field_names = array("day", "month", "year", "time", "sermon_title", "series",
//    "preacher", "bible_book", "bible_ch_start", "bible_verse_start",
//    "bible_ch_end", "bible_verse_end", "file_name");

if ($_POST['submitted'] == "yes") {
    $found_error = ERROR_MESSAGE;
//    FB::log('Processing insertRecord()');
// Get field values and check not blank
    //$field_values = array(getFieldValues($field_names));
    $field_values = getFieldValues($field_names);
//    FB::log("Field values: ");
//    FB::log($field_values);
//    
    $found_error .= checkFieldValuesAreNotBlank($field_values);
// TODO: replace with going back to admin screen with message "Please
// check fields"

    if (!$_FILES) {
        $found_error .= "No file was posted. Terminating process...";
        FB::error($found_error);
        //return $found_error;
        //exit;
    }
//    FB::log("Getting formatted date and bible ref...");
// Get formatted date and add to $field_values array
    $field_values['date'] = getFormattedDate($field_values);
//    FB::log("New date: " . $field_values['date']);
// Get formatted Bible ref and add to $field_values array
    $field_values['bible_ref'] = getFormattedBibleRef($field_values);

//// Get clean file name
    $new_file_name = getFileName($field_values);

    // $new_file_name will start with "ERROR" if something went wrong
    if (0 === strcmp(
                    substr($new_file_name, 0, strlen(ERROR_MESSAGE)), ERROR_MESSAGE)) {
        $found_error .= $new_file_name;
    } else {
        $field_values['file_name'] = $new_file_name;
    }

    // LER TODO IMPROVE!!!!
    if (strlen($found_error) > strlen(ERROR_MESSAGE)) {
        echo "Found errors: " . $found_error;
    } else {

// Insert record into MySQL DB
        // TODO: currently inserts one record at a time - lots of DB connections
        // if multiple records being inserted
        $success = insertRecord($field_values);


        // $success will start with "ERROR" if something went wrong
        if (0 === strcmp(
                        substr($success, 0, strlen(ERROR_MESSAGE)), ERROR_MESSAGE)) {
            echo "Found error: " . $success;
        }
        require_once(SERMONS_PATH . "/sermons.php");
        
//// If fails, reloads the HTML form with error
//// TODO: successful outcome??
////        else { 
//    echo "Success!<br />";
////TODO: replace with soft link to createNewRecord.php
//    echo "<form action=\"createNewRecord.php\"><input type=\"submit\" value=\"Enter another record\"></form>";
////TODO: replace with soft link to sermons.php
//    echo "<form action=\"sermons.php\"><input type=\"submit\" value=\"Go to sermons page\"></form>";
////        }
        
        
    }
    //exit;
}



if (ob_get_level() > 1) {
    ob_end_flush();
}
?>