<? 
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../classes/class.lookup.php");
include("xajax_common.php");

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
	function del_child($kode_parent)
	{
	global $conn;
	$strsql="select kode_menu from mn_menu 
			 where kode_parent='$kode_parent'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	while($row=oci_fetch_array($q)) {
		$kode_menu=$row["KODE_MENU"];
		$strsql2="delete from mn_menu where kode_menu='$kode_menu'";
		$q2=oci_parse($conn,$strsql2) or die(mysql_error());
		oci_execute($q2);
		
		//delete MN_ROLEMENU
		$strsql="delete from mn_rolemenu where kode_menu='$kode_menu'";
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
	
		del_child($kode_menu);
		}
	}	
	
	$kode_menu=$_GET["kode_menu"];
	$strsql="delete from mn_menu where kode_menu='$kode_menu'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	//delete MN_ROLEMENU//
	$strsql="delete from mn_rolemenu where kode_menu='$kode_menu'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	del_child($kode_menu);
	
	header("location:menu.php");
}
?>