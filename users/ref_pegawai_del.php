<? 
include("../auth.php"); 
include("../server.php");
include("../lib.php"); 
include("../auth_role.php"); 

$stambuk=$_GET["stambuk"];
$strsql="delete from REF_PEGAWAI where STAMBUK='$stambuk'";
$q=oci_parse($conn,$strsql);
oci_execute($q);
header("location:ref_pegawai.php");
?>