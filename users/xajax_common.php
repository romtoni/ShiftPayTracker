<?php

require_once ("xajax/xajax.inc.php");
$xajax = new xajax("xajax_server.php");

$xajax->registerFunction("seleksi_bagian_by_kd_ukerja");//xinc_cari.php
$xajax->registerFunction("display_users");//xinc_users.php
$xajax->registerFunction("display_user_online");//xinc_kick_user.php
$xajax->registerFunction("display_menu_role");//xinc_menu_dan_role.php
$xajax->registerFunction("display_submenu");//xinc_menu_dan_role.php
$xajax->registerFunction("simpan_perubahan");//xinc_menu_dan_role.php
$xajax->registerFunction("display_monitoring_userlog");//xinc_monitoring_userlog.php

$xajax->registerFunction("display_pengumuman");//xinc_pengumuman_maintenance.php
$xajax->registerFunction("del_pengumuman");//xinc_pengumuman_maintenance.php

$xajax->registerFunction("display_menu_parent");//xinc_pengumuman_maintenance.php
$xajax->registerFunction("display_menu_child");//xinc_pengumuman_maintenance.php
?>