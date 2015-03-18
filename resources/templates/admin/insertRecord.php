<?php

/*
 * Takes sanitized record produced by processInput.php and tries to insert
 * it into MySQL DB
 */
require_once (__ROOT__ . '/FirePHPCore/fb.php');
require_once(__RESOURCES__ . "/config.php");
require_once(LIBRARY_PATH . "/userAuthentication.php");

//TODO: request authorised connection


function insertRecord($field_values) {
    $found_error = ERROR_MESSAGE;
    
    $query = buildQuery($field_values);

    $db_connection = openConnection('authorised');
    if (!$db_connection) {
        $found_error .= "Not connected to DB. Terminating process...";
        FB::error($found_error);
        return $found_error;
    }
    if (!mysql_select_db(DB_DATABASE)){
        $found_error .= "Could not select DB table. Terminating process...";
        $db_connection = closeConnection($db_connection);
        FB::error($found_error);
        return $found_error;
    }

    // TODO: test mode properties to determine which query to use
    if (strcmp($query,"")!==0 ) {
        if (mysql_query($query, $db_connection)) {
            FB::log("Successfully inserted new record");
        } else {
            $found_error .= "INSERT failed: $query: " .
                    mysql_error();
            FB::error($found_error);
            return $found_error;
            //exit;
        }
    }
    else{
        FB::log("Error building query: " . $query);
    }
    closeConnection($db_connection);
    
    return "";
}

function buildQuery($field_values) {

    // TODO: replace with variables as order has changed

    // Fields have been replaced by formatted versions
    $expected_blank_fields = array('', 'id', 'day','month','year', 
    'bible_ch_start', 'bible_verse_start', 'bible_ch_end', 'bible_verse_end');

    $query = "INSERT INTO " . DB_TABLE . " VALUES(NULL";

    // Iterate through field values, adding VALUES to query
    foreach ($field_values as $key => $value) {
        if (in_array($key, $expected_blank_fields)) { continue; }
        $query .= ", '$value'";
    }


    $query .= ")";

    FB::log('Query to be inserted: ' . $query);
    return $query;
}
