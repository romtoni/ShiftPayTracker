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
		$id_transaksi = $data_json['id_transaksi'];
		$id_pengenal = $data_json['id_pengenal'];
		$kunci_rahasia = $data_json['kunci_rahasia'];
		$no_va = $data_json['no_va'];
		$nama_tertagih = $data_json['nama_tertagih'];
		$total_tagihan = $data_json['total_tagihan'];
		$keterangan_tagihan = $data_json['keterangan_tagihan'];
		$tgl_va_expired = $data_json['tgl_va_expired'];
		$userlogin = $data_json['userlogin'];

		//file_put_contents("json.txt", $no_va);
	}
	
	// Open the file to get existing content
	//file_put_contents("json.txt", $_SERVER['REQUEST_METHOD']);
	
	$kode_sukses="";
	$kode_sukses=terima_tagihan($id_transaksi, $id_pengenal, $kunci_rahasia, $no_va, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $tgl_va_expired, $userlogin);

	if ($kode_sukses == "Y")
	{
		echo '{ 
			"status":"000", 
			"data" : { 
						"id_transaksi":"'.$id_transaksi.'", 
						"id_pengenal":"'.$id_pengenal.'", 
						"kunci_rahasia":"'.$kunci_rahasia.'",
						"no_va":"'.$no_va.'",
						"nama_tertagih":"'.$nama_tertagih.'",
						"total_tagihan":"'.$total_tagihan.'",
						"keterangan_tagihan":"'.$keterangan_tagihan.'",
						"tgl_va_expired":"'.$tgl_va_expired.'",
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
							"id_transaksi":"", 
							"id_pengenal":"", 
							"kunci_rahasia":"",
							"no_va":"",
							"nama_tertagih":"",
							"total_tagihan":"",
							"keterangan_tagihan":"",
							"tgl_va_expired":"",
							"userlogin":""
							"pesan_error":"Transaksi tidak diijinkan"
						}
				}';
	}
}

function terima_tagihan($id_transaksi,  $id_pengenal, $kunci_rahasia, $no_va, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $tgl_va_expired, $userlogin)
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
		$status_akses = "Y";
		
		$sqlx = "BEGIN SP_SERVER_RECEIVE_VA_TRANSAKSI('$id_transaksi', '$id_akses', '$no_va', '$nama_tertagih', '$total_tagihan', '$keterangan_tagihan', '$tgl_va_expired', '$userlogin'); END; ";
		$qx=oci_parse($conn,$sqlx);
		oci_execute($qx);
	}
	else $status_akses = "T";

	//file_put_contents("json.txt", $sqlx);
	oci_close($conn);
	return $status_akses;
}

?>