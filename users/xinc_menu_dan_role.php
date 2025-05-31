<?
function display_menu_role($sub_menu)
{
 	include("../server.php"); 
	//session_start();
  	set_time_limit(180);  
  	$objResponse = new xajaxResponse(); 
	
	if ($sub_menu != "") $cond1=" kode_menu='$sub_menu'";
	$cond = $cond1;
	if ($cond !="") $cond=" where $cond";

	$sql="select count(*) as jumlah from V_MENU_ROLE_ALL $cond";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$jumlah=$row["JUMLAH"];
	
	
	$sql="select * from V_MENU_ROLE_ALL $cond";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$no=0;
	
	$data="<tbody id='mytable'>";
	while($row=oci_fetch_assoc($q))
	{
		$no++;
		if(fmod($no,2)==0) $class='baris1'; else $class='baris2';
		$kode_menu=$row["KODE_MENU"];
		$nama_menu=$row["NAMA_MENU"];
		$link=$row["LINK"];
		$kode_role=$row["KODE_ROLE"];
		$nama_role=$row["NAMA_ROLE"];
		$isselect=$row["ISSELECT"];
		
		if ($isselect == "Y") { $checked = "checked"; $black='black'; }
		else if ($isselect == "N") { $checked = ""; $black=''; }
	
		$data.="<tr class='$class' >
						<td align='center' bgcolor='$black'>
							<input type='checkbox' id='cek$no' name='cek$no' value='$kode_role' $checked>
							<input type='hidden' id='kode_role$no' name='kode_role$no' value='$kode_role' $checked>
							<input type='hidden' id='kode_menu$no' name='kode_menu$no' value='$kode_menu' $checked>
						</td>
						<td align='center' >$no</td>
						<td align='center' >$isselect</td>
						<td align='center' >$kode_role</td>
						<td align='center' >$nama_role</td>
						<td align='center' >$kode_menu</td>
						<td align='center' >$nama_menu</td>		
						<td align='center' >$link</td>				
					<tr>";
	}
	$data.="<tr class='$class'>
				<td colspan='8'>
					<input type='button' id='btnsubmit' name='btnsubmit' value='Simpan Perubahan' onClick='simpan_perubahan()'>
				</td>
			</tr>";
	$data.="</tbody>";
	
	if ($cond !="")	$id_menu_terpilih="<label id='id_menu_terpilih'><strong>MENU TERPILIH</strong> : $nama_menu</label>";
	else $id_menu_terpilih="<label id='id_menu_terpilih'></label>";
	
	
   	$objResponse->AddAssign("nrow", "value", $jumlah);  
   	$objResponse->AddAssign("id_menu_terpilih", "innerHTML", $id_menu_terpilih);  	
   	$objResponse->AddAssign("mytable", "innerHTML", $data);  
 	$objResponse->AddAssign('loader', "innerHTML", '');        
  	return $objResponse;
}

function display_submenu($menu_utama)
{
 	include("../server.php"); 
	//session_start();
  	set_time_limit(180);  
	$anak_buah="";
	
  	$objResponse = new xajaxResponse(); 
	
	if ($menu_utama != "") $cond1=" kode_parent='$menu_utama'";
	$cond = $cond1;
	if ($cond !="") $cond=" where $cond";

	$sql="select count(*) as jumlah from MN_MENU $cond";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$jumlah=$row["JUMLAH"];
	
	if ($jumlah == 0)
	{
		$objResponse->AddAlert("Menu ini tidak mempunyai submenu");
	
		$sql="select * from MN_MENU where kode_menu = '$menu_utama'";
		//echo $sql;
		$q=oci_parse($conn,$sql);
		oci_execute($q);
		$row=oci_fetch_assoc($q);
		
		$kode_menu=$row["KODE_MENU"];
		$nama_menu=$row["NAMA_MENU"];
		
		$data="<select name='sub_menu' id='sub_menu'>";
		$data.="<option value='$kode_menu'>$kode_menu - $nama_menu [MENU UTAMA]</option>";
		$data.="</select>";
	}
	else
	{
		$data="<select name='sub_menu' id='sub_menu'>";

		//menu utama
		$sql="select * from MN_MENU where kode_menu = '$menu_utama'";
		//echo $sql;
		$q=oci_parse($conn,$sql);
		oci_execute($q);
		$row=oci_fetch_assoc($q);
		
		$kode_menu=$row["KODE_MENU"];
		$nama_menu=$row["NAMA_MENU"];
		
		$data.="<option value='$kode_menu'>$kode_menu - $nama_menu [MENU UTAMA]</option>";
	
		//submenu
		$sql="select * from MN_MENU $cond order by urutan asc";
		//echo $sql;
		$q=oci_parse($conn,$sql);
		oci_execute($q);
		$no=0;
		
		while($row=oci_fetch_assoc($q))
		{
			$no++;
			$kode_menu=$row["KODE_MENU"];
			$nama_menu=$row["NAMA_MENU"];
		
			$data.="<option value='$kode_menu'>$kode_menu - $nama_menu</option>";
			$child_menu = display_child_menu($kode_menu, $anak_buah);
			$data.=$child_menu;
			
		}
		$data.="</select>";
	}
	//$objResponse->AddAlert($sql);
   	$objResponse->AddAssign("sub_menu", "innerHTML", $data);  
  	return $objResponse;
}

function display_child_menu($kode_menu, $anak_buah)
{
	include("../server.php"); 
	//session_start();
  	set_time_limit(180);  
	$spasi="";
	if ($anak_buah =="") $anak_buah = 1;
	else $anak_buah = $anak_buah + 1;
	
	for ($i=1; $i<=$anak_buah; $i++)
	{
		$spasi.="&nbsp;&nbsp;&nbsp;";
	}
	
	$strsql_child="select m.*
           from mn_menu m
		   where m.kode_parent='$kode_menu' 
   	       order by m.urutan";
		  //echo $strsql_child;
	$q_child=oci_parse($conn,$strsql_child) or die(ocierror());
	oci_execute($q_child);
	$data="";
	while($row_child=oci_fetch_array($q_child)) 
	{
		$kode_menu=$row_child["KODE_MENU"];
		$nama_menu=$row_child["NAMA_MENU"];
		
		$data.="<option value='$kode_menu'>$spasi$kode_menu - $nama_menu</option>";
		$child_menu=display_child_menu($kode_menu, $anak_buah);
		$data.=$child_menu;
	}
	return $data;
}




function simpan_perubahan($kode_menu, $kode_role, $isselect)
{
	include("../server.php"); 
	//session_start();
  	set_time_limit(180);  
  	$objResponse = new xajaxResponse(); 
	
	$strsql="BEGIN UPDATE_ROLE_MENU($kode_menu, $kode_role, '$isselect'); END;";
	//echo $strsql;
	$q=oci_parse($conn,$strsql);
	oci_execute($q);

  	return $objResponse;
}

?>