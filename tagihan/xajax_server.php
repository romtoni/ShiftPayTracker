<?php
session_start();
include("../lib.php");
include("../xinc_lookup.php");
include("../xinc_cari.php");

include("xinc_client_kirim_tagihan.php");
include("xinc_client_laporan_tagihan.php");

include("xinc_server_create_akses_tagihan.php");
include("xinc_server_laporan_tagihan.php");
require("xajax_common.php");

$xajax->processRequests();


?>