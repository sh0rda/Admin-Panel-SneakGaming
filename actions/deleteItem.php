<?php
include('../template-parts/session.php');
require '../config.php';

$itemid = $_GET['itemid'];

//if(isValidCsrfToken($_GET['csrfToken'])) {
  $sql = "DELETE FROM ".TRUNK_INVENTORY_TABLE." WHERE ".TRUNK_INVENTORY_COLUM_ID." = '{$itemid}' ";

  if ($link->query($sql) === TRUE) {
      echo "Item deleted";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
//}
