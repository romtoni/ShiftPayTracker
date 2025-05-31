<?php
session_start();

require("xajax_common.php");
include("xinc_kirim_email_ke_agen.php");
include("xinc_kirim_email_ke_agen_by_prosedur.php");
include("xinc_remunerasi_total_waktu_proses_terpakai.php");
//include("xinc_update_execute_time.php");
include("xinc_cek_proses_antrian.php");
$xajax->processRequests();
?>