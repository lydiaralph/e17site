<?php // createNewRecord.php

/** This is intended to be a wrapper file bringing together other resources.
    TODO: move to public_html folder */

define('__RESOURCES__', dirname(dirname(dirname(__FILE__)))); 
require_once(__RESOURCES__. "/config.php"); 

// User Authentication must be before mysqlLists
require_once(LIBRARY_PATH . "/userAuthentication.php");
require_once(LIBRARY_PATH . "/mysqlLists.php");


require_once(TEMPLATES_PATH . "/admin/insertRecord.php");



define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__))))); 

require_once (__ROOT__ . '/FirePHPCore/fb.php');

ob_start();

// Initialization


// TODO: replace this with variables from mysql
$field_names = array("day", "month", "year", "time", "sermon_title", "series", 
    "preacher", "bible_book", "bible_ch_start", "bible_verse_start",  
    "bible_ch_end", "bible_verse_end", "file_name");


$found_error = ""; 
$count = 0; 

if ($_POST['submitted'] == "yes") {
    $found_error .= insertRecord($field_names);
}


//HTML Form



require_once(TEMPLATES_PATH . "/pageHeader.php");



echo <<<_END_FORM_START
<script defer type="text/javascript" src="/public_html/js/validation.js"></script>
<form action="createNewRecord.php" 
        method="post" onsubmit="return validate(this)" enctype="multipart/form-data">
       <div id="create_record_table">
           <div class="row"><h1>Upload New Record</h1></div>
               
_END_FORM_START;


 
//$var = array('i'=>10, 'j'=>20);
 
//$firephp->log($var, 'Iterators');

//FB::log('Log message');
//FB::info('Info message');
//FB::warn('Warn message');
//FB::error('Error message');


   
 if ($found_error != "") {
     echo "<div class=\"row\"><span class=\"cell\" id=\"error_message\" colspan=\"2\">"
        . "Sorry, the following errors were found in your form: "
        . "<p><font><i>$found_error</i></font></p>"
        . "</span></div>";
 }

foreach ($field_names as $field) {
    switch ($field) {

        case 'day': 
            echo "<div class=\"row\"><span class=\"cell label\">Date</span>";
            echo "<span class=\"cell\" id=\"date\"><select id=\"" . $field . "\"  name=\"" . $field . "\">";
            for ($count = 1; $count <=31; $count++) {
              echo "<option value=\"$count\">$count</option>";
            }
            echo "</select>";
            break;

        case 'month':              
            echo "<select id=\"" . $field . "\" name=\"" . $field . "\">";
             $month_number = 0;
            foreach ($month_list as $month_opt) {
                  echo "<option value=\"$month_number\">$month_opt</option>";
                  ++$month_number;
            }
            echo "</select>"; 
            break;

        case 'year':
           echo "<select id=\"" . $field . "\" name=\"" . $field . "\">";
           for ($year_count = 2012; $year_count < 2030; $year_count++) {
              echo "<option value=\"$year_count\">$year_count</option>";
           }
           echo "</select></span></div>";
           break;

        case 'time':
            echo "<div class=\"row\"><span class=\"cell label\">Time</span><span class=\"cell\" id=" . $field . ">";
            echo "<label>Morning<input type=\"radio\" name=\"" . $field . "\" value=\"1\" checked=\"checked\"/></label>";
            echo "<label>Evening<input type=\"radio\" name=\"" . $field . "\" value=\"2\" /></label>";
            echo "<label>Other<input type=\"radio\" name=\"" . $field . "\" value=\"3\" /></label>";
            echo "</span></div>";
            break;

        case 'bible_book':
            echo "<div class=\"row\"><span class=\"cell label\">Bible Ref: Book</span><span class=\"cell form_input\"><select id=" . $field . " name=\"" . $field . "\">";

            foreach ($bible_books as $item) {
                echo "<option value=\"$item\">$item</option>";
            }
            echo "</select></span></div>";
            break;

        case 'bible_ch_start':
            echo "<div class=\"row\"><span class=\"cell label\">From:</span>";
            echo "<span class=\"cell chapter\">Chapter</span>";
            echo "<span class=\"cell\" id=" . $field . "><input type=\"text\" name=\"" . $field . "\"/></span>";
            break;

        case 'bible_verse_start':
            echo "<span class=\"cell verse\">Verse</span>";
            echo "<span class=\"cell\" id=" . $field . "><input type=\"text\" name=\"" . $field . "\" /></span></div>"; 
            break;
            
        case 'bible_ch_end':
            // From and To labels
            echo "<div class=\"row\"><span class=\"cell label\">To:</span>";
            echo "<span class=\"cell chapter\">Chapter</span>";
            echo "<span class=\"cell\" id=" . $field . "><input type=\"text\" name=\"" . $field . "\"/></span>";
                break;

        case 'bible_verse_end':
            echo "<span class=\"cell verse\">Verse</span>";
            echo "<span class=\"cell\" id=" . $field . "><input type=\"text\" name=\"" . $field . "\" /></span></div>"; 
            break;

        case 'sermon_title':
            echo "<div class=\"row\"><span class=\"cell label\">Sermon title</span><span class=\"cell\"><input id=" . $field . " class=\"form_input\" type=\"text\" name=\"" . $field . "\" />";
            echo "</span></div>";
            break;

        // No break

        case 'preacher':
        case 'series':
            echo "<div class=\"row\"><span class=\"cell label\" id=\"select_" . $field . "_label\" >";

            switch ($field) {
                case 'series':
                    echo "Sermon Series</span>";
                    break;
                case 'preacher':
                    echo "Preacher</span>";
                    break;
            }

            // Select option
            echo "<span class=\"cell\" id=\"select_" . $field . "\" class=\"form_input\"><select name=\"select_" . $field . "\" />";

            foreach (${$field . "_list"} as $field_item) {
              echo "<option value=\"" . $field_item . "\">$field_item</option>";
            }
            echo "</select></span></div>";

            echo "<div class=\"row\"><span class=\"cell\" id=\"select_" . $field . "_link\">";
            echo "<a href=\"javascript:chooseInput('" . $field . "', 'select', 'add')\">New " . $field . "?</a>";
            echo "</span></div>";

            // Add option
            echo "<div class=\"row\"><span class=\"cell label hidden\" id=\"add_" . $field . "_label\">Please enter new " . $field . "</span>";

            echo "<span class=\"cell hidden\" id=\"add_" . $field . "\" class=\"form_input\"><input type=\"text\" name=\"add_" . $field . "\" />";
            echo "</span></div>";

            echo "<div class=\"row\"><span class=\"cell hidden\" id=\"add_" . $field . "_link\">";
            echo "<a href=\"javascript:chooseInput('" . $field . "', 'add', 'select')\">Select " . $field . " from list</a>";
            echo "</span></div>";

            break;

        case 'file_name':
            echo "<div class=\"row\"><span class=\"cell label\">Select an MP3 file:</span><span class=\"cell\" id=" . $field . "><input type='file' name=\"" . $field . "\" /></span></div>";
            break;

        default:          
            echo "<div class=\"row\"><span class=\"cell\">" . $field . "</span><span class=\"cell\" id=" . $field . "><select name=\"" . $field . "\">";
            echo "</select></div></span>";
            break;
    }
}

echo <<<_END_FORM
    <div class="row">
        <span class="cell">
            <input id="submitButton" class="button" type="submit" value="ADD RECORD"  />
            <input type="hidden" name="submitted" value="yes" />
        </span>
        <span class="cell"><a href="sermons.php">Cancel</a></span>
    </div>
</div>
    </form>
_END_FORM;

require_once(TEMPLATES_PATH . "/pageFooter.php");
?>


