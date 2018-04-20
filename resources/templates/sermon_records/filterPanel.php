<?php

// Doesn't need to be included as referenced by <script> header
//include_once(__LIBRARY__.'/filter.js'); 

echo "<div id=\"filterBox\">";
echo "<script defer type=\"text/javascript\" src=\"/public_html/js/filter.js\"></script>";
echo "<form id=\"selform\" action=\"\">";
echo "<p class=\"header3\">Filter your results</p>";

$records_to_fetch = array('get_lists', 'column_names');

$records = readDbRecords($records_to_fetch);
$field_names = $records['column_names'];
$required_lists = $records['get_lists'];

/* FILTER BY DB FIELDS */

foreach ($required_lists as $field) {
    echo "<select id=" . $field . " onChange=\"select(this.id)\">";
    switch ($field) {
        case 'bible_book':
            $j = 0;
            foreach ($bible_books as $item) {
                if ($j == 0) {
                    echo "<option value=\"\">-- Any Bible book--</option>";
                    echo "<optgroup label=\"Old Testament\">";
                } else {
                    // if book is not found in bible_ref_list, disable option
                    if (in_array($item, $bible_book_list)) {
                        echo "<option value=\"$item\">$item</option>";
                    } else {
                        echo "<option value=\"$item\" disabled>$item</option>";
                    }
                    if ($j == 39) {
                        echo "</optgroup>";
                        echo "<optgroup label=\"New Testament\">";
                    }
                }
                ++$j;
                echo "</optgroup>";
            }
            echo "</select>";
            break;

        default:
            echo "<option value=\"\">-- Any " . $field . " --</option>";
            /* Puts each item in e.g. "preacher_list" into $opt */

//            foreach (${$field . "_list"} as $opt) {
//                echo "<li>Option: " . $opt . "</li>";
//            }

            foreach (${$field . "_list"} as $opt) {

                if ($field == "year" && $opt == 0) {
                    echo "<option value=\"" .  $opt . "\">Unknown</option>";
                } else {
                    echo "<option value=\"" . $opt . "\">" . $opt . "</option>";
                }
            }

            echo "</select>";
    }
}
echo "<button id=\"clearFilters\" onClick=\"removeFilters()\">Remove filters</button>";
echo "</form></div>";

?>

