<?php

require_once ("xajax/xajax.inc.php");
$xajax = new xajax("xajax_server.php");


$xajax->registerFunction("cek_antrian_proses"); //xinc_cek_proses_antrian
/*
$xajax->registerFunction("backtozeroproses"); //xinc_update_execute_time
$xajax->registerFunction("cek_proses_antrian"); //xinc_update_execute_time
$xajax->registerFunction("update_execute_time"); //xinc_update_execute_time
*/

$xajax->registerFunction("hitung_email_blm_dikirim"); //xinc_kirim_email_ke_agen_by_prosedur

$xajax->registerFunction("display_history_email_ke_agen"); //xinc_history_email_ke_agen
$xajax->registerFunction("clear_ktremail_penerima_tmp"); //xinc_history_email_ke_agen
$xajax->registerFunction("ambil_ktremail_penerima_tmp"); //xinc_history_email_ke_agen

$xajax->registerFunction("display_waktu_terpakai_remunerasi"); //xinc_remunerasi_total_waktu_proses_terpakai.php
?>