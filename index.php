<?php
error_reporting(E_ALL & E_NOTICE); //will be turned off by cbg/config
require('include/cbg.class.php');
$cbg = new cbg();
$cbg->output();
?>
