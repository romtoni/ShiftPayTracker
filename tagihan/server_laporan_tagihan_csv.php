<?
include("../server.php");
session_start();
ini_set('memory_limit', '-1');
set_time_limit(1800000); 
	
if (isset($_GET["kode_pelanggan"])) $kode_pelanggan=$_GET["kode_pelanggan"];
if (isset($_GET["no_va"])) $no_va=$_GET["no_va"];
if (isset($_GET["tgl_va_expired"])) $tgl_va_expired=$_GET["tgl_va_expired"];
if (isset($_GET["status_expired"])) $status_expired=$_GET["status_expired"];
if (isset($_GET["flag_bayar"])) $flag_bayar=$_GET["flag_bayar"];
if (isset($_GET["tgl_bayar"])) $tgl_bayar=$_GET["tgl_bayar"];
if (isset($_GET["status_transaksi"])) $status_transaksi=$_GET["status_transaksi"];

function create_csv($kode_pelanggan,$no_va,$tgl_va_expired,$status_expired,$flag_bayar,$tgl_bayar,$status_transaksi)
{	
	global $conn,$file_csv_download,$file_csv;
	
	$userlogin=$_SESSION["server_user"];
	$file_csv="";
	$file_csv_download="";
	
	$cond1="";
	$cond2="";
	$cond3="";
	$cond4="";
	$cond5="";
	$cond6="";
	$cond7="";
	$cond_default="";
	
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
	
	$cond_default = " FLAG_DOWNLOAD IS NULL"; if ($cond1.$cond2.$cond3.$cond4.$cond5.$cond6.$cond7 != "") $cond_default = " and $cond_default";
	
	$cond=$cond1.$cond2.$cond3.$cond4.$cond5.$cond6.$cond7.$cond_default;
	$cond_count=$cond;
	
	if($cond!="") $cond=" where $cond"; 
	
	$strsql="SELECT   
					KODE_PELANGGAN,
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
					TO_CHAR(TGL_BAYAR, 'YYYY-MM-DD HH24:MI:SS') AS TGL_BAYAR,
					ID_TRANSAKSI
				FROM  V_SERVER_LAPORAN_TAGIHAN
			   $cond ";
	//echo $strsql;
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$no=0;
	$data="Executed Date,Creditted Virtual Number,Creditted Name,Creditted Cost,Creditted Information,Status";
	while($row=oci_fetch_assoc($q)) 
	{
		$no++;
		if(fmod($no,2)==0) $class="baris1"; else $class="baris2";
		
		$id_transaksi=$row["ID_TRANSAKSI"];
		$kode_pelanggan=$row["KODE_PELANGGAN"];
		$nama_pelanggan=$row["NAMA_PELANGGAN"];
		$jenis_bisnis=$row["JENIS_BISNIS"];
		$no_va_pelanggan=$row["NO_VA_PELANGGAN"];
		$nama_tertagih=$row["NAMA_TERTAGIH"];
		$total_tagihan=$row["TOTAL_TAGIHAN"];
		$keterangan_tagihan=$row["KETERANGAN_TAGIHAN"];
		$tgl_va_expired = $row["TGL_VA_EXPIRED"];
		$status_expired = $row["STATUS_EXPIRED"];
		$status_transaksi = $row["STATUS_TRANSAKSI"];
		$flag_bayar = $row["FLAG_BAYAR"];
		$tgl_bayar = $row["TGL_BAYAR"];
		
		if ($flag_bayar == "Y" and $status_transaksi == "CLOSE") $status_bayar = "SUCCESS";
		else $status_bayar = "FAIL";
		
		$data.="\r\n".$tgl_bayar.",".$no_va_pelanggan.",".$nama_tertagih.",".$total_tagihan.",".$keterangan_tagihan.",".$status_bayar;
		
		$sql_upd = "UPDATE TM_SERVER_API_VA_TRANSAKSI
					SET
						FLAG_DOWNLOAD = 'Y',
						TGL_DOWNLOAD = SYSDATE,
						USER_DOWNLOAD = '$userlogin'
					WHERE
						ID_TRANSAKSI = '$id_transaksi'
						AND STATUS_TRANSAKSI = 'CLOSE'";
		$q_upd=oci_parse($conn,$sql_upd);
		oci_execute($q_upd);
		
	}
	$file_csv="transaksi_".mt_rand().".csv";
	file_put_contents($file_csv,$data);
	$file_csv_download="csv_server_transaksi/$file_csv";
	copy($file_csv, $file_csv_download);
	unlink($file_csv);
}

create_csv($kode_pelanggan,$no_va,$tgl_va_expired,$status_expired,$flag_bayar,$tgl_bayar,$status_transaksi);

$text = file_get_contents($file_csv_download);
header("Content-Disposition: attachment; filename=\"$file_csv\"");
echo $text;
?>