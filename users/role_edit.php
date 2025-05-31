<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
//session_start();
global $gkode_role;
$gkode_role=$_REQUEST["kode_role"];
$msg="";
////////////////////SAVE/////////////////////
function save()
{
global $conn;
$kode_role=$_POST["kode_role"];
update_master();
$strsql="select kode_menu from mn_rolemenu where kode_role='$kode_role'";
$q=oci_parse($conn,$strsql);
oci_execute($q);
while($row=oci_fetch_array($q)) {
	$kode_menu=$row["KODE_MENU"];
	$isselect=$_POST["x$kode_menu"];
	if($isselect=='') $isselect='N';
	update_menu($kode_role,$kode_menu,$isselect);
	$strsql="update MN_ROLEMENU set ISSELECT='$isselect' where KODE_ROLE='$kode_role' and kode_menu='$kode_menu'";
}
header("location:role.php");
}
if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") save();

function update_master()
{
global $conn;
$kode_role=$_POST["kode_role"];
$nama_role=$_POST["nama_role"];
$strsql="update mn_role set NAMA_ROLE='$nama_role' where kode_role='$kode_role'";

if($kode_role!="" and $nama_role!="") 
	{
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	}
}

function update_menu($kode_role,$kode_menu,$isselect)
{
global $conn;
$strsql="update MN_ROLEMENU set ISSELECT='$isselect' where KODE_ROLE='$kode_role' and kode_MENU='$kode_menu'";
//echo $strsql."<br>";
$q=oci_parse($conn,$strsql);
oci_execute($q);

}
////////////////////SAVE/////////////////////

function display()
{
global $conn;

$xkode_role=$_GET["kode_role"];
$strsql="select KODE_ROLE,NAMA_ROLE from MN_ROLE order by KODE_ROLE";
$q=oci_parse($conn,$strsql);
oci_execute($q);
$data="";
$no="";
while ($row=oci_fetch_assoc($q)) 
  {
    $no++;
	if(fmod($no,2)==0) $class="baris2"; else $class="baris1";
	$kode_role=$row["KODE_ROLE"];		
	$nama_role=$row["NAMA_ROLE"];	
	
	$pg="role_del.php?kode_role=$kode_role";	
	$adel="<a href='javascript:confirm_del(\"$pg\");' onmouseover=\"ddrivetip('Delete Role', 50)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_del.png' border='0'></a>";	
	$aedit="<a href='role_edit.php?kode_role=$kode_role' onmouseover=\"ddrivetip('Edit Role', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_edit.png' border='0'></a>&nbsp;";
	/*$amenu="<a href='role_menu.php?kode_role=$kode_role' onmouseover=\"ddrivetip('Edit Menu', 45)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_menu.png' border='0'></a>&nbsp;";
	$asave="<a href='' onmouseover=\"ddrivetip('Save Role', 45)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_ok.png' border='0'></a>&nbsp;";
	*/
	//$action="$aedit $adel";
	
	if($kode_role==$xkode_role) {
	  $nama_role="<input name='nama_role' type='text' id='nama_role' size='20' value='$nama_role'>";
  	  $submit='<input name="btnsubmit" type="submit" id="btnsubmit" value="Submit">';
	  /*$delete='<input name="btndelete" type="submit" id="btndelete" value="Delete">';
	  $menu='<input name="btnmenu" type="submit" id="btnmenu" value="Menu">';
	  $action="$submit $delete $menu";*/
	  $action="$submit";
	 }
	 else
	 $action="$aedit $adel";
			
	$data.= "<tr class='$class'>"; 
	$data.= "<td align='center'>$no</td>";
	$data.= "<td align='left'>$nama_role</td>";
	$data.= "<td align='center'>$action</td></tr>";
  }
  echo $data;
}
/*
function open_role_menu($kode_role)
{

}*/

function open()
{
global $conn,$nama_role;
$kode_role=$_REQUEST["kode_role"];
$strsql="select NAMA_ROLE, KODE_ROLE from MN_ROLE where KODE_ROLE='$kode_role'";
$q=oci_parse($conn,$strsql);
oci_execute($q);
$row=oci_fetch_assoc($q);
$nama_role=$row["NAMA_ROLE"];
//open_role_menu($kode_role);
}

open();
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
<style type="text/css">
<!--
.nobulletlist {		list-style: none;
}
-->
</style>
</head>
<body>
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
                    <td colspan="2" align="center" valign="top" class="title_banner"> ROLE EDIT</td>
                    </tr>
        <tr>
          <td align="center" valign="top" >
          <form name="form1" method="post" action="">
          <input type='hidden' name="kode_role" value="<?=$_GET["kode_role"]; ?>" >
            <table width="100%" border="0">
              <tr>
                <td width="125" height="178" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                <td width="891" align="left" valign="top" class="left_border"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top"><a href="role.php" onMouseOver="ddrivetip('Insert Role', 50)" onMouseOut="hideddrivetip()"><img src="../images/xinsert.png" width="32" height="32" border="0"></a></td>
                    <td align="left" valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="38%" align="left" valign="top">
					  <table width="100%" cellpadding="2"  cellspacing="1">
                        <tr class="table_header">
                          <td  width="20px" align="center">No</td>
                          <td  width="450px" align="left">Role </td>
                          <td  width="100px" align="center" >Action</td>
                        </tr>
                        <?  display(); ?>
                      </table>					</td>
                    <td width="62%" align="left" valign="top">
					  <table width="90%" align="center" cellpadding="1" cellspacing="1">
                        <tr class="tebal">
                          <td width="16%">Role Name</td>
                          <td width="2%" align="center">:</td>
                          <td width="82%"><?=$nama_role; ?></td>
                        </tr>
                        <tr>
                          <td class="tebal" valign="top">Menu Name</td>
                          <td class="tebal" align="center" valign="top">:</td>
                          <td ><? 
						  		$kode_role=$gkode_role;
								//echo $kode_role;
						  		include("inc_rolemenutree.php");
							  ?>						 </td>
                       </tr>
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
    </table>    </td>
  </tr>
</body>
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>