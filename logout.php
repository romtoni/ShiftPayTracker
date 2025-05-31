<?
	session_start();
	include("server.php");
	
	$username=$_SESSION["server_user"];
	$id=$_SESSION["id"];
	$lastlogout=date("Y-m-d H:i");			
	$strsql="update HS_USERLOG set LASTLOGOUT=to_date('$lastlogout','yyyy-mm-dd HH24:MI:SS'), ISLOGOUT='Y',  ISLOGIN='N' 
			 where ID_HSUSERLOG='$id' and username='$username'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	$strsql="DELETE FROM TM_SESSIONS where ID_SESSIONS='$id' and  username='$username'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
		
	oci_close($conn);
	
	session_destroy();
	header("location:index.php");
?>