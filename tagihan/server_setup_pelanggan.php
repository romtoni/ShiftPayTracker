<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
//session_start();
$msg="";
function save()
{
	global $conn;
	$kode_pelanggan=$_POST["kode_pelanggan"];
	$jenis_bisnis=$_POST["jenis_bisnis"];
	$nama_pelanggan=$_POST["nama_pelanggan"];
	$userlogin=$_SESSION["server_user"];

	$strsql="BEGIN SP_SERVER_INSERT_PELANGGAN('$kode_pelanggan','$nama_pelanggan','$jenis_bisnis', '$userlogin'); END;";
	// echo $strsql;
	if($kode_pelanggan!="" and $jenis_bisnis!="" and $nama_pelanggan!="") 
	{
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
	}
}

function open()
{
	global $conn;
	$data="";
	$sql="select KODE_PELANGGAN, NAMA_PELANGGAN, JENIS_BISNIS from TM_SERVER_API_VA_PELANGGAN WHERE FLAG_DELETE IS NULL";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$no=0;
	
	while($row=oci_fetch_assoc($q)){
		$no++;
		if(fmod($no,2)==0) $class='baris1'; else $class='baris2';
		$kode_pelanggan=$row["KODE_PELANGGAN"];
		$nama_pelanggan=$row["NAMA_PELANGGAN"];
		$jenis_bisnis=$row["JENIS_BISNIS"];

		$adel="<a href='server_setup_pelanggan_del.php?kode_pelanggan=$kode_pelanggan'><img src='../images/img_del.png' onclick='return confirm(\"Apakah anda Yakin??\")'></a>";
		$aedit="<a href='server_setup_pelanggan_edit.php?kode_pelanggan=$kode_pelanggan' onmouseover=\"ddrivetip('Edit Pelanggan', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_edit.png' border='0'></a>&nbsp;";		
		$data.="<tr class='$class'>
				<td align='center'>$no</td>
				<td>$kode_pelanggan</td>
				<td>$nama_pelanggan</td>				
				<td>$jenis_bisnis</td>
				<td align='center'>$aedit $adel</td>
				<tr>";
		}
	echo $data;
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
                      <td width="100%" align="center" class="title_banner"> SETUP PELANGGAN</td>
                    </tr>
        <tr>
          <td align="center" valign="top"><form name="form1" id="form1" method="post" action="">
          
            <table width="100%" border="0">
              <tr>
                <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_tagihan.php"); ?></td>
                <td width="891" align="left" valign="top" class="left_border">
                <table width="600" height="111"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="80" valign="top" bordercolor="#999999">
                      <table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <thead>
                        <tr class="table_header">
                          <td  width="21" align="center" valign="middle" bordercolor="#999999">No</td>
                          <td  width="100" align="left" valign="middle">Kode Pelanggan</td>
                          <td  width="29" align="left" valign="middle">Nama Pelanggan</td>
                          <td  width="100" align="left" valign="middle">Jenis Bisnis</td>
                          <td  width="48" align="center" valign="middle" >Action</td>
                        </tr>
                        <tr >
                          <td  align="center" bordercolor="#999999" >&nbsp;</td>
                          <td align="left" ><input name="kode_pelanggan" type="text" id="kode_pelanggan" size="30" class="required"></td>
                          <td align="left"><input name="nama_pelanggan" type="jenis_bisnis" id="nama_pelanggan" size="30" class="required"></td>
                          <td align="left">
                              <input name="jenis_bisnis" type="jenis_bisnis" id="jenis_bisnis" size="30" class="required">
                          </td>                          
                          <td align="center" ><input name="btnsubmit" type="submit" id="btnsubmit" value="Submit"></td>
                        </tr>
                        </thead>
                        <tbody id="mytable">
                        <?  open(); ?>
                        </tbody>
                      </table></td>
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