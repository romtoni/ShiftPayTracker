<?
	$url=get_script().".php";
	//echo $url;
	$kode_role=$_SESSION["server_role"];
	if ($kode_role != "")
	{
		$strsql="select m.link from mn_rolemenu r
				left join v_menu m on m.kode_menu=r.kode_menu
				where r.kode_role='$kode_role' and m.nama_file='$url' and r.isselect='Y'";
				//echo $strsql;
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
		$row=oci_fetch_array($q);
		$link=$row["LINK"];
		
		if($link=='') header("location:../home/restricted.php");
	}
?>