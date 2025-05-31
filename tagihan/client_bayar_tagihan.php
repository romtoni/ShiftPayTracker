<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
include ("../api-client/bayar_va.php");
//session_start();
$msg = "";
function bayar()
{
	global $msg;
	
	$id_pengenal=567;
	$kunci_rahasia="is8s2btmpcjogqoh1bmvigczt67lpara";
	$userlogin=$_SESSION["server_user"];

	$kode_pelanggan=$_POST["kode_pelanggan"];
	$no_va=$_POST["no_va"];
	$total_tagihan=$_POST["total_tagihan"];
	
	if ($id_pengenal=="" or $kunci_rahasia == "" or $kode_pelanggan=="" or $no_va=="" or $total_tagihan=="" or $userlogin=="")
	{
		$msg = "Masih ada field yang kosong";
	}
	else
	{
		$status_bayar=bayar_tagihan_api($id_pengenal, $kunci_rahasia, $kode_pelanggan, $no_va, $total_tagihan, $userlogin);
		
		if ($status_bayar == "000") $msg = "Berhasil\\n Pembayaran tagihan dengan VA ".$no_va." berhasil dilakukan";
		else  $msg = "Gagal\\n Pembayaran tagihan dengan VA ".$no_va." gagal dilakukan";
	}
}


if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") bayar();
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
<body onLoad="">
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
                      <td width="100%" align="center" class="title_banner">BAYAR TAGIHAN</td>
                    </tr>
        <tr>
          <td align="center" valign="top"><form name="form1" id="form1" method="post" action="">
          
            <table width="100%" border="0">
              <tr>
                <td width="122" height="150" align="left" valign="top"><? require_once("inc_menu_tagihan.php"); ?></td>
                <td width="1040" align="left" valign="top" class="left_border">
                <table width="100%" height="111"  border="0" cellpadding="0" cellspacing="0">
                        <tr >
                          <td valign="top">Nama Pelanggan</td>
                          <td valign="top">:</td>
                          <td valign="top">
                          	<? 
										$lookup=new Lookup();
										$lookup->name="kode_pelanggan";
										$lookup->id="kode_pelanggan";					
										$lookup->sql="select * from TM_SERVER_API_VA_PELANGGAN where FLAG_DELETE IS NULL order by NAMA_PELANGGAN";
										$lookup->value_field="KODE_PELANGGAN";
										$lookup->list_field="NAMA_PELANGGAN";
										$lookup->default_value="AJBBP1912";					
										$lookup->onchange="";
										$lookup->separator=" - ";
										echo $lookup->dropdown();
										?>
                          </td>
                        </tr>
                        <tr>
                          <td width="169" valign="top">No Virtual Account</td>
                          <td width="10" valign="top">:</td>
                          <td width="838" valign="top">
                          	<input type="text" name="no_va" id="no_va">
                          </td>
                        </tr>
                        <tr >
                          <td valign="top">Total Tagihan</td>
                          <td valign="top">:</td>
                          <td valign="top">
                          <input type="text" name="total_tagihan" id="total_tagihan">
                          </td>
                        </tr>
                        <tr >
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td valign="top"><input type="submit" name="btnsubmit" id="btnsubmit" value="Bayar"></td>
                        </tr>
                        </tbody>
                      </table>
                      </td>
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
	echo "<script>window.location.href='client_bayar_tagihan.php'</script>"; 
}
?>
</html>