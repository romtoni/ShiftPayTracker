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
	global $conn, $msg;
	$stambuk=$_POST["stambuk"];
	$nama=strtoupper($_POST["nama"]);
	$upah=$_POST["upah"];
	$kode_bagian=$_POST["kode_bagian"];

	$strsql = "select count(*) as jumlah from REF_PEGAWAI where stambuk = '$stambuk'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$jumlah_Data = $row["JUMLAH"];
	
	if ($jumlah_data == 0)
	{
		$strsql="insert into REF_PEGAWAI(STAMBUK, NAMA, UPAH, KODE_BAGIAN) 
			 values('$stambuk','$nama', $upah, $kode_bagian) ";
		// echo $strsql;
		if($stambuk!="" and $nama!="" and $upah!="" and $kode_bagian !="") 
		{
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
			$msg = "Data berhasi disimpan!";
		}
		else
		{
			$msg ="Data sudah ada!";
		}
	}
	else
	{
		$msg = "Stambuk tersebut sudah terdaftar!";
	}
}

function open()
{
	global $conn;
	$data="";
	$sql="select * from ref_pegawai order by kode_bagian asc, stambuk asc";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$no=0;
	
	while($row=oci_fetch_assoc($q))
	{
		$no++;
		if(fmod($no,2)==0) $class='baris1'; else $class='baris2';
		$stambuk=$row["STAMBUK"];
		$nama=$row["NAMA"];
		$upah=$row["UPAH"];
		$kode_bagian=$row["KODE_BAGIAN"];
		if ($kode_bagian == 1) $ket_bagian = "KEPALA DEPARTEMEN";
		if ($kode_bagian == 2) $ket_bagian = "KEPALA BAGIAN";
		if ($kode_bagian == 3) $ket_bagian = "STAFF";
		
		$adel="<a href='ref_pegawai_del.php?stambuk=$stambuk'><img src='../images/img_del.png' onclick='return confirm(\"Apakah anda Yakin??\")'></a>";
		$aedit="<a href='ref_pegawai_edit.php?stambuk=$stambuk' onmouseover=\"ddrivetip('Edit User', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_edit.png' border='0'></a>&nbsp;";		
		$data.="<tr class='$class'>
				<td align='center'>$no</td>
				<td>$stambuk</td>
				<td>$nama</td>				
				<td>$upah</td>
				<td>$ket_bagian</td>
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
                      <td width="100%" align="center" class="title_banner">PEGAWAI</td>
              </tr>
        <tr>
          <td align="center" valign="top"><form name="form1" id="form1" method="post" action="">
          
            <table width="100%" border="0">
              <tr>
                <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                <td width="891" align="left" valign="top" class="left_border">
                <table width="428" height="111"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="80" valign="top" bordercolor="#999999"><table width="682" border="0" cellpadding="2" cellspacing="2">
                        <thead>
                        <tr class="table_header">
                          <td  width="21" align="center" bordercolor="#999999">No</td>
                          <td  width="100" align="left">Stambuk</td>
                          <td  width="29" align="left">Nama</td>
                          <td  width="100" align="left">Uang Piket</td>
                          <td  width="100" align="left">Kode Bagian</td>
                          <td  width="48" align="center" >Action</td>
                        </tr>
                        <tr >
                          <td  align="center" bordercolor="#999999" >&nbsp;</td>
                          <td align="left" ><input name="stambuk" type="text" id="stambuk" size="10" maxlength="4"></td>
                          <td align="left"><input name="nama" type="text" id="nama" size="50"></td>
                          <td align="left"><input name="upah" type="text" id="upah" size="10"></td>    
                          <td align="left">
                          					<select name="kode_bagian" id="kode_bagian">
                                            	<option value="">--Select--</option>
                                            	<option value="1">Kepala Departemen</option>
                                            	<option value="2">Kepala Bagian</option>
                                            	<option value="3">Staff</option>
                                            </select>
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