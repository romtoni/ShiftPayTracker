<? 
include("../auth.php"); 
include("../server.php");
include("../lib.php"); 

//cek auth_role
$url=get_script().".php";
//echo $url;
$kode_role=$_SESSION["server_role"];
$strsql="select m.link from mn_rolemenu r
		left join v_menu m on m.kode_menu=r.kode_menu
		where r.kode_role='$kode_role' and m.nama_file='$url' and r.isselect='Y'";
		//echo $strsql;
$q=oci_parse($conn,$strsql);
oci_execute($q);
$row=oci_fetch_array($q);
$link=$row["LINK"];
if($link=='') header("location:../home/restricted.php");
//cek auth_role
else
{
	$kode_role=$_GET["kode_role"];
	$strsql="delete from MN_ROLE where KODE_ROLE='$kode_role'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	header("location:role.php");
}
?>