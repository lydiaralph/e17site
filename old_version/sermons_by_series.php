<?php // sermons.php
require_once 'login.php';
require_once 'date_list.php';
require_once 'series_list.php';
require_once 'preacher_list.php';
require_once 'bible_books.php';

if(!$connected_to_db)
{
    $db_read_only = mysql_connect ($db_hostname, $db_reader_user, $db_reader_password);
    if (!$db_read_only) die("Unable to connect to MySQL for series_list: " . mysql_error());
}

mysql_select_db($db_database)
  or die("Unable to select database: " . mysql_error());

$query = "SELECT * FROM sermon_files";
$result = mysql_query ($query);

if (!$result) die ("Database access failed: " . mysql_error());

echo <<<_END_HEADER
<html>
<head>
<title>Sermons from the Parish of Walthamstow, UK in audio (MP3) format</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
_END_HEADER;

echo <<<_SEARCH_BIBLE
<form action="http://www.biblegateway.com/quicksearch/" method="post">
<table border="1" cellspacing="0" cellpadding="2" style="border-color: #600;">
<tr><th style="background-color: #600; color:#fff; text-align: center; padding: 0 10px;">Look up a word or passage in the Bible</th></tr>
<tr><td style="background-color: #fff; text-align: center; padding: 0 10px;">
<p style="margin-bottom: 0;">
<input type="text" name="quicksearch" /><br />
<input type="submit" value="Search BibleGateway.com" /><br />
</p>
</td></tr>
</table>
</form>
_SEARCH_BIBLE;

echo <<<_END_BODY
<body vlink="#551a8b">
<table width="100%" id="intro">
<tr>
   <td valign=top>
     This archive has sermons preached live at <a href="http://www.walthamstowchurch.org.uk/">Walthamstow Churches</a>.
     To listen, please click the sermon title. You can download files by right clicking
     a link and selecting "Save link as..."
   </td>
_END_BODY;

echo <<<_END_TABLE_HEADER
<table width="100%" id="main" summary="Sermons from Walthamstow Churches">
   <col width="10%" id="date"><!-- date -->
   <col width ="4%" id="time"><!-- time -->
   <col width="26%" id="title"><!-- title -->
   <col width="20%" id="sermon_series"><!-- series -->
   <col width="15%" id="bibleref"><!-- bibleref -->
   <col width="20%" id="preacher"><!-- preacher -->
   <col width="5%" id="length" align=right> <!-- length -->
<thead>
<tr class=title>
  <td><a href="index_date.html"><b>Date</b></a></td>
  <td><a href="index_time.html"><b>Time</b></a></td>
  <td><a href="index_title.html"><b>Sermon Title</b></a> (Click to listen)</td>
  <td><a href="index_series.html"><b>Sermon Series</b></a></td>
  <td><a href="index_reference.html"><b>Bible Reference</b></a></td>
  <td><a href="index_length.html"><b>Length</b></a></td>
  <td><a href="index_preacher.html"><b>Preacher</b></a></td>
  </tr>
</thead>
_END_TABLE_HEADER;


echo "<tbody id=\"sermonsmp3\">";

while ($row = mysql_fetch_assoc($result)) {
  $preacher_list[] = $row['person'];
}

$rows = mysql_num_rows($result);

for ($j = 0; $j < $rows ; ++$j)
{
  $row = mysql_fetch_row($result);
  if (!$row) continue;
  echo '<tr class=hl>'; // Row header
  echo '<td>' . $row[1] . '</td>'; // Date
  echo '<td>' . $row[2] . '</td>'; // Time
  echo '<td><a href=mp3/' . $row[6] . '>' . $row[3] . '</a></td>'; // Title & MP3 File
  echo '<td>' . $row[4] . '</td>'; // Series
  echo '<td><a href="https://www.biblegateway.com/passage/?search=%22' . $row[5] 
    . '%22&&version=NIV">' . $row[5] . '</a></td>'; // Ref
  echo '<td>' . $row[7] . '</td>'; // Preacher
  echo '<td>' . $row[8] . '</td>'; // Length
}

echo <<<_END_TABLE_BODY
</tbody>
</table>
</body>
</html>
_END_TABLE_BODY;

?>
