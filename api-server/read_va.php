<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get posted data
$data = file_get_contents("php://input");
$data_json = json_decode($data, true);
if (!$data_json) {
	// handling orang iseng
	echo '{"status":"999","message":"Jangan coba-coba atau ands akan menyesal"}';
}
else
{
	foreach ($data_json as $key=>$value)
	{
		$no_va = $data_json['no_va'];
	}
	
	$data_exist=read_va($no_va);
	
	$serpihan_data_exist=explode("|",$data_exist);
	$flag_exist=$serpihan_data_exist[0];
	$nama_tertagih=$serpihan_data_exist[1];
	$total_tagihan=$serpihan_data_exist[2];
	$keterangan_tagihan=$serpihan_data_exist[3];
	$tgl_va_expired=$serpihan_data_exist[4];
	$status_expired=$serpihan_data_exist[5];
	$status_transaksi=$serpihan_data_exist[6];
	
	//file_put_contents("json.txt", $data_exist);
	if ($flag_exist=="Y")
	{
		echo '{
				"status":"000",
				"data": {
							"nama_tertagih":"'.$nama_tertagih.'",
							"total_tagihan":"'.$total_tagihan.'",
							"keterangan_tagihan":"'.$keterangan_tagihan.'",
							"tgl_va_expired":"'.$tgl_va_expired.'",
							"status_expired":"'.$status_expired.'",
							"status_transaksi":"'.$status_transaksi.'",
							"pesan_error":""
						}
			  }';
	}
	else 
	{
		echo '{
				"status":"999",
				"data": {
							"nama_tertagih":"",
							"total_tagihan":"0",
							"keterangan_tagihan":"",
							"tgl_va_expired":"",
							"status_expired":"",
							"status_transaksi":"",
							"pesan_error":"No Virtual Account '.$no_va.' tidak ada atau tidak bisa lagi digunakan"
						}
			  }';	
	}
	
}

function read_va($no_va)
{
	include("server.php");
	
	$sql = "SELECT COUNT(*) AS JMLDATA FROM TM_SERVER_API_VA_TRANSAKSI
			 WHERE 
			 	NO_VA_PELANGGAN = '$no_va' 
				AND STATUS_TRANSAKSI = 'OPEN'
				AND STATUS_EXPIRED = 'T'
				AND FLAG_BAYAR IS NULL";
	$q=oci_parse($conn, $sql);
	oci_execute($q);
	$row=oci_fetch_array($q);
	$jmldata = $row["JMLDATA"];
	
	if ($jmldata == 0) 
	{
		$data_exist = "T| | | | | |";
	}
	else
	{
		$sql = "SELECT 
					NAMA_TERTAGIH,
					TOTAL_TAGIHAN,
					KETERANGAN_TAGIHAN,
                    TO_CHAR(TGL_VA_EXPIRED, 'DD/MM/YYYY') AS TGL_VA_EXPIRED,
					STATUS_EXPIRED,
					STATUS_TRANSAKSI
				FROM 
					TM_SERVER_API_VA_TRANSAKSI
				 WHERE 
					NO_VA_PELANGGAN = '$no_va' 
					AND STATUS_TRANSAKSI = 'OPEN'
					AND STATUS_EXPIRED = 'T'
					AND FLAG_BAYAR IS NULL";
		$q=oci_parse($conn, $sql);
		oci_execute($q);
		$row=oci_fetch_array($q);
		$nama_tertagih = $row["NAMA_TERTAGIH"];
		$total_tagihan = $row["TOTAL_TAGIHAN"];
		$keterangan_tagihan = $row["KETERANGAN_TAGIHAN"];
		$tgl_va_expired = $row["TGL_VA_EXPIRED"];
		$status_expired = $row["STATUS_EXPIRED"];
		$status_transaksi = $row["STATUS_TRANSAKSI"];
		
		$data_exist = "Y|$nama_tertagih|$total_tagihan|$keterangan_tagihan|$tgl_va_expired|$status_expired|$status_transaksi";
	}
	//file_put_contents("json.txt", $sql);
	return $data_exist;
}
?>