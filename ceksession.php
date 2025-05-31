<?
include ("server.php");
global $conn;

//$url=$_SERVER['REQUEST_URI'];
//header("Refresh: 5; URL=\"" . $url . "\""); // redirect in 5 seconds
$now = time(); // Checking the time now when home page starts.
//echo $now;
$waktusesi=$_SESSION['expire'];
if ($now > $waktusesi) 
{	
	$strsql="select ID_SESSIONS, USERNAME from TM_SESSIONS where WAKTUSESI='$waktusesi'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$username=$row["USERNAME"];
	$id=$row["ID_SESSIONS"];
	
	
	$lastlogout=date("Y-m-d H:i");
	$strsql="update HS_USERLOG set LASTLOGOUT=to_date('$lastlogout','yyyy-mm-dd HH24:MI:SS'), ISLOGOUT='Y',  ISLOGIN='N' where ID_HSUSERLOG='$id' and USERNAME='$username'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	$strsql="DELETE FROM TM_SESSIONS where ID_SESSIONS='$id' and USERNAME='$username'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);

	oci_close($conn);

  	session_destroy();
	header("location:../home/session_expired.php");
	//header("location:../.");
}
else
{
	$_SESSION['start'] = time(); // Taking now logged in time.
    // Ending a session in 30 minutes from the starting time.
    $_SESSION['expire'] = $_SESSION['start'] + (15 * 60); // 15 menit	
}
?>