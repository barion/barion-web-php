<?php

include "iBarionModel.php";
include "BarionHelper.php";
$include_dirs = Array(realpath(dirname(__FILE__)."/../common"), realpath(dirname(__FILE__)."/../models"));
foreach ($include_dirs as $dir) {
  foreach(glob($dir.'/*.php') as $file) {
      include $file;
  }
}

?>