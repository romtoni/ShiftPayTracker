<?
function display_pengumuman()
{
	include("../server.php"); 
	//session_start();
  	set_time_limit(180);  
  	$objResponse = new xajaxResponse();
	
	$sql = "select count(*) as jumlah from TM_PENGUMUMAN";
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$jumlah = $row["JUMLAH"];
	$data="";
	$no="";
	if ($jumlah > 0)
	{
		$sql = "select 
					ID_PENGUMUMAN,
					MENU_UTAMA, 
					MENU_PARENT,
					MENU_CHILD,
					KETERANGAN,
					USER_CREATE,
					TGL_CREATE 
				from
					TM_PENGUMUMAN";
		$q=oci_parse($conn,$sql);
		oci_execute($q);
		while($row=oci_fetch_assoc($q))
		{
			$no++;
			$id_pengumuman = $row["ID_PENGUMUMAN"];
			$menu_utama = $row["MENU_UTAMA"];
			$menu_parent = $row["MENU_PARENT"];
			$menu_child = $row["MENU_CHILD"];
			$keterangan = $row["KETERANGAN"];
			$user_create = $row["USER_CREATE"];
			$tgl_create = $row["TGL_CREATE"];
			
			$btndel="<a onMouseOver=\"ddrivetip('Hapus', 25)\"onMouseOut=\"hideddrivetip()\" href='#' onClick=\"javascript:del_pengumuman('$id_pengumuman')\"><img src='../images/x.png' width='15' border='0'></a>";
			
			$data .= "<tr>
						<td align='center'>$no</td>
						<td align='center'>$menu_utama</td>
						<td align='center'>$menu_parent</td>
						<td align='center'>$menu_child</td>
						<td align='center'>$keterangan</td>
						<td align='center'>$user_create</td>
						<td align='center'>$tgl_create</td>
						<td align='center'>$btndel</td>
					</tr>";
					
		}
	}
	else
	{
			$data .= "<tr>
						<td align='center' colspan='8' ><h3>TIDAK ADA DATA</h3></td>
					</tr>";
	}
	$objResponse->AddAssign("tbl_pengumuman", "innerHTML", $data);  
  	return $objResponse; 
}

function del_pengumuman($id_pengumuman)
{
 	include("../server.php"); 
	//session_start();
  	set_time_limit(180);   
	$objResponse = new xajaxResponse();
	
	$userlogin = $_SESSION["server_user"];
	$sql = "BEGIN DEL_PENGUMUMAN('$id_pengumuman', '$userlogin'); END;";
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	
	return $objResponse; 
}


function display_menu_parent($menu_utama)
{
 	include("../server.php"); 
	//session_start();
  	set_time_limit(180);  
  	$objResponse = new xajaxResponse(); 
	
	if ($menu_utama == "") 
	{
		$data="<select name='menu_parent' id='menu_parent' >";
		$data.="<option value='$kode_menu'>--Select--</option>";
		$data.="</select>";
	}
	else
	{
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
			
			$data="<select name='menu_parent' id='menu_parent' >";
			$data.="<option value='$kode_menu'>$kode_menu - $nama_menu [MENU UTAMA]</option>";
			$data.="</select>";
		}
		else
		{
			$data="<select name='menu_parent' id='menu_parent' onChange=\"xajax_display_menu_child(this.value);\">";
	
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
				//$child_menu = display_child_menu($kode_menu, $anak_buah);
				//$data.=$child_menu;
				
			}
			$data.="</select>";
		}
	}
	//$objResponse->AddAlert($sql);
   	$objResponse->AddAssign("menu_parent", "innerHTML", $data);  
  	return $objResponse;
}

function display_menu_child($kode_menu)
{
	include("../server.php"); 
	//session_start();
  	set_time_limit(180);  

  	$objResponse = new xajaxResponse(); 
	$anak_buah="";
	$data="<select name='menu_child' id='menu_child'>";
	
	//menu parent
	$sql="select * from MN_MENU where kode_menu = '$kode_menu'";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
		
	$kode_menu=$row["KODE_MENU"];
	$nama_menu=$row["NAMA_MENU"];
	$data.="<option value='$kode_menu'>$kode_menu - $nama_menu [MENU PARENT]</option>";
	
	$strsql_child="select m.*
           from mn_menu m
		   where m.kode_parent='$kode_menu' 
   	       order by m.urutan";
		  //echo $strsql_child;
	$q_child=oci_parse($conn,$strsql_child) or die(ocierror());
	oci_execute($q_child);
	while($row_child=oci_fetch_array($q_child)) 
	{
		$kode_menu=$row_child["KODE_MENU"];
		$nama_menu=$row_child["NAMA_MENU"];
		
		$data.="<option value='$kode_menu'>$kode_menu - $nama_menu</option>";
		$child_menu=display_child_menu($kode_menu, $anak_buah); //ada di xinc_menu_dan_role.php
		$data.=$child_menu;
	}
	$data.="</select>";
	//return $data;
	//$objResponse->AddAlert("menu_child");  
	$objResponse->AddAssign("menu_child", "innerHTML", $data);  
  	return $objResponse;
}
?>