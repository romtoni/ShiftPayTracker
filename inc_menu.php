<?
include("server.php");
//include("ceksession.php");
//session_start();

$kode_role=$_SESSION["server_role"];
//echo $kode_role;
$c_ktr=isset($_SESSION["server_kantor"]);
$sql="select 
			r.*,
			m.nama_menu,
			m.link,
			m.isnew
		from 
			mn_rolemenu r 
		left join 
			mn_menu m on m.kode_menu=r.kode_menu
		where 
			r.kode_role='$kode_role'
			and m.kode_parent is null 
			and isselect='Y' 
		order by m.urutan";
$q=oci_parse($conn,$sql);
oci_execute($q);
$data="";
while($row=oci_fetch_assoc($q))
{
	$isnew=$row["ISNEW"];
	if ($isnew == "") $nama_menu=$row["NAMA_MENU"];
	else 
	{
	
		$nama_menu=$row["NAMA_MENU"]."<img src=\"../images/new-blink.gif\" vspace='10px'/>";
	}
	
	$link=$row["LINK"];
	$data.="<a href='$link' class='menu_utama'>$nama_menu<a>  ";
}
?>
<tr><? require_once("../inc_bawahbanner.php"); ?></tr>
<tr>
	<td align="center" class="menu_bar" height="26">
 	<?php echo $data; ?>
    <a href='../logout.php' class="menu_utama  tebal" style="color:#FF0">Logout (<?=$_SESSION["server_user"];?>)</a>
    <br style='clear: left' />
    </td>
</tr>
<tr></tr>


   
     
     

