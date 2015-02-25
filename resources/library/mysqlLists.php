<?php
/* 
 * Interrogates the MySQL database for distinct values for the 
 * specified fields, and then places these values in an array.
 * 
 * if($connected_to_db), a user has logged in already. Otherwise,
 * read-only access is granted.
 */

// Config file contains login details and DB field names
require_once(__RESOURCES__.'/config.php'); 

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

// Lists of distinct values - used for filtering
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



// Get the full database - used for displaying records
$query = "SELECT * FROM sermon_files";
$full_database = mysql_query ($query);

if (!$full_database) {die ("Database access failed: " . mysql_error());}




// Get database column names
/*$query_columns = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`"
        . "WHERE `TABLE_SCHEMA`='yourdatabasename' "
        . "AND `TABLE_NAME`='yourtablename';";
*/
$query_columns = "SHOW COLUMNS FROM sermon_files";

//$query_columns = "SELECT * FROM sermon_files";

$columns_result = mysqli_query($db_read_only, $query_columns);
if($columns_result){
    while($full_db_fields = mysqli_fetch_array($columns_result)){
        
        //echo "DB Field name: " . $full_db_fields['Field']."<br>";
    }
    
    if (!$full_db_fields){die ("Database access failed: " . mysql_error());}
}
mysqli_free_result($columns_result);






if($db_read_only)
{
    mysql_close($db_read_only);
}

?>

