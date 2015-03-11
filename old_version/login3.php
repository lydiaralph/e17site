<?php // login.php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$db_table = 'sermon_files';

 if(!(isset($_POST['username'])))
   $username = "(Not entered)"; 

 else 
  {
    $username = $_POST['username'];

    if(!(isset($_POST['password'])))
      $password = "(Not entered)"; 

    else
    {
      $password = $_POST['password'];

      $db_server = mysql_connect('walthamstowsermonsor.fatcowmysql.com', 
                   $username, $password);
      if(!$db_server) die("Unable to connect: " . mysql_error());
      else echo "You are now logged in";
    
      mysql_select_db($db_table)
      or die("Unable to select database: " . mysql_error());
    }
  }
?>
