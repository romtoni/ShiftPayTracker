<?php

require_once ("xajax/xajax.inc.php");
$xajax = new xajax("xajax_server.php");

$xajax->registerFunction("display_client_kirim_tagihan");//xinc_client_kirim_tagihan.php
$xajax->registerFunction("display_client_laporan_tagihan");//xinc_client_laporan_tagihan.php

$xajax->registerFunction("display_server_create_akses_tagihan");//xinc_server_create_akses_tagihan.php
$xajax->registerFunction("edit_status_akses");//xinc_server_create_akses_tagihan.php
$xajax->registerFunction("edit_status_akses_detail");//xinc_server_create_akses_tagihan.php
$xajax->registerFunction("display_server_laporan_tagihan");//xinc_server_laporan_tagihan.php
?>