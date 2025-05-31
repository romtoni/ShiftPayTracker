<? 
include("../auth.php"); 
include("../server.php");
include("../lib.php"); 
include("../auth_role.php"); 

$id_akses=$_GET["id_akses"];
$ip_address=$_GET["ip_address"];
$userlogin=$_SESSION["server_user"];

$strsql="begin SP_SERVER_REVOKE_VA_AKSES ('$id_akses', '$ip_address', 'DETAIL', 'T', '$userlogin'); end;";
$q=oci_parse($conn,$strsql);
oci_execute($q);
header("location:server_create_akses_tagihan_add_detail.php?id_akses=$id_akses");
?>