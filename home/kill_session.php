<?
$msg="";
function kill_session()
{
	include ("../server.php");
	global $msg;

	if (isset($_POST["username"])) $username=$_POST["username"];
	if (isset($_POST["password"]))  $password=$_POST["password"];
	
	$sql="SELECT   COUNT ( * ) AS JML
				  FROM      TM_USER a
						 LEFT JOIN
							TM_SESSIONS b
						 ON b.USERNAME = a.USERNAME
						 LEFT JOIN
							HS_USERLOG c
						 ON C.ID_HSUSERLOG = b.ID_SESSIONS
				 WHERE   a.USERNAME = '$username' AND a.PASSWORD = '$password' AND LASTLOGOUT IS NULL";
				// echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$jml=$row["JML"];
	
	
	if ($jml==1)
	{
		$sql="BEGIN KILL_SESSION('$username'); END;";
		$q=oci_parse($conn,$sql);
		oci_execute($q);
		//echo $sql;
		$msg="Remote login berhasil";
		header("location:../index.php");
	}
	else
	{
		$msg="Tidak bisa remote login";
	}
}
	
if (isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") kill_session();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<link rel="icon" href="../images/icon.gif">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="icon" href="../favicon.ico">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width='1%' background="../../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y">&nbsp;</td>
        <td width='98%'><table width="100%"  border="0" class="outside_border">
          <tr>
            <td align="center" valign="middle"><? include("../inc_banner.php"); ?></td>
          </tr>
          <tr>
          	<td align="center" valign="middle" class="title_banner">REMOTE LOGIN MONITORING SERVER</td>
          </tr>
          <tr>
            <td align="center" valign="middle">
            <form action="" method="post" name="kill_session" id="kill_session">
            	<table>
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
                        <td><input type="submit" name="btnsubmit" id="btnsubmit" value="Proses" ></td>
                    </tr>
                </table>
            </form>
            </td>
          </tr>
          <? include("../inc_footer.php"); ?>
        </table></td>
        <td width='1%' background="../../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y">&nbsp;</td>
      </tr>
    </table>	
	</td>
  </tr>
</table>
</body> 
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>