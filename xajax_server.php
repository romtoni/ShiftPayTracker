<?php
session_start();
include("lib.php");
include("xinc_cari.php");
include("xinc_lookup.php");

require("xajax_common.php");
$xajax->processRequests();
?>