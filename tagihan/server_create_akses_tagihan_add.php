<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
//session_start();
$msg = "";
function save()
{
	global $conn, $msg, $kode;
	$kode="";
	$kode_pelanggan=$_POST["kode_pelanggan"];
	$ip_address=$_POST["ip_address"];
	$userlogin=$_SESSION["server_user"];
	
	if ($kode_pelanggan=="" or $ip_address=="" or $userlogin=="")
	{
		$msg = "Masih ada field yang kosong";
		$kode = "T";
	}
	else
	{
		$strsql="BEGIN SP_SERVER_CREATE_VA_AKSES('$kode_pelanggan','$ip_address','$userlogin'); END;";
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
		
		$msg = "Akses untuk pelanggan tersebut berhasil dibuat";
		$kode = "Y";
	}
	oci_close($conn);
}


if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") save();
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
<script language="JavaScript" src="../lib.js"></script>
<link rel="stylesheet" type="text/css" href="../datepicker.css"/>
<script language="JavaScript" src="../datepicker.js"></script>
</head>
<body>
<div id="loader" align="center"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
    <td width='98%'><table width="100%"  border="0" align="center" class="outside_border">
        <tr>
          <td align="center" valign="middle"><? require_once("../inc_banner.php"); ?></td>
        </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
        <? require_once("../inc_menu.php"); ?>
                    <tr>
                      <td width="100%" align="center" class="title_banner">CREATE AKSES TAGIHAN</td>
                    </tr>
        <tr>
          <td align="center" valign="top"><form name="form1" id="form1" method="post" action="">
          
            <table width="100%" border="0">
              <tr>
                <td width="122" height="97" align="left" valign="top"><? require_once("inc_menu_tagihan.php"); ?></td>
                <td width="1040" align="left" valign="top" class="left_border">
                <table width="100%" height="111"  border="0" cellpadding="0" cellspacing="0">
                <TR>
                  	<td>
                    	<a href='server_create_akses_tagihan.php' onmouseover="ddrivetip('List Akses', 40)" onmouseout="hideddrivetip()"><img src='../images/xlist.png'></a>
                    </td>
                  </TR>
                    <tr>
                      <td height="80" valign="top" bordercolor="#999999">
                      
                      <table width="100%" border="0">
                        <tr>
                          <td width="169" valign="top">Nama Pelanggan</td>
                          <td width="10" valign="top">:</td>
                          <td width="838" valign="top"><? 
								$lookup=new Lookup();
								$lookup->name="kode_pelanggan";
								$lookup->id="kode_pelanggan";					
								$lookup->sql="select * 
												from TM_SERVER_API_VA_PELANGGAN 
												where KODE_PELANGGAN NOT IN (SELECT KODE_PELANGGAN FROM TM_SERVER_API_VA_AKSES WHERE FLAG_AKTIF = 'Y')
												order by NAMA_PELANGGAN";
								$lookup->value_field="KODE_PELANGGAN";
								$lookup->list_field="NAMA_PELANGGAN";
								$lookup->default_value="";					
								$lookup->onchange="";
								$lookup->separator=" - ";
								echo $lookup->dropdown();
								?>           </td>
                        </tr>
                        <tr>
                          <td valign="top">IP Address</td>
                          <td valign="top">:</td>
                          <td valign="top"><input type="text" name="ip_address" id="ip_address"></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td valign="top"><input type="submit" name="btnsubmit" id="btnsubmit" value="Simpan"></td>
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
<? 
if($msg!="") 
{
	echo "<script>alert('$msg');</script>"; 
	if ($kode == "Y") echo "<script>window.location.href='server_create_akses_tagihan.php'</script>"; 
}
?>
</html>