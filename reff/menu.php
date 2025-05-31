<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
$kode_parent="";
function display()
{
global $conn;
global $data;
$kode_parent="";
if (isset($_POST["kode_parent"])) $kode_parent=$_POST["kode_parent"];
if($kode_parent=='') $kode_parent=$_SESSION["kode_parent"]; 
if($kode_parent!='') 
$strsql="select m.*
           from mn_menu m
		   where m.kode_parent='$kode_parent'
   	       order by m.urutan";
else
$strsql="select m.*
           from mn_menu m
		   where m.kode_parent is null 
   	       order by m.urutan";

$q=oci_parse($conn,$strsql) or die(mysql_error());
oci_execute($q);
$data="";
$no=0;
if (isset($_POST["kode_parent"])) $kode_parent_top=$_POST["kode_parent"];
while($row=oci_fetch_array($q)) {
 $no++;
 if(fmod($no,2)==0) $class="baris1"; else $class="baris2"; 
 $kode_menu=$row["KODE_MENU"];
 $kode_parent=$row["KODE_PARENT"];
 $iscud=$row["ISCUD"];
 if($iscud=='Y') $xiscud="<img src='../images/tick.png' border='0'>";
 else $xiscud="";
 $link=$row["LINK"];
 $urutan=$row["URUTAN"];
 $isnew=$row["ISNEW"];
  if ($isnew == "") $nama_menu=$row["NAMA_MENU"];
	else 
	{
	
		$nama_menu=$row["NAMA_MENU"]."<img src=\"../images/new-blink.gif\" />";
	}

 //$menu="$kode_menu : $nama_menu";
 
 $menu="<a href='menu_add.php?kode_parent=$kode_menu'>$kode_menu : $nama_menu</a>";
 
 //if($kode_parent=="")$menu="<a href='menu_add.php?kode_parent=$kode_menu'>$menu</a>";
 
 $adel="<a href='javascript:del(\"$kode_menu\");'><img src='../images/img_del.png' border='0'></a>";	
 $aedit="<a href='menu_edit.php?kode_menu=$kode_menu'><img src='../images/img_edit.png' border='0'></a>&nbsp;";
 $action="$aedit $adel";
 $data.="<tr class='$class'>
		  <td>$menu</td>
		  <td>$link</td>		  
		  <td align='center'>$urutan</td>		  		  
		  <td align='center'>$xiscud</td>		  
  	      <td align='center'>$action</td>		  		  		  		  
	     </tr>";
 display_child_menu($kode_menu);
}

echo $data;
}


function display_child_menu($kode_parent)
{
global $conn;
global $data;
global $no;
if (isset($_POST["kode_parent"])) $kode_parent_top=$_POST["kode_parent"];
static $spasi;
$strsql="select nvl(count(*),0) as jumlah
           from mn_menu
		   where kode_parent='$kode_parent'";
$q=oci_parse($conn,$strsql) or die(ocierror());
oci_execute($q);
$row=oci_fetch_array($q);
$n=$row["JUMLAH"];
if($n>0)  $spasi+=2; 
$sps=spasi($spasi); 

$strsql="select m.*
           from mn_menu m
		   where m.kode_parent='$kode_parent' 
   	       order by m.urutan";
$q=oci_parse($conn,$strsql) or die(mysql_error());
oci_execute($q);
while($row=oci_fetch_array($q)) {
 $no++;
 if(fmod($no,2)==0) $class="baris1"; else $class="baris2"; 
 $kode_menu=$row["KODE_MENU"];
 $kode_parent=$row["KODE_PARENT"];
 $iscud=$row["ISCUD"];
 if($iscud=='Y') $xiscud="<img src='../images/tick.png' border='0'>";
 else $xiscud="";
 $link=$row["LINK"];
 $urutan=$row["URUTAN"];
 $isnew=$row["ISNEW"];
  if ($isnew == "") $nama_menu=$row["NAMA_MENU"];
	else 
	{
	
		$nama_menu=$row["NAMA_MENU"]."<img src=\"../images/new-blink.gif\" />";
	}
 
 $menu="<a href='menu_add.php?kode_parent=$kode_menu'>$kode_menu : $nama_menu</a>";
 
 //if($kode_parent=="")$menu="<a href='menu_add.php?kode_parent=$kode_menu'>$menu</a>";

 $adel="<a href='javascript:del(\"$kode_menu\");'><img src='../images/img_del.png' border='0'></a>";	
 $aedit="<a href='menu_edit.php?kode_menu=$kode_menu'><img src='../images/img_edit.png' border='0'></a>&nbsp;";
 $action="$aedit $adel";
 
 $data.="<tr class='$class'>
		  <td>$sps $menu</td>
		  <td>$link</td>		  
		  <td align='center'>$urutan</td>		  		  
		  <td align='center'>$xiscud</td>		  
  	      <td align='center'>$action</td>		  		  		  		  
	     </tr>"; 
 display_child_menu($kode_menu);
}
if($n>0) $spasi-=2; //kurangi spasi lagi untuk menetralkan posisi parent
}

if (isset($_POST["kode_parent"])) $kode_parent=$_POST["kode_parent"];
if($kode_parent=='' and (isset($_POST["kode_parent"]) and $_POST["btnsubmit"]=='')) 
{
	if (isset($_SESSION["kode_parent"])) $kode_parent=$_SESSION["kode_parent"]; 
}
else $_SESSION["kode_parent"]=$kode_parent;
$lookup=new Lookup();
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
<script language="JavaScript" src="../lib.js"></script>
<script>
function del(kode_menu)
{
var isok=confirm("Anda yakin?");
if (isok==true) 
  {
  	
	location.href="menu_del.php?kode_menu="+kode_menu;
  }
}
</script>
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
          <td><? require_once("../inc_banner.php"); ?></td>
        </tr>
        <? require_once("../inc_menu.php"); ?>
        <tr>
          	<td class="title_banner" align="center">MENU EDITOR</td>
        </tr>
        <tr>
          <td align="center" valign="top" ><form action="" method="post" name="myform" id="myform">
            <table width="100%" border="0">
              <tr>
                <td width="1050" height="178" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" class="padding_3"><a 
						onMouseOver="ddrivetip('Insert menu', 55)" 
				        onMouseOut="hideddrivetip()"   					
					href="menu_add.php"><img src="../images/xinsert.png" width="32" height="32" border="0"></a></td>
                  </tr>
                  <tr>
                    <td align="left"><table width="100%"  border="0" cellspacing="1" cellpadding="1">
                      <tr align="left">
                        <td width="8%">Top parent</td>
                        <td width="92%"><? 
					$lookup->name="kode_parent";
					$lookup->id="kode_parent";					
					$lookup->sql="select kode_menu,nama_menu from mn_menu where kode_parent is null order by urutan";
					$lookup->value_field="KODE_MENU";
					$lookup->list_field="KODE_MENU/NAMA_MENU";
					$lookup->default_value=$kode_parent;	
					$lookup->separator=" - ";	
					
					$lookup->onchange="menu_terakhir(this.value)";
					echo $lookup->dropdown();
					?></td>
                      </tr>
                      <tr align="left" class="table_color">
                        <td>&nbsp;</td>
                        <td id='cust_type'><input name="btnsubmit" type="submit" id="btnsubmit" value="Display"></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="left"><div class="xtable_frame">
                      <table>
                        <tbody>
                          <tr class="table_header">
                            <td align="left">Menu</td>
                            <td align="left">Link</td>
                            <td align="left">Urutan</td>
                            <td align="left">Non Tree Menu</td>
                            <td align="center">Action</td>
                          </tr>
                          <? display(); ?>
                        </tbody>
                      </table>
                    </div></td>
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
  </table>
  </td>
  </tr>
</table>
</body>
</html>