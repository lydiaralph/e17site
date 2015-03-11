<?php
function checkInput($field_names) {
    $firephp->log('Processing checkInput()...');
    $found_error = "";
    foreach ($field_names as $field) {
        $firephp->log('Checking ' . $field . ' is not blank...');
        $found_error .= isNotBlank($value);
    }
    return $found_error;
}


function validateBibleRef($bible_ref, $bible_ch_start, $bible_ch_end, 
        $bible_verse_start, $bible_verse_end) {
    if ($bible_ch_end < $bible_ch_start)
      {
          $bible_ch_end = $bible_ch_start;
          // TODO log that bible ref has been altered
      }
      else if ($bible_ch_end == $bible_ch_start)
      {
          if ($bible_verse_end < $bible_verse_start)
              $bible_verse_end = $bible_verse_start;
          // TODO log that bible ref has been altered
      }

      return "$bible_ch_start:$bible_verse_start - $bible_ch_end:$bible_verse_end";
}

function validateUploadedFile ($_FILES) {

}