<?php 
function display_santunan_meninggal2_verifikasi($no_lisensi,$id_agen,$nama,$ver_santunan,$ktr_administrasi,$page)
{
	session_start();
	include("../server.php");
	set_time_limit(180);
	
	if ($ktr_administrasi == "ZPA"){$ktr_administrasi=""; }
	//
	//	$ver=="Y";
	$objResponse = new xajaxResponse(); 
	if($no_lisensi!="") {$cond1="A.NO_LISENSI = '$no_lisensi'";}
	if($id_agen!="") {$cond2="B.ID ='$id_agen'"; if($cond1!="") $cond2=" and $cond2";}
	if($nama!="") {$cond3="upper(B.NAMA) LIKE upper('%$nama%')"; if($cond1.$cond2!="") $cond3=" and $cond3";}
	if($ver_santunan!="") {$cond4="upper(A.FLAG) LIKE upper('%$ver_santunan%')"; if($cond1.$cond2.$cond3!="") $cond4=" and $cond4";}
	if($ktr_administrasi!="") {$cond5="TRIM(B.KTR_ADMINISTRASI)=upper(trim('$ktr_administrasi'))"; if($cond1.$cond2.$cond3.$cond4!="") $cond5=" and $cond5";}
	//if($ver!="") {$cond5="BUTUH_VERIFIKASI='$ver'"; if($cond1.$cond2.$cond3.$cond4!="") $cond5=" and $cond5";}
	
	$cond=$cond1.$cond2.$cond3.$cond4;
	$cond_count=$cond;

	if($cond!="") $cond_count=" where $cond"; 
	if($cond!="") $cond=" where $cond";  							  

	$strcount="select count(*) as jumlah from (SELECT   A.ID_SANTUNAN,
							 B.NAMA,
							 B.ID AS ID_PUSAT,
							 B.TEMPAT_LAHIR,
							 B.TGL_LAHIR,
							 A.NO_LISENSI,
							 A.TELP,
							 B.KTR_ADMINISTRASI,
							 B.TGL_LISENSI_AKHIR,
							 A.TGL_MENINGGAL,
							 A.KET_MENINGGAL,
							 A.USER_VERIFIKASI,
							 A.TGL_VERIFIKASI,
							 A.FLAG AS STATUS,
							 D.KET
					  FROM            TM_PENGAJUAN_SANTUNAN A
								   LEFT JOIN
									  TM_AGEN B
								   ON A.NO_LISENSI = B.NO_LISENSI
								LEFT JOIN
								   D_KTR C
								ON B.ktr_administrasi = C.kdktr
							 LEFT JOIN
								TM_REF_SANTUNAN D
							 ON A.FLAG = D.KODE_STATUS
					$cond)";
	//echo $strcount;
	$q=oci_parse($conn,$strcount);
	oci_execute($q);		
	$row=oci_fetch_assoc($q);
  
	  
	//paging header
	//=============================================
	$baris=$maxrow;  
	$jmlrec=$row["JUMLAH"];
	$n=$jmlrec/$baris;
	if($n==floor($jmlrec/$baris)) $npage=$n; else $npage=floor($jmlrec/$baris)+1;  
	if($page=="") $hal=1; else $hal=$page;
	$posisi=($hal-1)*$baris;
	//lookup page
	$datapage="<select name='page' onChange='display()' >";
	for($i=1;$i<=$npage;$i++) 
	{
		if($i==$page) $sel="selected"; else $sel="";
		$datapage.="<option value='$i' $sel>$i</option>";
	}   
	$datapage.="</select>";
	//==============================================-
	
	//==============================================-
	//RECORDS
	
	$strsql="SELECT * FROM
			(
				SELECT a.*, rownum r__
				FROM
				(
				 	SELECT   A.ID_SANTUNAN,
							 B.NAMA,
							 B.ID AS ID_PUSAT,
							 B.TEMPAT_LAHIR,
							 B.TGL_LAHIR,
							 A.NO_LISENSI,
							 A.TELP,
							 B.KTR_ADMINISTRASI,
							 B.TGL_LISENSI_AKHIR,
							 A.TGL_MENINGGAL,
							 A.KET_MENINGGAL,
							 A.USER_VERIFIKASI,
							 A.TGL_VERIFIKASI,
							 A.FLAG AS STATUS,
							 D.KET
					  FROM            TM_PENGAJUAN_SANTUNAN A
								   LEFT JOIN
									  TM_AGEN B
								   ON A.NO_LISENSI = B.NO_LISENSI
								LEFT JOIN
								   D_KTR C
								ON B.ktr_administrasi = C.kdktr
							 LEFT JOIN
								TM_REF_SANTUNAN D
							 ON A.FLAG = D.KODE_STATUS
					$cond
					order by B.NAMA
				) a
				WHERE rownum < (($hal * $baris) + 1 )
			)
			WHERE r__ >= ((($hal-1) * $baris) + 1)";
	$q=oci_parse($conn,$strsql);
	//echo $strsql;
	oci_execute($q);
	$no=$posisi;
	while($row=oci_fetch_array($q))
	{
		$no++;
		//$id=$row["ID"];
		$id_santunan=$row["ID_SANTUNAN"];
		$nama=$row["NAMA"];
		$id_agen=$row["ID_PUSAT"];
		$tempat_lahir=$row["TEMPAT_LAHIR"];
		$tgl_lahir=$row["TGL_LAHIR"];
		$no_lisensi=$row["NO_LISENSI"];
		$telp=$row["TELP"];
		$ktr_administrasi=$row["KTR_ADMINISTRASI"];
		$tgl_lisensi_akhir=$row["TGL_LISENSI_AKHIR"];
		$user_pengajuan=$row["USER_PENGAJUAN"];
		$tgl_meninggal=$row["TGL_MENINGGAL"];
		$ket_meninggal=$row["KET_MENINGGAL"];
		$ver_santunan=$row["FLAG"];
		$ket=$row["KET"];
		$user_verifikasi=$row["USER_VERIFIKASI"];
		$tgl_verifikasi=$row["TGL_VERIFIKASI"];
		$action=$row["ACTION"];
		
		$adel="<a onMouseOver=\"ddrivetip('Delete', 30)\" onMouseOut=\"hideddrivetip()\" href='javascript:del(\"$no_lisensi\");'><img src='../images/img_del.png' border='0'></a>";	
		$tolak="<a onMouseOver=\"ddrivetip('Tolak', 30)\" onMouseOut=\"hideddrivetip()\" href='javascript:del(\"$no_lisensi\");'><img src='../images/img_del.png' border='0'></a>";
		$aedit="<a onMouseOver=\"ddrivetip('Edit', 20)\" onMouseOut=\"hideddrivetip()\" href='santunan_meninggal_edit.php?id=$id'><img src='../images/img_edit.png' border='0'></a>&nbsp;";
		$linkview=get_address_top()."/modul_keagenan/santunan_meninggal_view.php?id=$id";
		$aview="<a href='agen_view.php?id=$id' onClick=\"return GB_showCenter('View Detail Santunan', '$linkview', 450, 800)\" onmouseover=\"ddrivetip('View Detail Santunan', 75)\" onmouseout=\"hideddrivetip()\"><img src='../images/xview.png' height='15' width='15' border='0'></a>&nbsp;";
		
		if(fmod($no,2)==0) $class="baris2"; else $class="baris1";
		if($butuh_verifikasi=="") $butuh_verifikasi="-";
		if ($butuh_verifikasi=="Y") 
		{ 
			$class=""; 
			if ($action=="A") 
			{
				$action="(ADD)";
				$bgcolor="bgcolor='#9AC941'"; 
			}
			if ($action=="E") 
			{
				$action="(EDIT)";
				$bgcolor="bgcolor='#F7AF18'";
			}
			if ($action=="D") 
			{
				$action="(DELETE)";
				$bgcolor="bgcolor='#D00E68'";
			}
			
		}
		else $bgcolor="";
		
		if ($ver_santunan == "V")
			{
				$xver_santunan="asd";
			}
		/*if ($ver_santunan == "")
			{
				$xver_santunan="<a onMouseOver=\"ddrivetip('Ver Santunan Meninggal', 40)\" onMouseOut=\"hideddrivetip()\" href='javascript:santunan_meninggal2_verifikasi(\"$id_santunan\");'><img src='../images/button_ok.png' border='0' width='16' height='16'></a>";}*/
		
		$data.="<tr class='$class' $bgcolor>
				<td align='center'>$no</td>
				<td align='center'>$nama</td>
				<td align='center'>$id_agen</td>
				<td align='center'>$tempat_lahir</td>
				<td align='center'>$tgl_lahir</td>
				<td align='center'>$no_lisensi</td>
				<td align='center'>$telp</td>
				<td align='center'>$ktr_administrasi</td>";
		$data.="<td align='center'>$tgl_lisensi_akhir</td>
				<td align='center'>$tgl_meninggal</td>
				<td align='center'>$ket_meninggal</td>
				<td align='center'>$user_verifikasi</td>
				<td align='center'>$tgl_verifikasi</td>
				<td align='center'>$ket</td>
				<td align='center' width='55px'>$xver_santunan $tolak</td>
				</tr>";
	}
oci_close($conn);
$jmlrec=number_format($jmlrec);
$objResponse->AddAssign("frek", "value", $jmlrec);  
$objResponse->AddAssign("page", "innerHTML", $datapage);    
$objResponse->AddAssign("mytable", "innerHTML", $data);  
$objResponse->AddAssign('loader', "innerHTML", '');        
return $objResponse;	
}
//<td align='center'>$tgl_registrasi/$tgl_exit</td>
function santunan_meninggal2_verifikasi($id_santunan,$userlogin){
	session_start();
	include("../server.php");  
	$objResponse = new xajaxResponse(); 
	$userlogin=$_SESSION["server_user"];
	
	$sql="begin SP_VER_SANTUNAN_MENINGGAL ('$id_santunan','$userlogin'); end;";
	//echo $sql;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	   			  	    
	return $objResponse;
}

/*function delete_santunan_meninggal2($no_lisensi){
	session_start();
	include("../server.php");  
	$objResponse = new xajaxResponse(); 
	//$strsql="delete from TM_AGEN where ID='$id'";
	$strsql="delete from TM_PENGAJUAN_SANTUNAN where NO_LISENSI='$no_lisensi'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	oci_close($conn);	   			  	    
	return $objResponse;
}*/
?>