<?php
// multiply.php, multiply.common.php, multiply.server.php
// demonstrate a very basic xajax implementation
// using xajax version 0.2
// http://xajaxproject.org

require_once ("xajax/xajax.inc.php");
$xajax = new xajax("xajax_server.php");

$xajax->registerFunction("format_currency"); //xinc_cari
$xajax->registerFunction("dateval"); //xinc_cari
?>