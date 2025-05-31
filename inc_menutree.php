<?php
function display_menu($kode_parent)
{
global $conn;
global $data;
global $no;
$kode_role=$_SESSION["server_role"];
//$kode_role=1;
$strsql="select r.*,
				m.nama_menu,
				m.link,
				m.kode_parent,
				m.isnew 
		from 
			mn_rolemenu r  
		left join mn_menu m on m.kode_menu=r.kode_menu
		where 
			m.kode_parent='$kode_parent' 
			and r.isselect='Y' 
			and r.kode_role='$kode_role' 
			and iscud='N' 
		order by m.urutan";	
//echo $strsql;
$q=oci_parse($conn,$strsql) or die(mysql_error());
oci_execute($q);
$data="";
$no=0;
while($row=oci_fetch_array($q))
{
	$isnew=$row["ISNEW"];
		 
	if ($isnew == "") $nama_menu=$row["NAMA_MENU"];
	else $nama_menu=$row["NAMA_MENU"]."<img src='../images/new-blink.gif' />";//" <blink><em><strong><font color=\"red\">NEW</font></strong></em></blink>";
	 
	$kode_menu=$row["KODE_MENU"];
	$kode_parent=$row["KODE_PARENT"]; 
	$link=$row["LINK"];
	$no++;
	if($link=="")  $data.="d.add($kode_menu,0,'$nama_menu');".chr(13).chr(10);
	else $data.="d.add($kode_menu,0,'$nama_menu','$link');".chr(13).chr(10);
	if($kode_menu!="")display_child($kode_menu); 
}
echo $data;
}


function display_child($kode_parent)
{
global $conn;
global $data;
$kode_role=1;
$kode_role=$_SESSION["server_role"];
$strsql="select count(*) as jumlah from mn_rolemenu r
		left join mn_menu m on m.kode_menu=r.kode_menu
		where m.kode_parent='$kode_parent' and r.isselect='Y' and
		r.kode_role='$kode_role' and iscud='N' ";
$q=oci_parse($conn,$strsql) or die(ocierror());
oci_execute($q);
$row=oci_fetch_array($q);
$nrow=$row["JUMLAH"];
if($nrow>0) { 
	//$strsql="select * from mn_menu where kode_parent='$kode_parent' and isselect='Y' order by urutan";
    $strsql="select 
				r.*,
				m.nama_menu,
				m.link,
				m.kode_parent, 
				m.isnew 
			from 
				mn_rolemenu r  
			left join mn_menu m on m.kode_menu=r.kode_menu
			where 
				m.kode_parent='$kode_parent' 
				and r.isselect='Y' 
				and r.kode_role='$kode_role'
				and m.iscud='N'
			order by m.urutan";
	//echo $strsql;
	$q=oci_parse($conn,$strsql) or die(ocierror());
	oci_execute($q);
	$no="";
	while($row=oci_fetch_array($q)) 
	{
		 $no++;
		 $isnew=$row["ISNEW"];
		 
		 if ($isnew == "") $nama_menu=$row["NAMA_MENU"];
		 else $nama_menu=$row["NAMA_MENU"]."<img src='../images/new-blink.gif' />";
		 
		 $kode_menu=$row["KODE_MENU"];
		 $kode_parent1=$row["KODE_PARENT"];
		 $link=$row["LINK"];
		 
	     if($link=="")  $data.="d.add($kode_menu,$kode_parent,'$nama_menu');".chr(13).chr(10);
	     else $data.="d.add($kode_menu,$kode_parent,'$nama_menu','$link');".chr(13).chr(10);
		 display_child($kode_menu);
	}
}
}

?>