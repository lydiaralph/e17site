<?php 
/**
 * Stand-alone program to delete records. 
 * To be integrated later
 */


define('__RESOURCES__', dirname(dirname(__FILE__))); 
require_once(__RESOURCES__.'/config.php'); 

// SQL queries
require_once(LIBRARY_PATH . "/readDbRecords.php");

// SQL queries
require_once(LIBRARY_PATH . "/user_authentication.php");

// Page contents     
require_once(TEMPLATES_PATH . "/pageHeader.php");

$query = "DELETE FROM " . DB_TABLE . "WHERE sermon_title='' " ;

if(!mysql_query($query, $connected_to_db)) {
    echo "DELETE failed: $query<br />" .
    mysql_error() . "<br /><br />";
}
else {
    echo "Success!<br />";
    }
exit;


require_once(TEMPLATES_PATH . "/pageFooter.php");

?>