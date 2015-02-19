<?php

/* 
 * This is a test wrapper file, to build up components of sermons.php
 */

define('__RESOURCES__', dirname(dirname(__FILE__))); 
require_once(__RESOURCES__.'/config.php'); 



require_once(TEMPLATES_PATH . "/pageHeader.php");

?>

<!-- pageHeader.php content -->

<!--
<html lang="en">
<head>
<title>Walthamstow sermons</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="wc_sermons.css">
</head>

<body>
<div class="mainContent">
-->

  <!-- sermons.php content       -->
  <div id="container">
  <div id="filterBox">
   <script defer type="text/javascript" src="filter.js"></script>
   <form id="selform" action="">
   <p><h3>Filter your results</h3></p>

</div>

<div id="sermonsTable">
  <div id="sermonsTableHeader">
  </div>
  <div id="sermonRecords">
  </div>
</div>

<!-- pageFooter.php content -->
<?php
      require_once(TEMPLATES_PATH . "/pageFooter.php");
?>
