<?php

/*
 * Used to be called mysqlLists.php
 * 
 * Interrogates the MySQL database for distinct values for the 
 * specified fields, and then places these values in an array.
 * 
 * Requires $db_connection['readonly'] from userAuthentication.php
 * 
 * Takes array $required_task as parameter and performs all requested tasks
 * 
 */

// Config file contains login details and DB field names
require_once(__RESOURCES__ . '/config.php');
require_once(__RESOURCES__ . '/login.php');
require_once(LIBRARY_PATH . "/userAuthentication.php");

function readDbRecords($required_tasks) {
    $db_connection_error = "";

    $task_results = array_fill_keys($required_tasks, '');
    
//    FB::log("required_tasks: ");
//    FB::log($required_tasks);
//    
//    FB::log("task_results: ");
//    FB::log($task_results);

    $db_connection = openConnection('read_only');

    if (!$db_connection) {
        $db_connection_error = ERROR_MESSAGE . "Could not open connection to database";
  //      FB::log($db_connection_error);
    } else {
    //    FB::log("Selecting database...");
        if (!mysql_select_db(DB_DATABASE)) {
            $db_connection_error = ERROR_MESSAGE . "Could not select database";
 //           FB::log($db_connection_error);
        }
    }

    foreach ($required_tasks as $task) {
//        FB::log("Processing foreach for " . $task . "...");
        if (strcmp($db_connection_error, "") != null) {
            //$task_results['error'] = $db_connection_error;
        }
        else{
            $task_results[$task] = handleRequiredTask($task);
//            FB::log("Task results for " . $task . ": ");
//            FB::log($task_results[$task]);
        }
    }
    if ($db_connection) {
// This log may break process
//        FB::log("Closing connection...");
        closeConnection($db_connection);
    }

//    FB::log("Results to return: ");
//    FB::log($task_results);
    return $task_results;
}


/**
 * Acts as a router to ensure correct query is processed
 * 
 * Returns an array for each task
 */
function handleRequiredTask($task) {
//    FB::log("Beginning required_task: " . $task);
    $task_results = array();
    switch ($task) {
        case 'get_lists':
//            FB::log("Getting lists...");
            $task_results = getLists();
            break;

        case 'get_full_db':
            $task_results = getFullDb();
            break;

        case 'column_names':
//            FB::log("Getting column names...");
            $task_results = getColumnNames();
            // TODO remove
            //$task_results[] = array("1","2","3");
            break;

        default:
            FB::warn("Unrecognised MySQL task");
            //$task_results[] = ERROR_MESSAGE . "Unrecognised task: no results";
            break;
    }
//    FB::log("Finished required_task: " . $task);
//    FB::log("Results for " . $task . ": ");
//    FB::log($task_results);
    return $task_results;
}

/**
 * Generates lists of distinct values for all fields specified in $requiredLists
 * These are used for filtering records displayed on sermons.php
 *
 * Returns a multi-dimensional associative array ($generated_lists) 
 * of all generated lists. Results come as (e.g.) $generated_lists['preacher']
 * 
 * $mysql_queries replaced by $required_lists as too confusing
 */
function getLists() {
    $keys = array("preacher","series","bible_book","date","time");
    $generated_lists = array_fill_keys($keys, '');
    
    foreach ($keys as $field) {
        $generated_lists[$field] = fetchIndividualList($field);
    }
 
    // Return multi-dimensional array of all lists
//
//    FB::log("Generated lists: ");
//    FB::log($generated_lists);
    return $generated_lists;
}

function fetchIndividualList($field) {
//    FB::log("Fetching " . $field . " from database");
    $temp_array = array();

    if ($field == 'date') {
        $query = "SELECT DISTINCT (YEAR(" . $field .
                ")) AS 'field_value' FROM `" . DB_TABLE . "`";
    } else {
        $query = "SELECT DISTINCT (" . $field .
                ") AS 'field_value' FROM `" . DB_TABLE . "`";
    }
    
//    FB::log("Query for " . $field . " is: " . $query);
//    
    $result = mysql_query($query);

    if (!$result) {
        $temp_array[0] = ERROR_MESSAGE . "Database access failed: " . mysql_error();
        //FB::log("Could not fetch list: " . $temp_array[0]);
    } 
   else {
        // Convert result into array
        // TODO: This log causes an error. Why?
//        FB::log("Successfully found list for " . $field);

        while ($row = mysql_fetch_assoc($result)) {
            $temp_array[] = $row['field_value'];
            // TODO: This log causes an error. Why?
 //           FB::log("Added value: " . $row['field_value']);
        }
    }

//    FB::log("temp array is...");
//    FB::log($temp_array);
    return $temp_array;
}

/**
 * Gets the full database
 * Used for displaying records on sermons.php
 */
function getFullDb() {
    $query = "SELECT * FROM " . DB_TABLE;
    $full_database = mysql_query($query);

    if (!$full_database) {
        die("Database access failed: " . mysql_error());
    }
    return $full_database;
}

/**
 * Gets database column names
 * Future purpose: To be used for dynamic variable names. This will make the
 * application more scalable
 */
function getColumnNames() {

    $query_columns = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`"
            . "WHERE `TABLE_SCHEMA`='" . DB_DATABASE . "' "
            . "AND `TABLE_NAME`='" . DB_TABLE . "';";

    $column_names[] = "";
    $columns_result = mysql_query($query_columns);

    while ($row = mysql_fetch_assoc($columns_result)) {
        //${$field . '_list'}[] = $row['field_value'];
        array_push($column_names, $row['COLUMN_NAME']);
        // TODO: This log causes an error. Why?
        //FB::log("Added value: " . $row['COLUMN_NAME']);
    }
    if (strcmp($column_names[0], "") != null) {
        $column_names[] = ERROR_MESSAGE . "Could not find any columns";
    }
    return $column_names;
}
?>

