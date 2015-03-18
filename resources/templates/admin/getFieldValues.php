<?php

require_once (__ROOT__ . '/FirePHPCore/fb.php');


ob_start();

require_once(LIBRARY_PATH . "/userAuthentication.php");



/*
 * Convert the $_POST array values into object values
 */

function getFieldValues($field_names) {
    
    
$db_connection = openConnection('read_only');
//    FB::log('Getting field values for...');
    $field_values = array_fill_keys($field_names, '');
    
    foreach ($field_names as $field) {
//        FB::log($field);
        switch ($field) {
        // Do not check or use ID or any field with no name
        // Date should come in 'day', 'month' and 'year fields
        // bible_ref should come as 'bible_ch_start', 'bible_verse_start', 'bible_ch_end', 'bible_verse_end'

            case '':
            case 'id':
            case 'date':
            case 'bible_ref':
                break;

            case 'time':
                switch ($_POST[$field]) {
                    case 2: $field_values[$field] = "PM";
                        break;
                    case 3: $field_values[$field]= "Other";
                        break;
                    case 1:
                    default: $field_values[$field]= "AM";
                        break;
                }
                break;
            
            case 'day':
            case 'year':
                $field_values[$field] = mysql_real_escape_string($_POST[$field]);
                break;

            case 'month':
                $field_values[$field] = date("m", strtotime(mysql_real_escape_string($_POST[$field])));
                break;

            case 'preacher':
            case 'series':
                if (isset($_POST['select_' . $field])) {
                    $field_values[$field] = sanitizeString($_POST['select_' . $field]);
                } else if (isset($_POST['add_' . $field])) {
                    $field_values[$field] = sanitizeString($_POST['add_' . $field]);
                }
                break;

            // handle file separately
            case 'file_name':
                break;

            default:
                $field_values[$field] = sanitizeString($_POST[$field]);
                break;
        } // end switch ($field)
    }

    if ($db_connection) {
        closeConnection($db_connection);
    }
    return $field_values;
}
