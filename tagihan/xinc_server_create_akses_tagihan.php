<?
function display_server_create_akses_tagihan($kode_pelanggan,$flag_aktif,$page)
{
 // session_start();
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  
  $cond1="";
  $cond2="";
  
  if($kode_pelanggan!="") $cond1=" KODE_PELANGGAN = '$kode_pelanggan'";
  if($flag_aktif!="") { $cond2=" FLAG_AKTIF = '$flag_aktif'"; if ($cond1 != "") $cond2 = " and $cond2"; }

  $cond=$cond1.$cond2;
  $cond_count=$cond;
  
  if($cond!="") $cond_count=" where $cond"; 
  if($cond!="") $cond=" where $cond";

  $strcount="select count (*) as JUMLAH from V_SERVER_AKSES_TAGIHAN $cond_count";
 //echo $strcount;
  $q=oci_parse($conn,$strcount);
  oci_execute($q);
  $row=oci_fetch_assoc($q);

  //paging header
  $maxrow=20;
  $baris=$maxrow;
  
  $jmlrec=$row["JUMLAH"];
  $n=$jmlrec/$baris;
  if ($n==floor($jmlrec/$baris)) $npage=$n; else $npage=floor($jmlrec/$baris)+1;  
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

  //RECORDS
$strsql="SELECT * FROM
		(
		    SELECT a.*, rownum r__
		    FROM
		    (
				  SELECT    NAMA_PELANGGAN,
				  			JENIS_BISNIS, 
				  			ID_AKSES,
							ID_PENGENAL,
							KUNCI_RAHASIA,
							KODE_PELANGGAN,
							FLAG_AKTIF
					FROM  V_SERVER_AKSES_TAGIHAN
				   $cond       
		    ) a
		    WHERE rownum < (($hal * $baris) + 1 )
		)
		WHERE r__ >= ((($hal-1) * $baris) + 1)";
//echo $strsql;
  	$q=oci_parse($conn,$strsql);
  	oci_execute($q);
  	$no=0;
	$data="";
  	while($row=oci_fetch_assoc($q)) 
  	{
		$no++;
		if(fmod($no,2)==0) $class="baris1"; else $class="baris2";
		
		$nama_pelanggan=$row["NAMA_PELANGGAN"];
		$jenis_bisnis=$row["JENIS_BISNIS"];
		$id_akses=$row["ID_AKSES"];
		$id_pengenal=$row["ID_PENGENAL"];
		$kunci_rahasia = $row["KUNCI_RAHASIA"];
		$flag_aktif = $row["FLAG_AKTIF"];
		
		if ($flag_aktif == "Y") 
		{
			$action="<a href='#' onclick=\"javascript:status_akses('$id_akses','T')\" onmouseover=\"ddrivetip('NonAktifkan Akses', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/x.png'></a>";
			$xflag = "Aktif";
		}
		elseif ($flag_aktif == "T") 
		{
			$action="<a href='#' onclick=\"javascript:status_akses('$id_akses', 'Y')\" onmouseover=\"ddrivetip('Aktifkan Akses', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/tick.png'></a>";
			$xflag = "Non Aktif";
		}
		
		$linkview=get_address_top()."/tagihan/server_create_akses_tagihan_add_detail.php?id_akses=$id_akses";
		$aview="<a href='#' onClick=\"return GB_showCenter('View Detail Akses', '$linkview', 565, 950)\" onmouseover=\"ddrivetip('View Detail Akses', 75)\" onmouseout=\"hideddrivetip()\"><img src='../images/xview.png' height='15' width='15' border='0'></a>&nbsp;";
				
		$data.="<tr class='$class'>";
		$data.="<td align='center'>$no</td>";
		$data.="<td align='left'>$nama_pelanggan</td>";
		$data.="<td align='left'>$jenis_bisnis</td>";
		$data.="<td align='center'>$id_pengenal</td>";	
		$data.="<td align='center'>$kunci_rahasia</td>";
		$data.="<td align='center'>$xflag</td>";	
		$data.="<td align='center'>$action $aview</td>";	
		$data.="</tr>".chr(13).chr(10); 
  	}

  oci_close($conn);	   			  	  
  $jmlrec=number_format($jmlrec);
  $objResponse->AddAssign("frek", "value", $jmlrec);  
  $objResponse->AddAssign("page", "innerHTML", $datapage);    
  $objResponse->AddAssign("mytable", "innerHTML", $data);  
  $objResponse->AddAssign("loader", "innerHTML", '');        
  $objResponse->AddAssign("nrow", "value", $jmlrec);      
  return $objResponse;
}

function edit_status_akses($id_akses, $flag_aktif)
{
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  $userlogin = $_SESSION["server_user"];
  
  $strsql = "begin SP_SERVER_REVOKE_VA_AKSES('$id_akses', '', 'COMPLETE', '$flag_aktif', '$userlogin'); END;";
  $q=oci_parse($conn,$strsql);
  oci_execute($q);
  oci_close($conn);	   			  	  
  return $objResponse;
}

function edit_status_akses_detail($id_akses, $flag_aktif, $ip_address)
{
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  $userlogin = $_SESSION["server_user"];
  
  $strsql = "begin SP_SERVER_REVOKE_VA_AKSES('$id_akses', '$ip_address', 'COMPLETE', '$flag_aktif', '$userlogin'); END;";
  $q=oci_parse($conn,$strsql);
  oci_execute($q);
  oci_close($conn);	   			  	  
  return $objResponse;
}

?>