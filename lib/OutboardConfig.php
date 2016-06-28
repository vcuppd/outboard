<?php

/**
 * OutboardConfig.php
 *
 * Controls getting and setting configuration options for the OutBoard.
 *
 * 2005-02-15	Richard F. Feuerriegel	(richardf@aces.edu)
 *	- Initial creation
 *
 **/


Class OutboardConfig {

var $config = array();

Function OutboardConfig() {
  include("config/config.php");
}

Function setConfig($name,$value) {
  if ($name != "") {
    $this->config[$name] = $value;
  }
}

Function getConfig($name) {
  if (isset($this->config[$name])) {
    return $this->config[$name];
  }
}

}

?>
