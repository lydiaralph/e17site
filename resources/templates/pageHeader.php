<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<!-- TODO This section will be populated to appear like walthamtowchurch.org.uk header -->

<html lang="en">
<head>
<title>Walthamstow sermons</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . STYLE_PATH . "\">";

//echo constant("PUBLIC_PATH") . "/css/wc_sermons.css";
    

        
?>

</head>

<body>

    
<?php

//  echo "<div class='adminMenu'><a href=\"" . TEMPLATES_PATH . "/admin.php\">Log in</a></div>";
?>

<div class="header">
    <!-- TODO: soft link to RESOURCES... -->
    <!-- TODO: replace with admin.php which is landing page for admin where admin can
    delete or insert records -->
    <!-- TODO: hide if user is logged in -->
    <div class='adminMenu'><a href="/resources/templates/admin/createNewRecord.php">Log in</a></div>

    <div class='pageHeader'>Welcome</div>
</div>
<?php
$firephp = FirePHP::getInstance(true);
?>
 
<div class="mainContent">
