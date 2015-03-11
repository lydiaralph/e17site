<?php

/*
 * Handles connection to database
 * 
 * if($connected_to_db), a user has logged in already. Otherwise,
 * read-only access is granted.
 */

require_once(__RESOURCES__ . '/config.php');
require_once(__RESOURCES__ . '/login.php');

class connectToMySqlDb{
public function connectToMySqlDb() {
    log.info("Requesting user authentication...");
    requestUserAuthentication();
    log.info("Attempting connection to db...");
    
    // Already connected
    if($connected_to_db){
        log.info("Already connected to db");
        return;
    }
    
// Try connecting authenticated user
    // LER TODO: maybe use try catch?
    $connected_to_db = mysql_connect($db_hostname, $db_username, $db_password);

// Authentication failed: try connecting read-only
// This is so that sermon records can be displayed without authentication
    if (!$connected_to_db){
        $connected_to_db = mysql_connect($db_hostname, $db_reader_user, $db_reader_password);
// Read-only connection also failed
        if (!$connected_to_db) {
            die("Unable to connect to database: " . mysql_error());
        }
        else{
            log.info("Connected to database with read-only access");
        }
    }
// Check connected to db: select table
    if ($connected_to_db) {
        mysql_select_db($connected_to_db)
                or die("Unable to select database: " . mysql_error());
    }
    return;
}
}