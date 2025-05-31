<? 
include("../auth.php"); 
include("../server.php");
include("../lib.php"); 
include("../auth_role.php"); 

$kode_pelanggan=$_GET["kode_pelanggan"];
$userlogin=$_SESSION["server_user"];
$strsql="BEGIN SP_SERVER_DELETE_PELANGGAN('$kode_pelanggan', '$userlogin'); END;";
$q=oci_parse($conn,$strsql);
oci_execute($q);
header("location:server_setup_pelanggan.php");
?>