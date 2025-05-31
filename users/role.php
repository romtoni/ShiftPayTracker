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
$nama_role=$_POST["nama_role"];
$isselect_all=$_POST["isselect_all"];
if($isselect_all=="")$isselect_all='N';

$strsql="begin sp_insert_role('$nama_role','$isselect_all'); end;";
if($nama_role!="") 
	{
	//echo $strsql;
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	}
}
if(isset($_POST["btnsubmit"])  and $_POST["btnsubmit"]!="") save();

function display()
{
global $conn;

$strsql="select NAMA_ROLE, KODE_ROLE from MN_ROLE order by KODE_ROLE";
$q=oci_parse($conn,$strsql);
oci_execute($q);
$data="";
$no="";
while ($row=oci_fetch_assoc($q)) 
  {
    $no++;
	if(fmod($no,2)==0) $class="baris2"; else $class="baris1";
	$nama_role=$row["NAMA_ROLE"];
	$kode_role=$row["KODE_ROLE"];

	$pg="role_del.php?kode_role=$kode_role";	
	$adel="<a href='javascript:confirm_del(\"$pg\");' onmouseover=\"ddrivetip('Delete Role', 50)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_del.png' border='0'></a>";	
	$aedit="<a href='role_edit.php?kode_role=$kode_role' onmouseover=\"ddrivetip('Edit Role', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_edit.png' border='0'></a>&nbsp;";		
	$data.= "<tr class='$class'>"; 
	$data.= "<td align='center'>$no</td>";
	$data.= "<td align='left'>$nama_role</td>";
	$data.= "<td align='center'>$aedit $adel</td></tr>";
  }
  echo $data;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="icon" href="../favicon.ico">
<link rel="stylesheet" type="text/css" href="../tooltip.css" />
<script language="JavaScript" src="../tooltip.js"></script>
<link rel="stylesheet" href="../example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="../example-print.css" TYPE="text/css" MEDIA="print">
<script type="text/javascript" src="../tabber_nav.js"></script>
<script language="JavaScript" src="../lib.js"></script>
<script type="text/javascript" src="../tabber.js"></script>
<SCRIPT LANGUAGE="JavaScript" src="../sms.js"></script>
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
                <td align="center" valign="top" class="title_banner" width="100% ">USER MANAGEMENT &gt; ROLE</td>
              </tr>        
        <tr>
          <td align="center" valign="top" ><form name="form1" method="post" action="">
            <table width="100%" border="0">
              <tr>
                <td width="125" rowspan="2" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                <td width="891" align="left" valign="top" class="left_border">
				 <table width="38%">
                  <tr class="table_header">
                    <td  width="20px" align="center">No</td>
                    <td  width="440px" align="left">Role </td>
                    <td  width="100px" align="center" >Action</td>
                  </tr>
                  <tr >
                    <td align="center">&nbsp;</td>
                    <td align="left" >
					  <input name="nama_role" type="text" id="nama_role" size="20">
                      <input type="checkbox" name="isselect_all" value="Y" id="isselect_all">Select All					</td>
                    <td align="center"><input name="btnsubmit" type="submit" id="btnsubmit" value="Submit"></td>
                  </tr>
                  <?  display(); ?>
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
</table>
</body>
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>