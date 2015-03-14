<?php

/* 
 * Formats date as YYYY-MM-DD
 */

function getFormattedDate($field_values){
    $date = "";
    
    // checkdate() is an inbuilt function
    if (checkdate($field_values['month'], $field_values['day'], $field_values['year'])) {
        $date = $field_values['year'] . "-" . $field_values['month'] . "-" . $field_values['day'];
    } else {
        FB::error('Invalid date');
    }
    return $date;
}