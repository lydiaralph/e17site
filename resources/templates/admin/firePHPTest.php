<?php



define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__))))); 

require_once (__ROOT__ . '/FirePHPCore/fb.php');
require_once(__ROOT__ . '/FirePHPCore/FirePHP.class.php');


ob_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!-- TODO This section will be populated to appear like walthamtowchurch.org.uk header -->

<html lang="en">
<head>
<title>Walthamstow sermons</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


</head>

<body>
    
<?php
    
$firephp = FirePHP::getInstance(true);
 
$var = array('i'=>10, 'j'=>20);
 
$firephp->log($var, 'Iterators');

//FB::log('Log message');
//FB::info('Info message');
//FB::warn('Warn message');
//FB::error('Error message');

$firephp->log('Testing FirePHP');

ob_end_flush(); 

?>

    <!-- TODO: footer content -->
<div id="footer">
</div>

</div>
</body>
</html>
