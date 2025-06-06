<?
function display_client_kirim_tagihan($stambuk, $kode_kegiatan, $tgl_kegiatan, $no_va,$page)
{
 // session_start();
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  
  $cond1="";
  $cond2="";
  $cond3="";
  $cond4="";
  
  if($stambuk!="") $cond1=" STAMBUK = '$stambuk'";
  if($kode_kegiatan!="") { $cond2=" KODE_KEGIATAN = '$kode_kegiatan'"; if ($cond1 != "") $cond2 = " and $cond2"; }
  if($tgl_kegiatan!="") { $cond3=" TO_CHAR(TGL_KEGIATAN, 'DD-MM-YYYY') = '$tgl_kegiatan'"; if ($cond1.$cond2 != "") $cond3 = " and $cond3"; }
  if($no_va!="") { $cond4=" NO_VA = '$no_va'"; if ($cond1.$cond2.$cond3 != "") $cond4 = " and $cond4"; }
  
  $cond=$cond1.$cond2.$cond3.$cond4;
  $cond_count=$cond;
  
  if($cond!="") $cond_count=" where $cond"; 
  if($cond!="") $cond=" where $cond";

  $strcount="select count (*) as JUMLAH from V_CLIENT_KIRIM_TAGIHAN $cond_count";
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
				  SELECT   STAMBUK,
						   NAMA,
						   KODE_KEGIATAN,
						   NAMA_KEGIATAN,
						   TGL_KEGIATAN,
						   BIAYA,
						   KETERANGAN,
						   NO_VA
					FROM  V_CLIENT_KIRIM_TAGIHAN
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
		
		$stambuk=$row["STAMBUK"];
		$nama=$row["NAMA"];
		$kode_kegiatan=$row["KODE_KEGIATAN"];
		$nama_kegiatan=$row["NAMA_KEGIATAN"];
		$tgl_kegiatan=$row["TGL_KEGIATAN"];
		$biaya=$row["BIAYA"];
		$keterangan = $row["KETERANGAN"];
		$no_va = $row["NO_VA"];
		
		$xbiaya=number_format($biaya,0, ',','.');
		
		$data.="<tr class='$class'>";
		$data.="<td align='center'>
					<input type='checkbox' id='cek$no' name='cek$no' onclick=\"cek('$no')\">
					<input type='hidden' id='no_va$no' name='no_va$no' value='$no_va'>
					<input type='hidden' id='total_tagihan$no' name='total_tagihan$no' value='$biaya'>
					<input type='hidden' id='jenis_proses$no' name='jenis_proses$no' value='K'>
					<input type='hidden' id='nama$no' name='nama$no' value='$nama'>
					<input type='hidden' id='keterangan$no' name='keterangan$no' value='$keterangan'>
				</td>";
		$data.="<td align='center'>$no</td>";
		$data.="<td align='center'>$stambuk</td>";
		$data.="<td align='left'>$nama</td>";
		$data.="<td align='center'>$kode_kegiatan</td>";	
		$data.="<td align='left'>$nama_kegiatan</td>";
		$data.="<td align='center'>$tgl_kegiatan</td>";	
		$data.="<td align='right'>$xbiaya</td>";
		$data.="<td align='left'>$keterangan</td>";	
		$data.="<td align='center'>$no_va</td>";	
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

?>