<?
include("../auth.php");
include("../server.php"); 
include("../lib.php"); 
include("../auth_role.php");
include("../classes/class.lookup.php"); 

global $xkode_pelanggan;
$xkode_pelanggan=$_GET["kode_pelanggan"];
$msg="";
function save()
{
	global $conn;
	global $msg;
	$msg="";
	$xkode_pelanggan=$_POST["xkode_pelanggan"];
	$nama_pelanggan=$_POST["nama_pelanggan"];
	$jenis_bisnis=$_POST["jenis_bisnis"];
	$userlogin=$_SESSION["server_user"];

	$sql = "BEGIN SP_SERVER_UPDATE_PELANGGAN('$xkode_pelanggan', '$nama_pelanggan', '$jenis_bisnis','$userlogin'); END; ";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	//$msg="Data Berhasil DiUbah";
	header("location:server_setup_pelanggan.php");
}
function open()
{
	global $conn;
	global $xkode_pelanggan;
	$data="";
	$sql="select KODE_PELANGGAN, NAMA_PELANGGAN, JENIS_BISNIS from TM_SERVER_API_VA_PELANGGAN WHERE FLAG_DELETE IS NULL";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$no=0;
	while($row=oci_fetch_assoc($q))
	{
		$no++;
		if(fmod($no,2)==0) $class='baris1'; else $class='baris2';
		$kode_pelanggan=$row["KODE_PELANGGAN"];
		$nama_pelanggan=$row["NAMA_PELANGGAN"];
		$jenis_bisnis=$row["JENIS_BISNIS"];

		if($xkode_pelanggan==$kode_pelanggan)
		{
			$kode_pelanggan="<input name='kode_pelanggan' type='text' id='kode_pelanggan' size='30' value='$kode_pelanggan' disabled>";
			$nama_pelanggan="<input name='nama_pelanggan' type='text' id='nama_pelanggan' size='30' value='$nama_pelanggan'>";
			$jenis_bisnis="<input name='jenis_bisnis' type='text' id='jenis_bisnis' size='30' value='$jenis_bisnis'>";
			$action='<input type="submit" name="submit" id="submit" value="Submit">';
		}
		else
		{
			$adel="<a href='server_setup_pelanggan_del.php?kode_pelanggan=$kode_pelanggan'><img src='../images/img_del.png' onclick='return confirm(\"Apakah anda Yakin??\")'></a>";
			$aedit="<a href='server_setup_pelanggan_edit.php?kode_pelanggan=$kode_pelanggan' onmouseover=\"ddrivetip('Edit Pelanggan', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_edit.png' border='0'></a>&nbsp;";		
			$action = "$aedit $adel";
		}
		$data.="<tr class='$class'>
				<td align='center'>$no</td>
				<td>$kode_pelanggan</td>
				<td>$nama_pelanggan</td>					
				<td>$jenis_bisnis</td>	
				<td align='center'>$action</td>
				<tr>";
		}
	echo $data;
	}


if(isset($_POST["submit"]) and $_POST["submit"]!="") save(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<link rel="icon" href="../images/icon.gif">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="stylesheet" type="text/css" href="../tooltip.css" />
<script language="JavaScript" src="../tooltip.js"></script>
</head>
<body>
<form method="post" name="myform">
<input type="hidden" name="xkode_pelanggan" id="xkode_pelanggan" value="<?=$xkode_pelanggan?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
        <td width='98%'><table width="100%"  border="0" class="outside_border">
            <tr>
              <td>
			  <? include("../inc_banner.php"); ?></td>
            </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
            <? require_once("../inc_menu.php"); ?>
                    <tr>
                      <td width="100%" align="center" class="title_banner">USERS</td>
                    </tr>
            <tr>
              <td align="center" valign="middle"><table width="100%" border="0">
                <tr>
                  <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_tagihan.php"); ?></td>
                  <td width="891" align="left" valign="top" class="left_border"><table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="padding_3"><a href='server_setup_pelanggan.php' onMouseOver="ddrivetip('Entry Pelanggan', 80)" onMouseOut="hideddrivetip()"><img src="../images/xinsert.png" alt="" border='0'></a></td>
                    </tr>
                    <tr>
                      <td width="600" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <tr class="table_header">
                          <td  width="21" align="center" valign="middle" bordercolor="#999999">No</td>
                          <td  width="100" align="left" valign="middle">Kode Pelanggan</td>
                          <td  width="29" align="left" valign="middle">Nama Pelanggan</td>
                          <td  width="100" align="left" valign="middle">Jenis Bisnis</td>
                          <td  width="48" align="center" valign="middle" >Action</td>
                        </tr>
                        <? open(); ?>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
            <? include("../inc_footer.php"); ?>
        </table></td>
        <td width='1%' background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
      </tr>
    </table>    </td>
  </tr>
</table>
</form>
</body>
<?
if($msg!="")
echo "<script>alert('$msg');</script>";
?>
</html>