<?php
define('__RESOURCES__', dirname(dirname(dirname(__FILE__)))); 
require_once(__RESOURCES__. "/config.php"); 
require_once(TEMPLATES_PATH . "/admin/phpFunctions.php");

list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

if( strlen($_SERVER['PHP_AUTH_USER']) == 0 ||
    strlen($_SERVER['PHP_AUTH_PW']) == 0)
{
    unset($_SERVER['PHP_AUTH_USER']);
    unset($_SERVER['PHP_AUTH_PW']);
}

  if (!isset($_SERVER['PHP_AUTH_USER']))
  {
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized');
    die("Please enter your name and password");
    exit;
  }
  else
  {// Connect to server
   $db_username = sanitizeString($_SERVER['PHP_AUTH_USER']);
   $db_password = sanitizeString($_SERVER['PHP_AUTH_PW']);
   
   $connected_to_db = mysql_connect ($db_hostname, $db_username, $db_password);

   if (!$connected_to_db) 
   {
       die('Could not connect: ' . mysql_error()); 
   }

   mysql_select_db($db_database)
     or die("Unable to select database: " . mysql_error() ); 
  }
?>

