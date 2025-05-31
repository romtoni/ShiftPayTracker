<?
include("../auth.php");
include("../server.php"); 
include("../lib.php"); 
include("../auth_role.php");
include("../classes/class.lookup.php"); 
$msg="";
global $xusername;
$stambuk=$_GET["stambuk"];

function save()
{
	global $conn;
	global $msg;
	$msg="";
	
	$xstambuk=$_POST["xstambuk"];
	$stambuk=$_POST["stambuk"];
	$nama=strtoupper($_POST["nama"]);
	$upah=$_POST["upah"];
	$kode_bagian=$_POST["kode_bagian"];
	
	$sql="update ref_pegawai 
			set 
				stambuk='$stambuk',
				nama='$nama',
				upah='$upah',
				kode_bagian='$kode_bagian' 
			where 
				stambuk='$xstambuk'";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	//$msg="Data Berhasil DiUbah";
	header("location:ref_pegawai.php");
}

function open()
{
	global $conn;
	global $stambuk, $nama, $upah, $selected_nothing, $selected_kadep, $selected_kabag, $selected_staff;

	$sql="select * from ref_pegawai where stambuk = '$stambuk'";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$no="";
	while($row=oci_fetch_assoc($q))
	{
		$no++;
		if(fmod($no,2)==0) $class='baris1'; else $class='baris2';
		$stambuk=$row["STAMBUK"];
		$nama=$row["NAMA"];
		$upah=$row["UPAH"];
		$kode_bagian=$row["KODE_BAGIAN"];
		
		if ($kode_bagian == 1) 
		{
			$selected_nothing = "";
			$selected_kadep = "selected";
			$selected_kabag = "";
			$selected_staff = "";
		}
		else if ($kode_bagian == 2)
		{
			$selected_nothing = "";
			$selected_kadep = "";
			$selected_kabag = "selected";
			$selected_staff = "";
		}
		else if ($kode_bagian == 3)
		{
			$selected_nothing = "";
			$selected_kadep = "";
			$selected_kabag = "";
			$selected_staff = "selected";
		}
		else
		{
			$selected_nothing = "selected";
			$selected_kadep = "";
			$selected_kabag = "";
			$selected_staff = "";	
		}
	}
}

if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") save(); 
open();
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
<form method="post" name="myform" id="myform" action="">
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
                      <td width="100%" align="center" class="title_banner">PEGAWAI</td>
                    </tr>
            <tr>
              <td align="center" valign="middle"><table width="100%" border="0">
                <tr>
                  <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                  <td width="891" align="left" valign="top" class="left_border"><table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="padding_3"><a href='users.php' onMouseOver="ddrivetip('Entry user', 80)" onMouseOut="hideddrivetip()"><img src="../images/xinsert.png" alt="" border='0'></a></td>
                    </tr>
                    <tr>
                      <td width="85%" valign="top"><table width="682" border="0" cellpadding="2" cellspacing="2">
                        <thead>
                        <tr class="table_header">
                          <td  width="19" align="center" bordercolor="#999999">No</td>
                          <td  width="56" align="left">Stambuk</td>
                          <td  width="256" align="left">Nama</td>
                          <td  width="108" align="left">Uang Piket</td>
                          <td  width="138" align="left">Kode Bagian</td>
                          <td  width="67" align="center" >Action</td>
                        </tr>
                        <tr >
                          <td  align="center" bordercolor="#999999" >&nbsp;</td>
                          <td align="left" >
                          <input name="xstambuk" type="hidden" id="xstambuk" size="10" maxlength="4" value="<?=$stambuk;?>">
                          <input name="stambuk" type="text" id="stambuk" size="10" maxlength="4" value="<?=$stambuk;?>">
                          
                          </td>
                          <td align="left"><input name="nama" type="text" id="nama" size="50" value="<?=$nama;?>"></td>
                          <td align="left"><input name="upah" type="text" id="upah" size="10" value="<?=$upah;?>"></td>    
                          <td align="left">
                          					<select name="kode_bagian" id="kode_bagian">
                                           	  <option value="" <?=$selected_nothing;?>>--Select--</option>
                                           	  <option value="1" <?=$selected_kadep;?>>Kepala Departemen</option>
                                           	  <option value="2" <?=$selected_kabag;?>>Kepala Bagian</option>
                                           	  <option value="3" <?=$selected_staff;?>>Staff</option>
                                            </select>
                          </td>                          
                          <td align="center" ><input name="btnsubmit" type="submit" id="btnsubmit" value="Submit"></td>
                        </tr>
                        </thead>
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