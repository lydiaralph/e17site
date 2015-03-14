<?php

/* 
 * Formats Bible ref as "Chapter:Verse - Chapter:Verse"
 * 
 * // TODO: Warn if date is not a Sunday
 */

function getFormattedBibleRef($field_values){
    $bible_ref =    $field_values['bible_ch_start'] . ":" . 
                    $field_values['bible_verse_start'] . " - " . 
                    $field_values['bible_ch_end'] . ":" . 
                    $field_values['bible_verse_end'];
    
    return $bible_ref;
}
