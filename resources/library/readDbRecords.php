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

function readDbRecords($required_task) {
    $task_results = array_fill_keys($required_task, '');

    $db_connection = openConnection('read_only');
    
    if (!$db_connection) {
        FB::error("No connection to DB");
        exit;
    }

    mysql_select_db($db_database);

    foreach ($required_task as $task) {
        switch ($task) {
            case 'get_lists':
                $task_results[$task] = getLists();
                break;

            case 'get_full_db':
                $task_results[$task] = getFullDb();
                break;

            case 'get_column_names':
                $task_results[$task] = getColumnNames();
                break;
            
            default:
                FB::warn("Unrecognised MySQL task");
                $task_results[$task] = "";
                break;
        }
    }
    closeConnection($db_connection);
    return $task_results;
}

/**
 * Generates lists of distinct values for all fields specified in $requiredLists
 * These are used for filtering records displayed on sermons.php
 *
 * Returns a multi-dimensional associative array ($generated_lists) 
 * of all generated lists
 * 
 * $mysql_queries replaced by $requiredLists as too confusing
 */
function getLists() {
    $required_lists = array(
        'preacher' => "preacher",
        'series' => "series",
        'bible_book' => "bible_book",
        'date' => "date",
        'time' => "time"
    );

    $generated_lists = array_fill_keys($required_lists, '');
    
    foreach ($required_lists as $field) {
        $temp_array = array();
        
        if ($field == 'date') {
            $query = "SELECT DISTINCT (YEAR(" . $field .
                    ")) AS 'field_value' FROM `" . $db_table . "`";
        } else {
            $query = "SELECT DISTINCT (" . $field .
                    ") AS 'field_value' FROM `" . $db_table . "`";
        }
        $result = mysql_query($query);

        if (!$result)
            die("Database access failed: " . mysql_error());

        // Convert result into array
        while ($row = mysql_fetch_assoc($result)) {
            //${$field . '_list'}[] = $row['field_value'];
            $temp_array[] = $row['field_value'];
        }
        
        // Push array onto array to be returned
        // TODO: should this be $generated_lists[$field][] = $temp_array;?
        $generated_lists[$field] = $temp_array;

        // TODO: Also store as JS array?? Can't remember why
        // ${'js_' . $field . '_list'} = json_encode(${$field . '_list'});
    }
    // Return multi-dimensional array of all lists
    return $generated_lists;
}


/**
 * Gets the full database
 * Used for displaying records on sermons.php
 */
function getFullDb() {
    $query = "SELECT * FROM " . $db_table;
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
    /* $query_columns = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`"
      . "WHERE `TABLE_SCHEMA`='yourdatabasename' "
      . "AND `TABLE_NAME`='yourtablename';";
     */
    $query_columns = "SHOW COLUMNS FROM " . $db_table;


//$query_columns = "SELECT * FROM sermon_files";

    $columns_result = mysqli_query($db_read_only, $query_columns);
    if ($columns_result) {
        while ($full_db_fields = mysqli_fetch_array($columns_result)) {

            //echo "DB Field name: " . $full_db_fields['Field']."<br>";
        }

        if (!$full_db_fields) {
            die("Database access failed: " . mysql_error());
        }
    }
    //mysqli_free_result($columns_result);
    return $columns_result;
}
?>

