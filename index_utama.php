<?
include("server.php");

session_start();
global $msg;
//$db_user=$_GET["db_user"];
//if($db_user!='') $_SESSION["server_dbuser"]=$db_user; 

if(isset($_SESSION["server_error"])!="") $msg="Login ERROR...";
$error=isset($_GET["error"]);
$username=isset($_GET["username"]);
if($error==1) $msg="User $username masih dalam status login...";

$now = time();
function get_ip() 
{
	//Just get the headers if we can or else use the SERVER global
	if(function_exists('apache_request_headers'))
	{
		$headers = apache_request_headers();
	}
	else
	{
		$headers = $_SERVER;
	}

	//Get the forwarded IP if it exists
	if(array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) )
	{
		$the_ip = $headers['X-Forwarded-For'];

	}
	elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
	{
		$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
	}

	return $the_ip;
	
}
$ip=get_ip();
//$ip=$_SERVER["REMOTE_ADDR"];

$sqlautologin="select * from TM_SESSIONS where WAKTUSESI > '$now' and IP_ADDRESS='$ip'";
$qautologin=oci_parse($conn,$sqlautologin) or die("Query gagal" );
oci_execute($qautologin);
$rowautologin=oci_fetch_assoc($qautologin);
$username=$rowautologin["USERNAME"];
$password=$rowautologin["PASSWORD"];
$id_sessions=$rowautologin["ID_SESSIONS"];
if($username=="" and $password=="" and $id_sessions=="")
{
	$kode="logout";	
}
else
{
	$username=$username;
	$password=$password;
	$id_sessions=$id_sessions;
	$kode="autologin";
}

if($kode=="autologin")
{
	header("location:login.php?username=$username&password=$password&id_sessions=$id_sessions");
}
elseif($kode=="logout")
{
	//$ip=$_SERVER['REMOTE_ADDR'];
	$lastlogout=date("Y-m-d H:i");			
	$strsql="update HS_USERLOG set LASTLOGOUT=to_date('$lastlogout','yyyy-mm-dd HH24:MI:SS'), ISLOGOUT='Y',  ISLOGIN='N' 
			 where ip_address='$ip' and ISLOGIN ='Y'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	$strsql="DELETE FROM TM_SESSIONS where ip_address='$ip'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("inc_webtitle.php"); ?></title>
<link rel="icon" href="images/icon.gif">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="shortcut icon" href="favicon.ico" />
<style type="text/css">
<!--
.style1 {font-size: 18px}
.style2 {font-size: 18px; color: #000000; }
-->
</style>
</head>

<body>
<table width="100%"  border="0">
  <tr>
    <td align="center" valign="middle" >&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" >
    <form action="login.php" method="post" name="login" id="login">

<!-- ImageReady Slices (loginpage.psd) -->
<table id="Table_01" width="861" height="349" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="119">
			<!--<img src="images/loginpage_01.gif" width="861" height="119" alt="">-->
			<img src="images/loginpage_01_nologo.gif" width="861" height="119" alt="">
            </td>
	</tr>
	<tr>
		<td height="53">
			<img src="images/loginpage_02.gif" width="861" height="53" alt=""></td>
	</tr>
	<tr >
		<td height='156px' align="left" background="images/loginpage_03.gif" style="padding-left:50px">
        <table id="Table_" width="202" height="127" border="0" cellpadding="0" cellspacing="0">
		  <tr>
		    <td><img src="images/loginbox_01.gif" width="14" height="14" alt=""></td>
		    <td><img src="images/loginbox_02.gif" width="172" height="14" alt=""></td>
		    <td><img src="images/loginbox_03.gif" width="16" height="14" alt=""></td>
		    </tr>
		  <tr>
		    <td><img src="images/loginbox_04.gif" width="14" height="78" alt=""></td>
		    <td align="center" valign="middle" background="images/loginbox_05.gif"><table width="100%"  border="0" align="left" cellpadding="1" cellspacing="1" class="small_font">
		      <tr align="left">
		        <td width="31%">Username</td>
		        <td width="69%"><input name="username" type="text" id="username" size="15"></td>
		        </tr>
		      <tr align="left">
		        <td>Password</td>
		        <td><input name="password" type="password" id="password" size="15"></td>
		        </tr>
		      <tr align="left">
		        <td>&nbsp;</td>
		        <td><input type="submit" name="Submit" value="Login" ></td>
		        </tr>
		      </table></td>
		    <td><img src="images/loginbox_06.gif" width="16" height="78" alt=""></td>
		    </tr>
		  <tr>
		    <td><img src="images/loginbox_07.gif" width="14" height="35" alt=""></td>
		    <td><img src="images/loginbox_08.gif" width="172" height="35" alt=""></td>
		    <td><img src="images/loginbox_09.gif" width="16" height="35" alt=""></td>
		    </tr>
		  </table></td>
	</tr>
	<tr>
		<td height="160">
			<img src="images/loginpage_04.gif" width="861" height="160" alt=""></td>
	</tr>
	<tr bgcolor="#b5e61d"><td align="center"><a onmouseover="ddrivetip('Tidak bisa login? User lupa logout? Session stucked?', 160)" onmouseout="hideddrivetip()" href="home/kill_session.php" style="margin-left:20"><font color="#000000"><strong>Remote Login</strong></font></a></td></tr>
    
    <tr>
	  <!--<td align="center" BGCOLOR="#008000"><marquee truespeed onMouseOver="this.stop()" onMouseOut="this.start()"><font color="#FFFFFF"><strong>&copy; Copyright 2016 - Dept. Teknologi Informasi AJB Bumiputera 1912</strong></font></marquee></td>-->
	  <td align="center" BGCOLOR="#008000">&nbsp;</td>
  </tr>
</table>
<!-- End ImageReady Slices -->


    </form></td>
  </tr>
</table>
</body>
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>
