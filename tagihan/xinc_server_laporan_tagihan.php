<?
function display_server_laporan_tagihan($kode_pelanggan,$no_va,$tgl_va_expired,$status_expired,$flag_bayar,$tgl_bayar,$status_transaksi,$page)
{
 // session_start();
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  
  $cond1="";
  $cond2="";
  $cond3="";
  $cond4="";
  $cond5="";
  $cond6="";
  $cond7="";

  if($kode_pelanggan!="") $cond1=" KODE_PELANGGAN = '$kode_pelanggan'";
  if($no_va!="") { $cond2=" NO_VA_PELANGGAN = '$no_va'"; if ($cond1 != "") $cond2 = " and $cond2"; }
  if($tgl_va_expired!="") { $cond3=" TO_CHAR(TGL_VA_EXPIRED, 'DD-MM-YYYY') = '$tgl_va_expired'"; if ($cond1.$cond2 != "") $cond3 = " and $cond3"; }
  if($status_expired!="") { $cond4=" STATUS_EXPIRED = '$status_expired'"; if ($cond1.$cond2.$cond3 != "") $cond4 = " and $cond4"; }
  if($flag_bayar!="") 
  { 
  	if ($flag_bayar == "Y")	$cond5=" FLAG_BAYAR = '$flag_bayar'"; 
  	elseif ($flag_bayar == "T")	$cond5=" FLAG_BAYAR IS NULL"; 
	
  	if ($cond1.$cond2.$cond3.$cond4 != "") $cond5 = " and $cond5"; 
  }
  if($tgl_bayar!="") { $cond6=" TO_CHAR(TGL_BAYAR, 'DD-MM-YYYY') = '$tgl_bayar'"; if ($cond1.$cond2.$cond3.$cond4.$cond5 != "") $cond6 = " and $cond6"; }
  if($status_transaksi!="") { $cond7 = " STATUS_TRANSAKSI = '$status_transaksi'";  if ($cond1.$cond2.$cond3.$cond4.$cond5.$cond6 != "") $cond7 = " and $cond7"; }

  $cond=$cond1.$cond2.$cond3.$cond4.$cond5.$cond6.$cond7;
  $cond_count=$cond;
  
  if($cond!="") $cond_count=" where $cond"; 
  if($cond!="") $cond=" where $cond";

  $strcount="select count (*) as JUMLAH from V_SERVER_LAPORAN_TAGIHAN $cond_count";
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
				  SELECT   KODE_PELANGGAN,
				  		   NAMA_PELANGGAN,
						   JENIS_BISNIS,
						   NO_VA_PELANGGAN,
						   NAMA_TERTAGIH,
						   TOTAL_TAGIHAN,
						   KETERANGAN_TAGIHAN,
						   TGL_VA_EXPIRED,
						   STATUS_EXPIRED,
						   STATUS_TRANSAKSI,
						   FLAG_BAYAR,
						   TGL_BAYAR,
						   TGL_DOWNLOAD
					FROM  V_SERVER_LAPORAN_TAGIHAN
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
		
		$kode_pelanggan=$row["KODE_PELANGGAN"];
		$nama_pelanggan=$row["NAMA_PELANGGAN"];
		$jenis_bisnis=$row["JENIS_BISNIS"];
		$no_va_pelanggan=$row["NO_VA_PELANGGAN"];
		$nama_tertagih=$row["NAMA_TERTAGIH"];
		$total_tagihan=number_format($row["TOTAL_TAGIHAN"],0, ',','.');
		$keterangan_tagihan=$row["KETERANGAN_TAGIHAN"];
		$tgl_va_expired = $row["TGL_VA_EXPIRED"];
		$status_expired = $row["STATUS_EXPIRED"];
		$status_transaksi = $row["STATUS_TRANSAKSI"];
		$flag_bayar = $row["FLAG_BAYAR"];
		$tgl_bayar = $row["TGL_BAYAR"];
		$tgl_download = $row["TGL_DOWNLOAD"];
		
		if ($status_expired == "Y") $xstatus_expired = "Sudah Expired"; 
		else $xstatus_expired = "Belum Expired";
		
		
		if ($flag_bayar == "" and $status_transaksi=="OPEN") { $xstatus_bayar = "Belum Dibayar"; $bgcolor ="yellow"; $class =""; }
		else { $xstatus_bayar = "Sudah Dibayar";  $bgcolor =""; }

		
		$data.="<tr class='$class' bgcolor='$bgcolor'>";
		$data.="<td align='center'>$no</td>";
		$data.="<td align='left'>$nama_pelanggan</td>";
		$data.="<td align='left'>$jenis_bisnis</td>";
		$data.="<td align='center'>$no_va_pelanggan</td>";	
		$data.="<td align='left'>$nama_tertagih</td>";	
		$data.="<td align='right'>$total_tagihan</td>";
		$data.="<td align='left'>$keterangan_tagihan</td>";	
		$data.="<td align='center'>$tgl_va_expired</td>";
		$data.="<td align='center'>$xstatus_expired</td>";	
		$data.="<td align='center'>$status_transaksi</td>";	
		$data.="<td align='center'>$xstatus_bayar</td>";	
		$data.="<td align='center'>$tgl_bayar</td>";	
		$data.="<td align='center'>$tgl_download</td>";	
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