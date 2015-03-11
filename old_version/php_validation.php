<?php
function is_not_blank($value) {
    if ($value == "") {
        return ($value . " must not be blank.\n");
    }
    if ($value == null) {
        return ($value . " must not be blank.\n");
    }
    return "";
}



function validate_bible_ref($bible_ref, $bible_ch_start, $bible_ch_end, 
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

function validate_uploaded_file ($_FILES) {

}