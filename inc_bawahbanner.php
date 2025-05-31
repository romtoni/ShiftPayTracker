<?
//RUNNING TEXT MAINTENANCE
$sql="	select 
			count(*) as jumlah
		from
			TM_PENGUMUMAN";
$q=oci_parse($conn,$sql);
oci_execute($q);
$row=oci_fetch_array($q);
$jumlah_data = $row["JUMLAH"];

if ($jumlah_data > 0)
{
	$sql="	select 
				MENU_UTAMA, 
				MENU_PARENT,
				MENU_CHILD,
				KETERANGAN 
			from
				TM_PENGUMUMAN";
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$txt_pengumuman="";
	while($row=oci_fetch_array($q))
	{
		$menu_utama = $row["MENU_UTAMA"];
		$menu_parent = $row["MENU_PARENT"];
		$menu_child = $row["MENU_CHILD"];
		$keterangan = $row["KETERANGAN"];
		
		if ($menu_child == "") $menu = $menu_utama." -> ".$menu_parent;
		else $menu = $menu_child;
		//if ($menu_child != "") $menu_child = " -> ".$menu_child;
		
		$txt_pengumuman .= "PENGUMUMAN : Mohon maaf menu <strong>$menu</strong> saat ini <strong>$keterangan</strong>.<br />";
	}
	
	
	 echo "<tr bgcolor='red'>
			<td align='center' valign='middle' font='Verdana'>
				<h3><strong><font color='white'>$txt_pengumuman</font></strong></h3>
			</td>
		  </tr>";
	
}


?>
<td class="bold" align="left" valign="middle" face="Arial">
	<table width="100%" border="0" bgcolor="#E5E4D7">
		<tr>
        	<td width="32%"><? echo strtoupper("<strong>Monitoring Server</strong>");?></td>
            <td width="18%">&nbsp;</td>
		  <td align="right" width="36%"><!--script language="JavaScript">document.write(tanggallengkap);</script--></td>
            <td width="14%" align="right" id="tempatjam"><strong>[LIVE]</strong></td>
		</tr>
	</table>
</td>