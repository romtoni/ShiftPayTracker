<?
include('../auth.php');
include("../server.php");
include("../lib.php");
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
$msg="";
function simpan()
{
	global $conn, $msg;
	
	$kode_menu_utama = $_POST["menu_utama"];
	$sql="select NAMA_MENU from MN_MENU where kode_menu = '$kode_menu_utama'";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$nama_menu_utama=$row["NAMA_MENU"];
	
	$kode_menu_parent = $_POST["menu_parent"];
	$sql="select NAMA_MENU from MN_MENU where kode_menu = '$kode_menu_parent'";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$nama_menu_parent=$row["NAMA_MENU"];
	
	$kode_menu_child = $_POST["menu_child"];
	$sql="select NAMA_MENU from V_MENU_CHILD where kode_menu = '$kode_menu_child'";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$nama_menu_child=$row["NAMA_MENU"];
	
	$keterangan = $_POST["keterangan"];
	$userlogin = $_SESSION["server_user"];
	
	$sql = "INSERT INTO TM_PENGUMUMAN
			( 
				MENU_UTAMA, 
				MENU_PARENT,
				MENU_CHILD,
				KETERANGAN,
				USER_CREATE,
				TGL_CREATE
			)
			VALUES
			(
				'$nama_menu_utama',
				'$nama_menu_parent',
				'$nama_menu_child',
				'$keterangan',
				'$userlogin',
				sysdate
			)";
	$q=oci_parse($conn,$sql);
	oci_execute($q);		
	
	$msg = "Data Tersimpan";

}

if (isset($_POST["btn_simpan"]) and $_POST["btn_simpan"]!="") simpan();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="stylesheet" type="text/css" href="../tooltip.css" />
<link rel="icon" href="../favicon.ico">
<script language="JavaScript" src="../tooltip.js"></script>
<link rel="stylesheet" href="../example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="../example-print.css" TYPE="text/css" MEDIA="print">
<script type="text/javascript" src="../tabber_nav.js"></script>
<script language="JavaScript" src="../lib.js"></script>
<script type="text/javascript" src="../tabber.js"></script>
<SCRIPT LANGUAGE="JavaScript" src="../sms.js"></script>

<link rel="stylesheet" type="text/css" href="../jquery/jquery-ui.css" />
        <script language="JavaScript" src="../jquery/jquery.js"></script>
        <script language="JavaScript" src="../jquery/jquery-ui.js"></script>
        <script language="JavaScript" src="../jquery/validate.js"></script>
       
<?php $xajax->printJavascript('xajax/'); ?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
<script>
function display()
{
	xajax_display_pengumuman();
}

function del_pengumuman(id_pengumuman)
{
	var isok=confirm("Anda yakin untuk menghapus?");
	if (isok==true) 
	{
		xajax_del_pengumuman(id_pengumuman);
		alert('Data berhasil dihapus');
		window.location.href='pengumuman_maintenance.php';
	}
}
</script>
</head>
<body onLoad='display()'>
<div id="loader" align="center"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
    <td width='99%'><table width="100%"  border="0" align="center" class="outside_border">
        <tr>
          <td align="center" valign="middle"><? require_once("../inc_banner.php"); ?></td>
        </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
        <? require_once("../inc_menu.php"); ?>
                    <tr>
                      <td width="100%" align="center" class="title_banner"> PENGUMUMAN MAINTENANCE</td>
              </tr>
        <tr>
          <td align="center" valign="top">
          <form name="form1" id="form1" method="post" action="">
          <input type='hidden' name="nrow" id="nrow">
            <table width="100%" border="0">
              <tr>
                <td width="10%" height="97" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                <td width="90%" align="left" valign="top" class="left_border">
                <table width="100%" height="111"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="80" valign="top" bordercolor="#999999"><table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <tr>
                          <td colspan="3" align="left">
                          <table width="100%" border="0">
                            <tr>
                              <td colspan="8" align="center" bgcolor="#008000"><span class="produk merah style1"><strong>
                                <h3>LIST  MAINTENANCE YANG SEDANG BERLANGSUNG</h3>
                              </strong></span></td>
                              </tr>
                            <tr>
                              <td width="2%" height="38" align="center" bgcolor="#008000"><span class="merah style1"><strong>No</strong></span></td>
                              <td width="10%" align="center" bgcolor="#008000"><span class="merah style1"><strong> Menu Utama</strong></span></td>
                              <td width="9%" align="center" bgcolor="#008000"><span class="merah style1"><strong>Menu Parent</strong></span></td>
                              <td width="45%" align="center" bgcolor="#008000"><span class="merah style1"><strong>Menu Child <br>
                                (Menu yg diperbaiki)</strong></span></td>
                              <td width="15%" align="center" bgcolor="#008000"><span class="merah style1"><strong>Keterangan</strong></span></td>
                              <td width="7%" align="center" bgcolor="#008000"><span class="merah style1"><strong>Tgl Create</strong></span></td>
                              <td width="7%" align="center" bgcolor="#008000"><span class="merah style1"><strong>User Create</strong></span></td>
                              <td width="5%" align="center" bgcolor="#008000"><span class="merah style1"><strong>Aksi</strong></span></td>
                            </tr>
                            <tbody id='tbl_pengumuman'>
                            </tbody>
                          </table>
                          </td>
                          </tr>
                        <tr>
                          <td colspan="3" align="left">&nbsp;</td>
                          </tr>
                        <tr>
                          <td colspan="3" align="left" bgcolor="#008000"><span class="style1"><strong> Buat Pengumuman Baru</strong></span></td>
                          </tr>
                        <tr>
                          <td align="left">Menu Utama</td>
                          <td align="center" >:</td>
                          <td align="left" >
						  			<? 
                                    $lookup=new Lookup();
                                    $lookup->name="menu_utama";
                                    $lookup->id="menu_utama";					
                                    $lookup->sql="select * from MN_MENU WHERE KODE_PARENT IS NULL ORDER BY KODE_MENU";
                                    $lookup->value_field="KODE_MENU";
                                    $lookup->list_field="KODE_MENU/NAMA_MENU";
                                    $lookup->separator=" - ";
                                    $lookup->default_value="";					
                                    $lookup->onchange="xajax_display_menu_parent(this.value)";
                                    echo $lookup->dropdown();
                                    ?>                                   </td>
                        </tr>
                        <tr>
                          <td align="left">Menu Parent</td>
                          <td align="center" >:</td>
                          <td align="left" >
                          		<SELECT name="menu_parent" id="menu_parent" onChange="xajax_display_menu_child(this.value);">
                                	<option value="">--Select--</option>
                                </SELECT>                          </td>
                        </tr>
                        <tr>
                          <td align="left">Menu Child</td>
                          <td align="center" >:</td>
                          <td align="left" >
                          		<SELECT name="menu_child" id="menu_child">
                                	<option value="">--Select--</option>
                                </SELECT>                          </td>
                        </tr>
                        <tr>
                          <td align="left">Keterangan</td>
                          <td align="center" >:</td>
                          <td align="left" ><textarea name="keterangan" id="keterangan" cols="45" rows="5"></textarea></td>
                        </tr>
                        <tr>
                          <td  width="99" align="left">&nbsp;</td>
                          <td  width="4" align="center" >&nbsp;</td>
                          <td  width="755" align="left" ><input type='submit' id='btn_simpan' name='btn_simpan' value="Simpan"></td>
                        </tr>
                      </table>
                      </td>
                    </tr>
                </table></td>
              </tr>
            </table>
              </form></td>
        </tr>
        <? include("../inc_footer.php"); ?>
    </table></td>
    <td width='1%' align="center" valign="top" background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
  </tr>
  
</table></tr>
    </table>    </td>


</body>
 
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>
