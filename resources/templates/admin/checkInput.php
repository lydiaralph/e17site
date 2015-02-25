<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function checkInput($field_names) {
    foreach ($field_names as $field) {
        switch ($field) {

            case 'time':
                switch ($_POST[$field]) {
                    case 2: ${$field} = "PM";
                        break;
                    case 3: ${$field} = "Other";
                        break;
                    case 1:
                    default: ${$field} = "AM";
                        break;
                }
                break;

            case 'day':
            case 'year':
                ${$field} = mysql_real_escape_string($_POST[$field]);
                break;

            case 'month':
                ${$field} = date("m", strtotime(mysql_real_escape_string($_POST[$field])));
                break;

            case 'preacher':
            case 'series':
                if (isset($_POST['select_' . $field_name])) {
                    ${$field} = sanitizeString($_POST['select_' . $field_name]);
                } else if (isset($_POST['add_' . $field])) {
                    ${$field} = sanitizeString($_POST['add_' . $field]);
                }
                break;

            // handle file separately
            case 'file_name':
                break;

            default:
                ${$field} = sanitizeString($_POST[$field]);
                break;
        } // end switch ($field)

        if (${$field} == "") {
            $found_error .= $field . " must not be blank.<br />";
        }
    }    
    return $found_error;
}