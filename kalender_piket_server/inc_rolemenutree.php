<?
global $kode_role;
$kode_role=$_REQUEST["kode_role"];
//echo $kode_role;

function display_role_menu($kode_role)
{
global $conn;
global $data;
global $no;
$strsql="select NAMA_MENU, KODE_MENU, KODE_PARENT, ISSELECT from v_menu_role 
where kode_role='$kode_role' and kode_parent is null order by urutan";
$q=oci_parse($conn,$strsql) or die(mysql_error());
oci_execute($q);
$data="";
$no=0;
while($row=oci_fetch_array($q)) {
 $nama_menu=$row["NAMA_MENU"];
 $kode_menu=$row["KODE_MENU"];
 $kode_parent=$row["KODE_PARENT"];
 $isselect=$row["ISSELECT"];
 if($isselect=="Y")$checked="checked";
 else $checked=""; 
 $no++;
 $cb="<input type=\"checkbox\" value=\"Y\" name=\"x$kode_menu\" id=\"$kode_menu\" $checked>";
 $data.="x.add($kode_menu,0,'$cb$nama_menu');".chr(13).chr(10);
 display_role_child($kode_menu,$kode_role);
}
echo $data;
}


function display_role_child($kode_parent,$kode_role)
{
global $conn;
global $data;
$strsql="select count(*) as jumlah from v_menu_role where kode_parent='$kode_parent' and kode_role='$kode_role'";
$q=oci_parse($conn,$strsql) or die(ocierror());
oci_execute($q);
$row=oci_fetch_array($q);
$nrow=$row["JUMLAH"];
if($nrow>0) 
	{ 
	$strsql="select NAMA_MENU, KODE_MENU, KODE_PARENT, 
	ISSELECT from v_menu_role where kode_parent='$kode_parent'
	and kode_role='$kode_role' order by urutan";
	$q=oci_parse($conn,$strsql) or die(ocierror());
	oci_execute($q);
	while($row=oci_fetch_array($q)) 
		{
		 $no++;
		 $kode_menu=$row["KODE_MENU"];
		 $nama_menu=$row["NAMA_MENU"];
		 $kode_parent1=$row["KODE_PARENT"];
		 $isselect=$row["ISSELECT"];
 		 if($isselect=="Y")$checked="checked";
 		 else $checked=""; 
         $cb2="<input type=\"checkbox\" value=\"Y\" name=\"x$kode_menu\" id=\"$kode_menu\" $checked>";		 
		 $data.="x.add($kode_menu,$kode_parent,'$cb2$nama_menu');".chr(13).chr(10);
		 display_role_child($kode_menu,$kode_role);
		}
	}
}
?>
<link rel="StyleSheet" href="dtree.css" type="text/css" />
<script type="text/javascript" src="dtree.js"></script>

<table width="100%" border="0">
  <tr align="center" class='tebal'>
    <td width="100%" align="left">
	<a href="javascript: x.openAll();">Expand</a> | <a href="javascript: x.closeAll();">Collapse</a></td>
  </tr>
  <tr class='tebal'>
    <td colspan="2">
      <div class="dtree">
	  <script type="text/javascript">
		x = new dTree('x');
		x.add(0,-1,'Role Menu');
		<?
		display_role_menu($kode_role); 
		?>
		document.write(x);
		</script>
    	</div>	
	</td>
  </tr>
</table>