<?
include("../auth.php");
include("../server.php");
include("../lib.php");

//session_start();
$msg="";
function validasi()
{
	global $conn;
	global $msg,$class;
	$ret=true;
	$msg="";
	$id_user=$_SESSION["server_id_user"];
	$PWD=$_POST["pwd0"];
	$sql="SELECT   a.USERNAME
			  FROM     V_USERS_ONTHEFLY a
			 WHERE   a.ID_USER = '$id_user' AND a.hasil_decrypt = '$PWD'";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$USER_ID0=$row["USERNAME"];
	if($USER_ID0=="")
	{
	  $msg="Password lama tidak sesuai, gagal mengganti password...";
	  $class="merah_tebal";
	  $ret=false;
	} 
 
	$pwd1=$_POST["pwd1"];
	$pwd2=$_POST["pwd2"];
	if($pwd1=="" or $pwd2=="") 
	{
	  $msg="Password tidak boleh kosong!";
	  $class="merah_tebal";
	  $ret=false;
	}

	if($pwd1!=$pwd2) 
	{
	  $msg="Konfirmasi password baru tidak tepat!";
	  $class="merah_tebal";
	  $ret=false;
	}
	
return $ret;
}


function save()
{
	global $conn;
	global $msg,$class;
	
	$id_user=$_SESSION["server_id_user"];
	$PWD=$_POST["pwd1"];
	
	$sql = "BEGIN VC_CHANGE_PASS( $id_user, '$PWD'); END; ";
	
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$msg="Password berhasil dirubah!";
	$class="hijau_tebal";
}

if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") 
{
  $ok=validasi();
  if($ok) save(); 
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
<script language="JavaScript" src="../lib.js"></script>
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
        <? require_once("../inc_bawahbanner.php"); ?>
        <? require_once("../inc_menu.php"); ?>
        <tr>
        	<td align="center" class="title_banner">GANTI PASSWORD</td>
        </tr>
        <tr>
          <td align="center" valign="top" ><form action="" method="post" name="myform" id="myform">         
				    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left"><table width="300"  border="0" align="center" cellpadding="2" cellspacing="2">
                          <tr align="left">
                            <td width="51%" align="left">Password lama </td>
                            <td width="3%" align="left">:</td>
                            <td width="46%"><input name="pwd0" type="password" id="pwd0" size="12" ></td>
                          </tr>
                          <tr align="left">
                            <td align="left">Password baru </td>
                            <td align="left">:</td>
                            <td><input name="pwd1" type="password" id="pwd1" size="12"></td>
                          </tr>
                          <tr align="left">
                            <td align="left">Ketik ulang password baru </td>
                            <td align="left">:</td>
                            <td><input name="pwd2" type="password" id="pwd2" size="12"></td>
                          </tr>
                          <tr align="left">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><input name="btnsubmit" type="submit" id="btnsubmit" value="Submit">
                                <input type="reset" name="Reset" value="Reset"></td>
                          </tr>
                          <tr align="center">
                            <td colspan="3" class='<?=$class; ?>'><?=$msg; ?></td>
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
</table>
</body>
<? 
if (isset($_GET["msg"])) $msg=$_GET['msg'];
 if($msg!="") echo "<script>alert('$msg');</script>"; 
?>
</html>