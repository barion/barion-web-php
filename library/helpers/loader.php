<?php

include "iBarionModel.php";
include "BarionHelper.php";
$include_dirs = Array(dirname(__FILE__)."/../common", dirname(__FILE__)."/../models");
foreach ($include_dirs as $dir) {
  $handle = opendir($dir);
  while (false !== ($file = readdir($handle))) {
      if (endsWith($file, ".php")) {
        include $dir."/".$file;
      }
  }
}

?>