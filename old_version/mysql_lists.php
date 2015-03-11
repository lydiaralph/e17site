<?php
/* 
 * Interrogates the MySQL database for distinct values for the 
 * specified fields, and then places these values in an array.
 * 
 * if($connected_to_db), a user has logged in already. Otherwise,
 * read-only access is granted.
 */

require_once 'login.php';

$mysql_queries = array ('preacher' => "preacher",
                        'series' => "series",
                        'bible_book' => "bible_book",
                        'date' => "date",
                        'time' => "time");

if(!$connected_to_db)
{
    $db_read_only = mysql_connect($db_hostname, $db_reader_user, $db_reader_password);
    if (!$db_read_only) die("Unable to connect to MySQL for mysql_lists: " . mysql_error());
}

mysql_select_db($db_database)
  or die("Unable to select database: " . mysql_error());

// Lists of distinct values
foreach ($mysql_queries as $field){
    if ($field == 'date')
    {
        $query = "SELECT DISTINCT (YEAR(" . $field . 
                ")) AS 'field_value' FROM `sermon_files`";
    }
    else
    {
        $query = "SELECT DISTINCT (" . $field . 
                ") AS 'field_value' FROM `sermon_files`";
    }
    $result = mysql_query ($query);

    if (!$result) die ("Database access failed: " . mysql_error());

    while ($row = mysql_fetch_assoc($result)) {
      ${$field . '_list'}[] = $row['field_value'];
    }
    
    // Also store as JS array
    ${'js_' . $field . '_list'} = json_encode(${$field . '_list'});
    
}

// Full database


$query = "SELECT * FROM sermon_files";
$full_database = mysql_query ($query);

if (!$full_database) die ("Database access failed: " . mysql_error());




if($db_read_only)
{
    mysql_close($db_read_only);
}

?>

