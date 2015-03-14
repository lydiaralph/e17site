<?php

/** This is intended to be a wrapper file bringing together other resources.
    TODO: move to public_html folder */


define('__RESOURCES__', dirname(dirname(dirname(__FILE__)))); 
require_once(__RESOURCES__.'/config.php'); 

// SQL queries

// TODO: get records: $field_names, $full_db
require_once(LIBRARY_PATH . "/readDbRecords.php");

// Page contents     
require_once(TEMPLATES_PATH . "/pageHeader.php");
?>




  <div id="sermonsTableHeader">
    <?php
      require_once(TEMPLATES_PATH . "/sermon_records/sermonHeader.php");
    ?>
  </div>
    <div id="filter">
    <?php
        require_once(TEMPLATES_PATH . "/sermon_records/filterPanel.php");
    ?>
    </div>
    
  <div id="sermonRecords">
    <?php
      require_once(TEMPLATES_PATH . "/sermon_records/sermonRecord.php");
    ?>
  </div>

<?php
      require_once(TEMPLATES_PATH . "/pageFooter.php");
?>