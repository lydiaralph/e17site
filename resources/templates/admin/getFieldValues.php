<?php

require_once (__ROOT__ . '/FirePHPCore/fb.php');
ob_start();

/*
 * Convert the $_POST array values into object values
 */

function getFieldValues($field_names) {
    FB::log('Getting field values...');
    $field_values = array_fill_keys($field_names, '');
    
    foreach ($field_names as $field) {
        switch ($field) {

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
                if (isset($_POST['select_' . $field_name])) {
                    $field_values[$field] = sanitizeString($_POST['select_' . $field_name]);
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
    return $field_values;
}