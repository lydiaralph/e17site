<?php // login.php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

$db_table = 'sermon_files';

if(isset($_POST['username']))
  $username = $_POST['username'];
else $username = "(Not entered)";

if(isset($_POST['password']))
  $password = $_POST['password'];
else $password = "(Not entered)";

  $db_server = mysql_connect('walthamstowsermonsor.fatcowmysql.com', 
                   $username, $password);
    if(!$db_server) die("Unable to connect: " . mysql_error());
    else echo "You are now logged in";
    
   mysql_select_db($db_table)
      or die("Unable to select database: " . mysql_error());

echo <<<_END
<html>
<head><title>Please enter your username and password</title></head>
<body>
<form method='post' action='login.php' enctype='multipart/form-data'>
Username: <input type='text' name='username' size='10'><br>
Password: <input type='text' name='password' size='10'><br>
<input type='submit' value='Log in' />
</form>
</body>
</html
_END;
?>
