<?php

/*
 * Handles all access to DB
 * 
 * Queries:
 * - getFullDatabase: used for displaying all records
 * - getDistinctListValues: used for filtering by unique values
 * - getFieldNames: used for inserting new records
 * 
 * @author: lydia.e.ralph@gmail.com
 */

require_once(__RESOURCES__ . '/config.php');

class databaseQueries {

    // TODO: write get & set methods
    public $query;

    public function queryDatabase() {
        if (connectToMySqlDb()) {
            
        }
        if($connected_to_db)
{
    mysql_close($connected_to_db);
}
    }

    private function getFullDatabase() {
        $query = "SELECT * FROM sermon_files";
        $full_database = mysql_query($query);

        if (!$full_database) {
            die("Database access failed: " . mysql_error());
        }
    }

    /*
     * Interrogates the MySQL database for distinct values for the 
     * specified fields, and then places these values in an array.
     */

    private function getDistinctListValues() {
        $mysql_queries = array('preacher' => "preacher",
            'series' => "series",
            'bible_book' => "bible_book",
            'date' => "date",
            'time' => "time");

// Lists of distinct values - used for filtering
        foreach ($mysql_queries as $field) {
            if ($field == 'date') {
                $query = "SELECT DISTINCT (YEAR(" . $field .
                        ")) AS 'field_value' FROM `sermon_files`";
            } else {
                $query = "SELECT DISTINCT (" . $field .
                        ") AS 'field_value' FROM `sermon_files`";
            }
            $result = mysql_query($query);

            if (!$result)
                die("Database access failed: " . mysql_error());

            while ($row = mysql_fetch_assoc($result)) {
                ${$field . '_list'}[] = $row['field_value'];
            }

// Also store as JS array
            ${'js_' . $field . '_list'} = json_encode(${$field . '_list'});
        }
        return;
    }

    // Get database field names
    private function getFieldNames() {
        /* $query_columns = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`"
          . "WHERE `TABLE_SCHEMA`='yourdatabasename' "
          . "AND `TABLE_NAME`='yourtablename';";
         */
        $query_columns = "SHOW COLUMNS FROM sermon_files";

//$query_columns = "SELECT * FROM sermon_files";

        $columns_result = mysqli_query($connected_to_db, $query_columns);
        if ($columns_result) {
            while ($full_db_fields = mysqli_fetch_array($columns_result)) {

                //echo "DB Field name: " . $full_db_fields['Field']."<br>";
            }

            if (!$full_db_fields) {
                die("Database access failed: " . mysql_error());
            }
        }
        mysqli_free_result($columns_result);
    }

}
