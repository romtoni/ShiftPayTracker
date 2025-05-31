<?php
session_start();
include("../lib.php");
include("../xinc_lookup.php");
include("../xinc_cari.php");
include("xinc_users.php");
include("xinc_kick_user.php");
include("xinc_menu_dan_role.php");
include("xinc_monitoring_userlog.php");
include("xinc_pengumuman_maintenance.php");
require("xajax_common.php");

$xajax->processRequests();


?>