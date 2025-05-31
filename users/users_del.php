<? 
include("../auth.php"); 
include("../server.php");
include("../lib.php"); 
include("../auth_role.php"); 

$id_user=$_GET["id_user"];
$strsql="delete from TM_USER where ID_USER='$id_user'";
$q=oci_parse($conn,$strsql);
oci_execute($q);
header("location:users.php");
?>