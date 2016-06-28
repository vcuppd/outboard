<?php

// common.php
//
// Common functions
// 
// 2005-02-17  Richard F. Feuerriegel  (richardf@aces.edu)

Function getGetValue($variable) {
  if (isset($_GET[$variable])) {
    return $_GET[$variable];
  }
}

Function getPostValue($variable) {
  if (isset($_POST[$variable])) {
    return $_POST[$variable];
  }
}


?>
