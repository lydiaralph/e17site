<?php
require_once(__RESOURCES__ . "/config.php");
require_once(__RESOURCES__ . "/login.php");
require_once(TEMPLATES_PATH . "/admin/phpFunctions.php");

// Not required? Or how to flush it?
//require_once (__ROOT__ . '/FirePHPCore/fb.php');
//ob_start();


/**
 * Establishes connection to MySQL DB. 
 * Type of connection is requested by other processes which require DB connection.
 * Returns $db_connection as either 'read_only' and 'authorised'. 
 * Options could be extended later if adding different permissions
 * 
 * Does not perform mysql_select_db(DB_DATABASE) as this should be done
 * as part of process.
 * 
 * @param $connection_requested
 * * 'read_only' is used to display records
 * * 'authorised' is used to insert or change records
 * 
 * @return $db_connection
 */

function openConnection($connection_requested) {
    $db_connection = "";
    switch ($connection_requested) {
        case 'read_only':
            $db_connection = getReadOnlyDbConnection();
            break;
        case 'authorised':
            $db_connection = getAuthorisedDbConnection();
            break;
        default:
            FB::warn("Unrecognised connection type. Unable to establish DB connection.");
            break;
    }
    return $db_connection;
}

function closeConnection($db_connection){
    if($db_connection){
        mysql_close($db_connection);   
    }
    else{
        FB::warn("Could not find DB connection to close");
    }
}

// TODO make this private function? as must be accessed from wrapper class
// to ensure that connection is later closed
/** 
 * Attempts to open read-only connection to DB
 * Should be accessed via openConnection()
 * @param DB_HOSTNAME to be passed in from login.php
 * @return $db_read_only database connection
 */
function getReadOnlyDbConnection() {
    $db_read_only = mysql_connect(DB_HOSTNAME, DB_READER_USER, DB_READER_PASSWORD);
    if (!$db_read_only) {
        FB::warn("Unable to establish read-only connection to MySQL DB: "
                . mysql_error());
    }
    return $db_read_only;
}

// TODO make this private function? as must be accessed from wrapper class
// to ensure that connection is later closed

// TODO: ERROR: needs to die and regenerate headers so user can enter password
/** 
 * Prompts user for name and password, then attempts connection
 * Should be accessed via openConnection()
 * @param DB_HOSTNAME to be passed in from login.php
 * @return $connected_to_db database connection
 */
function getAuthorisedDbConnection() {
    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

    if (strlen($_SERVER['PHP_AUTH_USER']) == 0 ||
            strlen($_SERVER['PHP_AUTH_PW']) == 0) {
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
    }

    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Restricted Section"');
        header('HTTP/1.0 401 Unauthorized');
        die("Please enter your name and password");
        exit;
    } else {// Connect to server
        $db_username = sanitizeString($_SERVER['PHP_AUTH_USER']);
        $db_password = sanitizeString($_SERVER['PHP_AUTH_PW']);

        $connected_to_db = mysql_connect(DB_HOSTNAME, $db_username, $db_password);

        if (!$connected_to_db) {
            FB::warn('Could not establish authorised connection to MySQL DB: '
                    . mysql_error());
        }
    }
    return $connected_to_db;
}
?>

