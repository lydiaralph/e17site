<?php

/* 
 * Formats date as YYYY-MM-DD
 */

function getFormattedDate($field_values){
//    $field_values = $field_lists[0];
    
    $date = "";
    
    // checkdate() is an inbuilt function
    FB::log("Date fields: M, D, Y: " 
            . $field_values['month'] . " "
            . $field_values['day'] . " " 
            . $field_values['year']);
    
    
    if (checkdate($field_values['month'], $field_values['day'], $field_values['year'])) {
        $date = $field_values['year'] . "-" . $field_values['month'] . "-" . $field_values['day'];
    } else {
        FB::error('Invalid date');
    }
    
    FB::log("Generated date: " . $date);
    return $date;
}