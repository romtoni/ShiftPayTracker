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
	$kode = "";
	$stambuk=$_POST["stambuk"];
	$kode_kegiatan=$_POST["kode_kegiatan"];
	$tgl_kegiatan=$_POST["tgl_kegiatan"];
	$biaya=$_POST["biaya"];
	$keterangan=nl2br($_POST["keterangan"]);
	$id_pengenal=567;
	$userlogin=$_SESSION["server_user"];
	
	if ($stambuk=="" or $kode_kegiatan == "" or $tgl_kegiatan=="" or $biaya=="" or $keterangan=="" or $id_pengenal=="" or $userlogin=="")
	{
		$msg = "Masih ada field yang kosong";
		$kode = "T";
	}
	else
	{
		$strsql="BEGIN SP_CLIENT_CREATE_VA('$stambuk','$kode_kegiatan','$tgl_kegiatan','$biaya','$keterangan','$id_pengenal','$userlogin'); END;";
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
		
		$msg = "NO VA untuk transaksi tersebut berhasil dibuat";
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
                      <td width="100%" align="center" class="title_banner">CREATE TAGIHAN</td>
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
                          <td width="169" valign="top">Nama Pegawai</td>
                          <td width="10" valign="top">:</td>
                          <td width="838" valign="top"><?
						  if ($_SESSION["server_role"]==1)
						  {
							  $userlogin ="";
							  $cond_lookup="";
							  $no_value = true;
						  }
						  else
						  {
							  $userlogin = $_SESSION["server_user"];
							  $cond_lookup="WHERE STAMBUK = '$userlogin'";
							  $no_value = false;
						  }
						   
								$lookup=new Lookup();
								$lookup->name="stambuk";
								$lookup->id="stambuk";					
								$lookup->sql="select * from REF_PEGAWAI $cond_lookup order by STAMBUK";
								$lookup->value_field="STAMBUK";
								$lookup->list_field="STAMBUK/NAMA";
								$lookup->default_value=$userlogin;	
								$lookup->no_value=$no_value;					
								$lookup->onchange="";
								$lookup->separator=" - ";
								echo $lookup->dropdown();
								?>           </td>
                        </tr>
                        <tr>
                          <td valign="top">Jenis Kegiatan</td>
                          <td valign="top">:</td>
                          <td valign="top"><? 
								$lookup=new Lookup();
								$lookup->name="kode_kegiatan";
								$lookup->id="kode_kegiatan";					
								$lookup->sql="select * from REF_KEGIATAN";
								$lookup->value_field="KODE_KEGIATAN";
								$lookup->list_field="NAMA_KEGIATAN";
								$lookup->default_value="";					
								$lookup->onchange="";
								echo $lookup->dropdown();
								?>  </td>
                        </tr>
                        <tr>
                          <td valign="top">Tanggal Kegiatan</td>
                          <td valign="top">:</td>
                          <td valign="top">
                          	<input name="tgl_kegiatan" type="text" id="tgl_kegiatan" size="10" readonly>
                      		<a href="javascript:displayDatePicker('tgl_kegiatan',false, 'dmy', '-');"><img src="../images/xcalendar.png" alt="" width="16" height="16" border="0" align="absmiddle" ></a>

                          </td>
                        </tr>
                        <tr>
                          <td valign="top">Biaya</td>
                          <td valign="top">:</td>
                          <td valign="top"><input type="text" name="biaya" id="biaya"></td>
                        </tr>
                        <tr>
                          <td valign="top">Keterangan</td>
                          <td valign="top">:</td>
                          <td valign="top"><textarea name="keterangan" id="keterangan" cols="45" rows="5"></textarea></td>
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
	if ($kode == "Y") echo "<script>window.location.href='client_kirim_tagihan.php'</script>"; 
}
?>
</html>