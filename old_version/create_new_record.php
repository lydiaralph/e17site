<?php // create_new_record.php
require_once 'login.php'; 
require_once 'php_functions.php';
require_once 'php_validation.php';
require_once 'user_authentication.php';
require_once 'bible_books.php';
require_once 'mysql_lists.php';
require_once 'date_list.php';
require_once 'FirePHPCore/fb.php';

//ob_start();

// Initialization

$field_names = array("day", "month", "year", "time", "sermon_title", "series", "preacher", 
    "bible_book", "bible_ch_start", "bible_verse_start",  "bible_ch_end", "bible_verse_end", "file_name");


$found_error = $date = $ext = $folder = $new_file_name = $n = "";


if ($_POST['submitted'] == "yes") {
    foreach ($field_names as $field) {
        switch ($field) {
            
            case 'time':
                switch ($_POST[$field]) {            
                      case 2: ${$field} = "PM"; break;
                      case 3: ${$field} = "Other"; break;
                      case 1: 
                      default: ${$field} = "AM"; break;
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
                }
                else if (isset($_POST['add_' . $field])) {
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
    } // end foreach $field

    if( checkdate($month, $day, $year))
      $date = $year . "-" . $month . "-" . $day;
    else
      $found_error .= "Invalid date entered<br />";
 
 

    // TODO: Warn if date is not a Sunday

    // Check file  

    if (!$_FILES) {
        $found_error .= "No file was posted<br />";
    }
    else {
        $file_name = $_FILES['file_name']['name'];


        if (!$file_name) {
            $found_error .= "No file uploaded. Please upload an mp3 file or a PDF file.<br />";
        }
        else {
            $new_file_name = $date . '_' . $time . '_' . str_replace(" ",'_',$sermon_title);

            $file_type = ($_FILES['file_name']['type']);

            switch($_FILES['file_name']['type']) {
                case 'audio/mp3':
                case 'audio/mpeg':
                case 'audio/mpeg1':
                case 'audio/mpeg2':
                case 'audio/mpeg3':
                case 'audio/mpeg4':
                    $ext = '.mp3';
                    $folder='mp3';
                    break;

                case 'application/pdf':
                    $ext = '.pdf';
                    $folder='pdf_notes';
                    break;

                default:
                    $ext = '';
                    break;
            }

            if (!$ext) {
                $found_error .= "'$file_name' is not of an accepted file type. Please upload mp3 or PDF files only.";
            }

          //  $length = wavDur($_FILES['filename']['tmp_name']);
            $n = $new_file_name . $ext;
            $file_path = $folder . "/" . $n;
            $success = move_uploaded_file($_FILES['file_name']['tmp_name'], $file_path);

            // upload file

            if (!$success) { 
                $found_error .= "Upload failed";

            }
        }
    }

//    alert ("Uploaded file '$file_name' as '$n': <br />");


    $bible_ref = $bible_ch_start . ":" . $bible_verse_start . " - " . $bible_ch_end . ":" . $bible_verse_end;

    // If fails, reloads the HTML form with error
    if ($found_error == "") {
        $query = "INSERT INTO sermon_files VALUES" .
        "(NULL, '$date', '$time', '$sermon_title', '$series', '$bible_book', '$bible_ref', '$n','$preacher')";

        if(!mysql_query($query, $connected_to_db)) {
         echo "INSERT failed: $query<br />" .
           mysql_error() . "<br /><br />";
        }
        else { 
          echo "Success!<br />";
          echo "<form action=\"create_new_record.php\"><input type=\"submit\" value=\"Enter another record\"></form>";
          echo "<form action=\"sermons.php\"><input type=\"submit\" value=\"Go to sermons page\"></form>";
        }
        exit; 
    }    
}


//HTML Form

echo <<<_END_FORM_START
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head><title>Upload Files</title>
<link rel="stylesheet" type="text/css" href="wc_sermons.css"/>

<script src="validate_new_record.js" type="text/javascript"></script></head>
    
   <body>
<form action="create_new_record.php" 
        method="post" onsubmit="return validate(this)" enctype="multipart/form-data">
       <div id="mainContent create_record_table">
           <div class="row"><h1>Upload New Record</h1></div>
               
_END_FORM_START;
   
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
        <span class="cell"><input id="submitButton" class="button" type="submit" value="ADD RECORD"  />
    <input type="hidden" name="submitted" value="yes" /></span>
    <span class="cell"><a href="sermons.php">Cancel</a></span>
    </div>
</div>
    </form>
_END_FORM;

    echo "</body></html>";
?>
