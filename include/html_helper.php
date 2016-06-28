<?php

Function pull_down_from_hash($select_name,$current_value,$option_hash,$choose_first=false,$js_fix=false) {
  $rv = "";
  $field_name = $select_name;
  if($js_fix) {
    $rv .= "<!-- javascript form field name fix -->"
          ."<INPUT TYPE=hidden NAME=\"js_fixed[$field_name]\" VALUE=\"$field_name\">";
  }
  $rv .= "<SELECT name=\"$field_name\" ";
  if (isset($option_hash['onChange'])) {
      $rv .= "onChange=\"" . $option_hash['onChange'] . "\"";
      unset($option_hash['onChange']);
  }
  $rv .= ">\n";
  $first_selected = "";
  if ($choose_first) { $first_selected = "SELECTED"; }
  while ( list($key,$value) = each($option_hash) ) {
    if (($key == $current_value) && ! $choose_first) {
      $selected = "SELECTED";
    } else {
      $selected = "";
    }
    $rv .= "<OPTION value=\"$key\" $selected $first_selected>"
          .htmlspecialchars($value)
          ."</OPTION>\n";
    $first_selected = "";
  }
  $rv .= "</SELECT>\n";
  return $rv;
}

?>
