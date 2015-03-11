<?php

/* Test comment */

/* Contains constants for walthamstowsermons.org.uk
   Bible books
   SQL fields
*/   


$bible_books = array (
      'None' => "None",
      'Genesis' => "Genesis",
      'Exodus' => "Exodus",
      'Leviticus' => "Leviticus",
      'Numbers' => "Numbers",
      'Deuteronomy' => "Deuteronomy",
      'Joshua' => "Joshua",
      'Judges' => "Judges",
      'Ruth' => "Ruth",
      '1 Samuel' => "1 Samuel",
      '2 Samuel' => "2 Samuel",
      '1 Kings' => "1 Kings",
      '2 Kings' => "2 Kings",
      '1 Chronicles' => "1 Chronicles",
      '2 Chronicles' => "2 Chronicles",
      'Ezra' => "Ezra",
      'Nehemiah' => "Nehemiah",
      'Esther' => "Esther",
      'Job' => "Job",
      'Psalm' => "Psalm",
      'Proverbs' => "Proverbs",
      'Ecclesiastes' => "Ecclesiastes",
      'Song of Solomon' => "Song of Solomon",
      'Isaiah' => "Isaiah",
      'Jeremiah' => "Jeremiah",
      'Lamentations' => "Lamentations",
      'Ezekiel' => "Ezekiel",
      'Daniel' => "Daniel",
      'Hosea' => "Hosea",
      'Joel' => "Joel",
      'Amos' => "Amos",
      'Obadiah' => "Obadiah",
      'Jonah' => "Jonah",
      'Micah' => "Micah",
      'Nahum' => "Nahum",
      'Habakkuk' => "Habakkuk",
      'Zephaniah' => "Zephaniah",
      'Haggai' => "Haggai",
      'Zechariah' => "Zechariah",
      'Malachi' => "Malachi",
      'Matthew' => "Matthew",
      'Mark' => "Mark",
      'Luke' => "Luke",
      'John' => "John",
      'Acts' => "Acts",
      'Romans' => "Romans",
      '1 Corinthians' => "1 Corinthians",
      '2 Corinthians' => "2 Corinthians",
      'Galatians' => "Galatians",
      'Ephesians' => "Ephesians",
      'Philippians' => "Philippians",
      'Colossians' => "Colossians",
      '1 Thessalonians' => "1 Thessalonians",
      '2 Thessalonians' => "2 Thessalonians",
      '1 Timothy' => "1 Timothy",
      '2 Timothy' => "2 Timothy",
      'Titus' => "Titus",
      'Philemon' => "Philemon",
      'Hebrews' => "Hebrews",
      'James' => "James",
      '1 Peter' => "1 Peter",
      '2 Peter' => "2 Peter",
      '1 John' => "1 John",
      '2 John' => "2 John",
      '3 John' => "3 John",
      'Jude' => "Jude",
      'Revelation' => "Revelation");

$month_list = array (
  'Jan' => "January",
  'Feb' => "February",
  'Mar' => "March",
  'Apr' => "April",
  'May' => "May",
  'Jun' => "June",
  'Jul' => "July",
  'Aug' => "August",
  'Sep' => "September",
  'Oct' => "October",
  'Nov' => "November",
  'Dec' => "December");



ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

defined("ROOT")
    or define("ROOT", realpath(dirname(__RESOURCES__)));

defined("STYLE_PATH")
    or define("STYLE_PATH", ('/public_html/css/sermons.css'));

defined("PUBLIC_PATH")
    or define("PUBLIC_PATH", (__ROOT__ . '/public_html'));

defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", (__RESOURCES__ . '/templates'));

defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", (__RESOURCES__ . '/library'));

?>
