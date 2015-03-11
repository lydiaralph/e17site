<?php // sermons.php
require_once 'login.php';
require_once 'php_functions.php'; //remove later
require_once 'user_authentication.php'; //remove later
require_once 'bible_books.php';
require_once 'mysql_lists.php';
require_once 'date_list.php';

$filter_array = Array("series", "preacher", "date", "bible_book","time");

echo <<<_END_MAIN_CONTENT
<html>
<head>
<title>Sermons from the Parish of Walthamstow, UK in audio (MP3) format</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="wc_sermons.css">
</head>

<body>
<div id="mainContent">
<div id="sermonsIntro">
<p><h1>Sermons</h1></p>
<p>This archive has sermons preached live at <a href="http://www.walthamstowchurch.org.uk/">Walthamstow Churches</a>.</p>
<p>To listen, please click the sermon title.</p>
<p>You can download files by right clicking a link and selecting "Save link as..."</p>
</div>
<div id="bible_gateway_table">
  <form action="http://www.biblegateway.com/quicksearch/" method="post">
    <div class="row"><h3>Look up a word or passage in the Bible</h3></div>
      <div class="row">
        <span class="cell">
          <input type="text" name="quicksearch" />
          <input type="submit" value="Search BibleGateway.com" />
        </span>
      </div>
  </form>
</div>

_END_MAIN_CONTENT;

/*   
   <form action="http://www.biblegateway.com/quicksearch/" method="post">


   <table border="1" cellspacing="0" cellpadding="2" style="border-color: #600;">
<div class="row"><th style="background-color: #600; color:#fff; text-align: center; padding: 0 10px;">Look up a word or passage in the Bible</th></div>
<div class="row"><span class="cell" style="background-color: #fff; text-align: center; padding: 0 10px;">
<p style="margin-bottom: 0;">
<input type="text" name="quicksearch" /><br />
<input type="submit" value="Search BibleGateway.com" /><br />
</p>
</span></div>
</table>
</form>
</p>
</div>
</div>

_START_BODY;
 * */
 


echo <<<_START_FILTER
<div id="filterBox">
   <script defer type="text/javascript" src="filter.js"></script>
   <form id="selform" action="">
   <p><h3>Filter your results</h3></p>
_START_FILTER;

// FILTER BY SERIES

foreach ($filter_array as $field) {
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
                        echo "<optgroup label=\"New Testament\">";
                    }
                }
                ++$j;
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

echo <<<_END_FILTER
   <p>You can also <a href="index_series.html">view sermons grouped by sermon series</a></p>
   </form>
</div>

_END_FILTER;


echo <<<_END_TABLE_HEADER
<div class="sermonTable">
<div class="sermonHeader row">
  <span class="cell columnDate"><a href="index_date.html"><b>Date</b></a></span>
  <span class="cell columnTime"><a href="index_time.html"><b>Time</b></a></span>
  <span class="cell columnTitle"><a href="index_title.html"><b>Sermon Title</b></a> (Click to listen)</span>
  <span class="cell columnSeries"><a href="index_series.html"><b>Sermon Series</b></a></span>
  <span class="cell columnReference"><a href="index_reference.html"><b>Bible Reference</b></a></span>
  <span class="cell columnPreacher"><a href="index_preacher.html"><b>Preacher</b></a></span>
</div>

_END_TABLE_HEADER;

echo <<<_START_TABLE_BODY
<div id="sermonList">
_START_TABLE_BODY;

$rows = mysql_num_rows($full_database);

for ($j = 0; $j < $rows ; ++$j)
{
  $row = mysql_fetch_row($full_database);
  if (!$row) continue;
  echo '<div class="row">'; // Row header
  echo '<span class="cell columnDate">' . $row[1] . '</span>'; // Date
  echo '<span class="cell columnTime">' . $row[2] . '</span>'; // Time
  echo '<span class="cell columnTitle"><a href=mp3/' . $row[7] . '>' . $row[3] . '</a></span>'; // Title & MP3 File
  echo '<span class="cell columnSeries">' . $row[4] . '</span>'; // Series
  echo '<span class="cell columnReference"><a href="https://www.biblegateway.com/passage/?search=%22' 
           . $row[5] . " " . $row[6] . '%22&&version=NIV">' 
           . $row[5] . " " . $row[6] . '</a></span>'; // Ref
  echo '<span class="cell columnPreacher">' . $row[8] . '</span>'; // Preacher
  // echo '<span class="cell">' . $row[9] . '</span>'; // Length
  echo '</div>';
}

echo "</div></div></body></html>";
?>
