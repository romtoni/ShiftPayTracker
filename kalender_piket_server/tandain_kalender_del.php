<? 
include('../auth.php');
include("../server.php");
include("../lib.php");
include('../auth_role.php');
include("../classes/class.lookup.php");
include("xajax_common.php");

$id=$_GET["id"];
$strsql="delete from KALENDER_PIKET where id='$id'";
$q=oci_parse($conn,$strsql);
oci_execute($q);
header("location:tandain_kalender.php");


?>