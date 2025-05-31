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
		$kode_pelanggan = $data_json['kode_pelanggan'];
		$id_pengenal = $data_json['id_pengenal'];
		$kunci_rahasia = $data_json['kunci_rahasia'];
		$no_va = $data_json['no_va'];
		$total_tagihan = $data_json['total_tagihan'];
		$userlogin = $data_json['userlogin'];

		//file_put_contents("json.txt", $no_va);
	}
	
	// Open the file to get existing content
	//file_put_contents("json.txt", $_SERVER['REQUEST_METHOD']);


	$kode_sukses="";
	$kode_sukses=pembayaran_tagihan($id_pengenal, $kunci_rahasia, $kode_pelanggan, $no_va, $total_tagihan, $userlogin);
	
	if ($kode_sukses == "Y")
	{
		echo '{ 
				"status":"000", 
				"data" : { 
							"kode_pelanggan":"'.$kode_pelanggan.'", 
							"id_pengenal":"'.$id_pengenal.'", 
							"kunci_rahasia":"'.$kunci_rahasia.'",
							"no_va":"'.$no_va.'",
							"total_tagihan":"'.$total_tagihan.'",
							"userlogin":"'.$userlogin.'",
							"pesan_error":""
						}
				}';
	}
	else
	{
		echo '{ 
				"status":"999", 
				"data" : { 
							"kode_pelanggan":"", 
							"id_pengenal":"", 
							"kunci_rahasia":"",
							"no_va":"",
							"total_tagihan":"",
							"userlogin":"",
							"pesan_error":"Transaksi tidak diijinkan atau No VA '.$no_va.' tidak valid."
						}
				}';
	}
}

function pembayaran_tagihan($id_pengenal, $kunci_rahasia, $kode_pelanggan, $no_va, $total_tagihan, $userlogin)
{
	include("server.php");
	
	$status_akses = "";
	
	$sql = "SELECT   COUNT ( * ) AS JMLDATA, ID_AKSES
			FROM   TM_SERVER_API_VA_AKSES
		   WHERE       ID_PENGENAL = '$id_pengenal'
				   AND KUNCI_RAHASIA = '$kunci_rahasia'
				   AND FLAG_AKTIF = 'Y'
		GROUP BY   ID_AKSES";
	//file_put_contents("json.txt", $sql);

	$q=oci_parse($conn, $sql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$jmldata=$row["JMLDATA"];
	$id_akses=$row["ID_AKSES"];
	
	if ($jmldata > 0)
	{
		
		$sqlvalid = "SELECT   COUNT ( * ) AS JMLVALID
						FROM   TM_SERVER_API_VA_TRANSAKSI
					   WHERE       NO_VA_PELANGGAN = '$no_va'
							   AND TOTAL_TAGIHAN = '$total_tagihan'
							   AND STATUS_TRANSAKSI = 'OPEN'
							   AND STATUS_EXPIRED = 'T'
							   AND FLAG_BAYAR IS NULL";
		//file_put_contents("json.txt", $sql);
	
		$qvalid=oci_parse($conn, $sqlvalid);
		oci_execute($qvalid);
		$rowvalid=oci_fetch_assoc($qvalid);
		$jmlvalid=$rowvalid["JMLVALID"];
		
		
		if ($jmlvalid == 0)	$status_akses="T";
		else
		{
			$status_akses="Y";
			
			$sqlx = "BEGIN SP_SERVER_PAY_VA_TRANSAKSI('$id_pengenal', '$kunci_rahasia', '$kode_pelanggan', '$no_va', '$total_tagihan', '$userlogin'); END; ";
			$qx=oci_parse($conn,$sqlx);
			oci_execute($qx);
		}
	}
	else $status_akses="T";

	//file_put_contents("json.txt", $sqlx);
	oci_close($conn);
	return  $status_akses;
}

?>