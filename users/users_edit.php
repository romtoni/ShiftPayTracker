<?
include("../auth.php");
include("../server.php"); 
include("../lib.php"); 
include("../auth_role.php");
include("../classes/class.lookup.php"); 

global $xusername;
$xid_user=$_GET["id_user"];
$msg="";
function save()
{
	global $conn;
	global $msg;
	$msg="";
	$username=$_POST["username"];
	$xid_user=$_POST["xid_user"];
	$password=$_POST["password"];
	$role=$_POST["role"];
	
	$sql = "BEGIN VC_UPDATE_USERS( $xid_user, '$username', '$password', $role); END; ";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	//$msg="Data Berhasil DiUbah";
	header("location:users.php");
}
function open()
{
	global $conn;
	global $xid_user;
	$data="";
	$sql="select a.*, b.nama_role from tm_user a
		  left join mn_role b on b.kode_role=a.kode_role";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$no=0;
		
	$lookup_role=new Lookup();
	$lookup_role->name="role";
	$lookup_role->id="role";					
	$lookup_role->sql="select * from mn_role";
	$lookup_role->value_field="KODE_ROLE";
	$lookup_role->list_field="NAMA_ROLE";
	$cktr="";
	while($row=oci_fetch_assoc($q)){
		$no++;
		if(fmod($no,2)==0) $class='baris1'; else $class='baris2';
		$id_user=$row["ID_USER"];
		$username=$row["USERNAME"];
		$password=$row["PASSWORD"];
		$kode_role=$row["KODE_ROLE"];
		$nama_role=$row["NAMA_ROLE"];	
		$adel="<a href='user_del.php?id_user=$id_user'><img src='../images/img_del.png' onclick='return confirm(\"Apakah anda Yakin??\")'></a>";
		$aedit="<a href='users_edit.php?id_user=$id_user'><img src='../images/img_edit.png'></a>";
		if($cktr!=""){
			$isi="$nktr ($cktr)";
		}
		else{
			$isi="";
		}
		$action="$aedit $adel";
		if($xid_user==$id_user){

			$username="<input name='username' type='text' id='username' size='20' value='$username'>";
			$password="<input name='password' type='password' id='password' size='20' value='$password'>";
			$lookup_role->default_value=$kode_role;			
			$nama_role=$lookup_role->dropdown();
			$action='<input type="submit" name="submit" id="submit" value="Submit">';
		}
		$data.="<tr class='$class'>
				<td align='center'>$no</td>
				<td>$username</td>
				<td>$nama_role</td>	
				<td>$password</td>					
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
<input type="hidden" name="xid_user" id="xid_user" value="<?=$xid_user?>">
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
                  <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                  <td width="891" align="left" valign="top" class="left_border"><table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="padding_3"><a href='users.php' onMouseOver="ddrivetip('Entry user', 80)" onMouseOut="hideddrivetip()"><img src="../images/xinsert.png" alt="" border='0'></a></td>
                    </tr>
                    <tr>
                      <td width="85%" valign="top"><table width="451" border="0" cellpadding="2" cellspacing="2">
                        <tr class="table_header">
                          <td width="21" align="center">No</td>
                          <td width="100">Username</td>
                          <td width="29">Role</td>
                          <td width="100">Password</td>
                          <td width="48" align="center">Action</td>
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