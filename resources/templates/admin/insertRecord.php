<?php

/*
 * Takes sanitized record produced by processInput.php and tries to insert
 * it into MySQL DB
 */

require_once(__RESOURCES__ . "/config.php");
require_once(LIBRARY_PATH . "/userAuthentication.php");

//TODO: request authorised connection


function insertRecord($field_values) {
    $query = buildQuery($field_values);

    $db_connection = openConnection($authorised);
    if (!$db_connection) {
        FB::error("Not connected to DB. Terminating process...");
        exit;
    }
        
    mysql_select_db($db_database);

    // TODO: temp query for testing
    $dry_run_query = "BEGIN TRANSACTION " .
            $query .
            "ROLLBACK TRANSACTION";

    // TODO: test mode properties to determine which query to use
    if ($dry_run_query) {
        if (!mysql_query($dry_run_query, $db_connection)) {
            FB::error("INSERT failed: $dry_run_query: " .
                    mysql_error());
            exit;
        }
    }
    closeConnection($db_connection);
    
    return true;
}

function buildQuery($field_values) {

    // TODO: replace with variables as order has changed

    $query = "INSERT INTO sermon_files VALUES(NULL";

    // Iterate through field values, adding VALUES to query
    foreach ($field_values as $key => $value) {
        switch ($key) {
            // Fields have been replaced by formatted versions
            case 'day':
            case 'month':
            case 'year':
            case 'bible_ch_start':
            case 'bible_verse_start':
            case 'bible_ch_end':
            case 'bible_verse_end':
                break;

            default:
                $query .= ", '$value'";
        }
    }

    $query .= ")";

    FB::log('Query to be inserted: ' . $query);
}
