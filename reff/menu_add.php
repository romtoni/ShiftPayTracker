<?
include("../auth.php");
include("../server.php");
include("../lib.php");
//include("../auth_role.php");
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
$msg="";
$kode_parent="";
$last_menu="";
$last_urutan="";
function save()
{
	global $conn;
	global $msg;
	$iscud = "";
	if (trim(isset($_POST["kode_menu"]))) $kode_menu=trim($_POST["kode_menu"]);
	if (trim(isset($_POST["nama_menu"]))) $nama_menu=trim($_POST["nama_menu"]);
	if (isset($_POST["link"])) $link=$_POST["link"];
	if (isset($_POST["iscud"])) $iscud=$_POST["iscud"];
	if($iscud=='') $iscud='N';
	if (isset($_POST["urutan"])) $urutan=$_POST["urutan"];
	if (isset($_POST["kode_parent"])) $kode_parent=$_POST["kode_parent"];
	//$isnew=$_POST["isnew"];
	
	$strsql="insert into mn_menu(kode_parent,nama_menu,link,urutan,iscud) 
			 values('$kode_parent','$nama_menu','$link','$urutan','$iscud')";
	//echo $strsql;
	if($nama_menu!="") 
	{
		  $q=oci_parse($conn,$strsql);
		  oci_execute($q);
		  
		  while($kode_parent != "")
		  {
			  	$sql_update="update mn_menu
										set 
											isnew = 'Y'
										where kode_menu = '$kode_parent'";
				$q_update=oci_parse($conn,$sql_update);
				oci_execute($q_update);
				
				//echo $sql_update."<br>";
				$kode_parent = recursive_cek_parent($kode_parent);
		  }
		  
		  //ambil id_menu yg barusan diinsert
		  $strsql = "select max(KODE_MENU) as KODE_MENU from MN_MENU";
		  $q = oci_parse($conn,$strsql);
		  oci_execute ($q);
		  $row=oci_fetch_array($q);
		  $kode_menu=$row["KODE_MENU"];	
		  //insert_to_rolemenu($kode_menu);
		  $msg="Data berhasil disimpan!"; 
	}  
}

function recursive_cek_parent($kode_parent)
{
	global $conn;
	$sql_cek = "select count(kode_parent) as jmldata, kode_parent from MN_MENU where kode_menu = '$kode_parent' group by kode_parent";
	$q_cek=oci_parse($conn,$sql_cek);
	oci_execute($q_cek);
	$row_cek = oci_fetch_assoc($q_cek);
	$jmldata = $row_cek["JMLDATA"];
	$kode_parent = $row_cek["KODE_PARENT"];
	
	return $kode_parent;
}



function insert_to_rolemenu($kode_menu)
{
	global $conn;
	$strsql="select distinct kode_role from mn_role";
	$q = oci_parse($conn,$strsql);
	oci_execute ($q);
	while($row=oci_fetch_array($q))
	{
		$kode_role=$row["KODE_ROLE"];
		$strsql="insert into mn_rolemenu(kode_role,kode_menu) values('$kode_role','$kode_menu')";
		$q1 = oci_parse($conn,$strsql);
		oci_execute($q1);
	}
}

$lookup=new Lookup();
if (isset($_REQUEST["kode_parent"])) $kode_parent=$_REQUEST["kode_parent"];
$onload="onLoad=menu_terakhir('$kode_parent')";
if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]) save();

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
<link rel="stylesheet" type="text/css" href="../datepicker.css"/>
<script language="JavaScript" src="../datepicker.js"></script>
<script language="JavaScript" src="../lib.js"></script>
<?php $xajax->printJavascript('xajax/'); ?>
<script>
function menu_terakhir(parent)
{
xajax_menu_terakhir(parent);
}

function cari_menu()
{
var nama_menu_cari=document.getElementById('nama_menu_cari').value;
xajax_cari_menu_bynama(nama_menu_cari,'kode_parent')
menu_terakhir(document.getElementById('kode_parent').value);
}
</script>
</head>
<body <?=$onload; ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
    <td width='98%'><table width="100%"  border="0" align="center" class="outside_border">
        <tr>
          <td valign="middle"><? require_once("../inc_banner.php"); ?></td>
        </tr>
        <? require_once("../inc_menu.php"); ?>
        <tr>
          <td class="title_banner" align="center">INSERT MENU</td>
        </tr>
        <tr>
          <td align="center" valign="top" >
		  <form action="" method="post" name="myform" id="myform">
          <table width="100%" border="0">
              <tr>
                <td width="1050" height="178" align="left" valign="top">
		    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		      <tr>
		        <td align="left" class="padding_3"><a onMouseOver="ddrivetip('Menu list', 40)" onMouseOut="hideddrivetip()" href="menu.php"> <img src="../images/xlist.png" alt="" width="32" height="32" border="0"> </a>
		          <!--a onMouseOver="ddrivetip('Insert new menu', 80)" onMouseOut="hideddrivetip()" href="menu_add.php">
						<img src="../images/xinsert.png" width="32" height="32" border="0">
					</a--></td>
		        </tr>
		      <tr>
		        <td align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1">
		          <tr align="left">
		            <td width="15%">Parent</td>
		            <td width="85%"><? 
					$lookup->name="kode_parent";
					$lookup->id="kode_parent";					
					$lookup->sql="select kode_menu,nama_menu from mn_menu order by nama_menu";
					$lookup->value_field="KODE_MENU";
					$lookup->list_field="KODE_MENU/NAMA_MENU";
					$lookup->default_value=$kode_parent;	
					$lookup->separator=" - ";	
					
					$lookup->onchange="menu_terakhir(this.value)";
					echo $lookup->dropdown();
					?></td>
		            </tr>
		          <tr align="left">
		            <td>Menu terakhir untuk parent ini</td>
		            <td><input name="last_menu" type="text" id="last_menu" value="<?=$last_menu; ?>" size="40" readonly>
		              urutan ke
		              <input name="last_urutan" type="text" class="numeric_textbox" id="last_urutan" value="<?=$last_urutan; ?>" size="3" readonly></td>
		            </tr>
		          <tr align="left">
		            <td>Menu baru</td>
		            <td><input name="nama_menu" type="text" id="nama_menu" size="40">
		              urutan ke
		              <input name="urutan" type="text" class="numeric_textbox" id="urutan" size="3"></td>
		            </tr>
		          <tr align="left">
		            <td>Link</td>
		            <td><input name="link" type="text" id="link" size="40"></td>
		            </tr>
		          <tr align="left">
		            <td>&nbsp;</td>
		            <td>
                    	<input name="iscud" type="checkbox" id="iscud" value="Y">Non Tree Menu
                    	<br>
		            	 <input type="checkbox" name="isnew" id="isnew" value="Y" checked disabled><img src="../images/new-blink.gif" />
                    </td>
		            </tr>
		          <tr align="left">
		            <td>&nbsp;</td>
		            <td><input name="btnsubmit" type="submit" id="btnsubmit2" value="Submit">
		              <input type="reset" name="Submit2" value="Reset"></td>
		            </tr>
		          </table></td>
		        </tr>
		      <tr>
		        <td align="left"><div class="xtable_frame"> </div></td>
		        </tr>
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
  </table>
  </td>
  </tr>
</table>
</body>
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>