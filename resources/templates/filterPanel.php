<?php

echo <<<_FILTER_INTRO
<div id="filterBox">
   <script defer type="text/javascript" src="filter.js"></script>
   <form id="selform" action="">
   <p class="header3">Filter your results</p>
_FILTER_INTRO;

/* FILTER BY DB FIELDS */

foreach ($mysql_queries as $field) {
    echo "<select id=" . $field . "select onChange=\"select(this.id)\">";
    switch ($field) {
        case 'bible_book':
            $j = 0;
            foreach ($bible_books as $item) {
                if ($j==0) {
                    echo "<option value=\"\">-- Any Bible book--</option>";
                    echo "<optgroup label=\"Old Testament\">";
                }
                else {
                    // if book is not found in bible_ref_list, disable option
                    if (in_array($item, $bible_book_list)) {
                        echo "<option value=\"$item\">$item</option>";
                    }
                    else {
                        echo "<option value=\"$item\" disabled>$item</option>";
                    }
                    if ($j==39) {
                        echo "</optgroup>";
                        echo "<optgroup label=\"New Testament\">";
                    }
                }
                ++$j;
            echo "</optgroup>";    
            }
            break;

        default:
            echo "<option value=\"\">-- Any " . $field . " --</option>";
            /* Puts each item in e.g. "preacher_list" into $opt */
            foreach (${$field . "_list"} as $opt) {
                if ($field == "year" && $opt == 0) {
/*                  echo "<option value=\"${$field . "_opt"}\">Unknown</option>"; */
                  echo "<option value=\"" . ${$field . "_opt" } . "\">Unknown</option>";

                }
                else {
/*                     echo "<option value=\"${$field . "_opt"}\">${$field . "_opt"}</option>"; */
                     echo "<option value=\"" . ${$field . "_opt"} . "\">" . $opt . "</option>";
/*                     echo "<option value=\"" . $opt . "\">" . $opt . "</option>"; */
                }
                break;
            }
    }

    echo "</select>";
}

echo "</form></div>";

?>

