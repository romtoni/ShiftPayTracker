<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
include ("../api-client/cek_va.php");
include("inc_client_cek_va_tagihan.php");
//session_start();
$msg = "";
$tbl_msg="";
function cek_va_tagihan()
{
	global $msg, $tbl_msg;
	
	$kode_pesan = "";
	$pesan_error = ""; 
	$no_va_pelanggan = ""; 
	$nama_tertagih = ""; 
	$total_tagihan = ""; 
	$keterangan_tagihan = ""; 
	$tgl_va_expired = ""; 
	$status_expired = ""; 
	$status_transaksi = "";	
	$tbl_msg = "";
	
	$no_va=$_POST["no_va"];
	if ($no_va=="")
	{
		$msg = "Mohon isi no Virtual Account";
	}
	else
	{
		$data_va=cek_va_api($no_va);
		$pecahan_data_va=explode("|",$data_va);
		
		$no_va_pelanggan = $no_va; 
		$status_cek = $pecahan_data_va[0]; 
		$nama_tertagih = $pecahan_data_va[1]; 
		$total_tagihan = number_format($pecahan_data_va[2],0,",","."); 
		$keterangan_tagihan = $pecahan_data_va[3]; 
		$tgl_va_expired = $pecahan_data_va[4]; 
		$status_expired = $pecahan_data_va[5];  
		$status_transaksi = $pecahan_data_va[6]; 	
		$pesan_error = $pecahan_data_va[7];  
		
		if ($status_cek == "Y") 
		{
			$tbl_msg=table_cek_va('VALID', '', $no_va_pelanggan, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $tgl_va_expired, $status_expired, $status_transaksi);
		}
		else  
		{
			$tbl_msg=table_cek_va('INVALID', $pesan_error, '', '', '', '', '', '', '');
		}
	}
}


if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") cek_va_tagihan();
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
                      <td width="100%" align="center" class="title_banner">CEK VIRTUAL ACCOUNT TAGIHAN</td>
                    </tr>
        <tr>
          <td align="center" valign="top"><form name="form1" id="form1" method="post" action="">
          
            <table width="100%" border="0">
              <tr>
                <td width="122" height="97" align="left" valign="top"><? require_once("inc_menu_tagihan.php"); ?></td>
                <td width="1040" align="left" valign="top" class="left_border">
                <table width="100%" height="111"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="80" valign="top" bordercolor="#999999"><table width="100%" border="0">
                        <tr>
                          <td width="169" valign="top">No Virtual Account</td>
                          <td width="10" valign="top">:</td>
                          <td width="838" valign="top"><input type="text" name="no_va" id="no_va" value="<? if (isset($_POST["no_va"])) echo $_POST["no_va"]; ?>">
                            &nbsp;
                            <input type="submit" name="btnsubmit" id="btnsubmit" value="Cek VA"></td>
                        </tr>
                        <tbody id='tbl_va_detail'>
                        		<?=$tbl_msg;?>
                        </tbody>
                      </table>
                      
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
	echo "<script>window.location.href='client_cek_va_tagihan.php'</script>"; 
}
?>
</html>