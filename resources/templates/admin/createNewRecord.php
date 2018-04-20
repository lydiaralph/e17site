<?php // createNewRecord.php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

/** 
 * Generates a form for user to input data for new record. 
 * Once form is submitted, it is processed by insertRecord.php
 * TODO: move to public_html folder 
 */

/* REQUIRED LIBRARIES */
// FirePHP is not dependent on anything else, i.e. doesn't require config.php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__))))); 
require_once (__ROOT__ . '/FirePHPCore/fb.php');
ob_start();
$firephp = FirePHP::getInstance(true);

// Resources directory
defined('__RESOURCES__')
    or define('__RESOURCES__', dirname(dirname(dirname(__FILE__)))); 

// Config file
require_once(__RESOURCES__. "/config.php"); 

// userAuthentication needed to check user is authorised to view this page
require_once(LIBRARY_PATH . "/userAuthentication.php");

// readDbRecords needed to get column names and unique lists
require_once(LIBRARY_PATH . "/readDbRecords.php");

if(!openConnection('authorised')){
    FB::error("Authorisation failed");
    //alert("Authorisation failed");
    echo '<script type="text/javascript">alert ("Authorisation failed");</script>';
    require_once(SERMONS_PATH . "/sermons.php");
}

$records_to_fetch = array('get_lists', 'column_names');

$records = readDbRecords($records_to_fetch);
$field_names = $records['column_names'];
$lists = $records['get_lists'];

//FB::log("field_names['get_lists']");
//FB::log($field_names['get_lists']);

//FB::log("field_names['get_lists']['preacher']");
//FB::log($field_names['get_lists']['preacher']);


//FB::log("field_names[0]: " . $field_names[0]);




// LER TODO: not sure this is necessary
//include_once(TEMPLATES_PATH . "/admin/processInput.php");

/******************************************************************************
 * PAGE CONTENTS                                                              *
 *****************************************************************************/

//HTML Form
require_once(TEMPLATES_PATH . "/pageHeader.php");

echo <<<_END_FORM_START
<script defer type="text/javascript" src="/public_html/js/validation.js"></script>
<form action="processInput.php" 
        method="post" onsubmit="return validate(this)" enctype="multipart/form-data">
       <div id="create_record_table">
           <div class="row"><h1>Upload New Record</h1></div>
               
_END_FORM_START;
   
 //if ($found_error != "") {
//     
//if($field_names == null ||
//        strcmp(substr($field_names[0],0,strlen(ERROR_MESSAGE)),ERROR_MESSAGE)){
//     echo "<div class=\"row\"><span class=\"cell\" id=\"error_message\" colspan=\"2\">"
//        . "Sorry, the following errors were found in your form: "
//        . "<p><font><i>" . $field_names[0] . "</i></font></p>"
//        . "</span></div>";
// }

    foreach ($field_names as $field) {
        //if(strcmp(substr($field_names[$field][0],0,strlen(ERROR_MESSAGE)),ERROR_MESSAGE)){
        //TODO later
//        if(strcmp(substr($field[0],0,strlen(ERROR_MESSAGE)),ERROR_MESSAGE)){
//            echo "<div class=\"row\"><span class=\"cell\" id=\"error_message\" colspan=\"2\">"
//            . "Sorry, the following errors were found in your form: "
//            . "<p><font><i>" . $field[0] . "</i></font></p>"
//            . "</span></div>";
//         }

    switch ($field) {
        
        case '':        
        case 'id':
            break;

        // Split 'date' into 'day', 'month' and 'year'
        case 'date': 
            // TODO: replace this with date selector
            // TODO: add selection for 'Unknown' date
            // Day
            echo "<div class=\"row\"><span class=\"cell label\">Date</span>";
            echo "<span class=\"cell\" id=\"date\"><select id=\"day\"  name=\"day\">";
            for ($count = 1; $count <=31; $count++) {
              echo "<option value=\"$count\">$count</option>";
            }
            echo "</select>";
            //Month
            echo "<select id=\"month\" name=\"month\">";
             $month_number = 0;
            foreach ($month_list as $month_opt) {
                  echo "<option value=\"$month_number\">$month_opt</option>";
                  ++$month_number;
            }
           echo "</select>"; 
           // Year
           echo "<select id=\"year\" name=\"year\">";
           // TODO: Replace with (today's year - 5) - today's year
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

        // Split 'bible_ref' into 'bible_ch_start', 'bible_verse_start', 'bible_ch_end', 'bible_verse_end'
        case 'bible_ref':
            // Chapter start
            echo "<div class=\"row\"><span class=\"cell label\">From:</span>";
            echo "<span class=\"cell chapter\">Chapter</span>";
            echo "<span class=\"cell\" id=\"bible_ch_start\"><input type=\"text\" name=\"bible_ch_start\"/></span>";
            // Verse start
            echo "<span class=\"cell verse\">Verse</span>";
            echo "<span class=\"cell\" id=\"bible_verse_start\"><input type=\"text\" name=\"bible_verse_start\" /></span></div>"; 
            // Chapter end
            // From and To labels
            echo "<div class=\"row\"><span class=\"cell label\">To:</span>";
            echo "<span class=\"cell chapter\">Chapter</span>";
            echo "<span class=\"cell\" id=\"bible_ch_end\"><input type=\"text\" name=\"bible_ch_end\"/></span>";
            // Verse end
            echo "<span class=\"cell verse\">Verse</span>";
            echo "<span class=\"cell\" id=\"bible_verse_end\"><input type=\"text\" name=\"bible_verse_end\" /></span></div>"; 
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

            //TODO investigate
            foreach ($lists[$field] as $field_item) {
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

if (ob_get_level()>1) {
    ob_end_flush();
}
?>


