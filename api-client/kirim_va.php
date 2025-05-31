<?php
include_once("server.php");
include_once("lib_send_api.php");

function kirim_tagihan_api($id_pengenal, $kunci_rahasia, $id_transaksi, $no_va, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $userlogin)
{
	$url = 'http://localhost/shift_pay_tracker/api-server/receive_va.php';
	
	$va_pelanggan=array(
							"id_transaksi" => $id_transaksi,
							"id_pengenal" => $id_pengenal,
							"kunci_rahasia" => $kunci_rahasia,
							"no_va" => $no_va,
							"nama_tertagih" => $nama_tertagih,
							"total_tagihan" => $total_tagihan,
							"keterangan_tagihan" => $keterangan_tagihan,
							"tgl_va_expired" => date('d/m/Y'),
							"userlogin" => $userlogin
						);
						
	//echo json_encode($va_pelanggan);
	
	$response = get_content($url, json_encode($va_pelanggan));	
	$response_json = json_decode($response, true);
	
	$status="";
	//$flag_success="";
	if ($response_json['status'] !== '000') 
	{
		// handling jika gagal
		$status = $response_json['status'];
		//var_dump($response_json);
		//$flag_success = "T";
	}
	else 
	{
		$status = $response_json['status'];
		//$data_response = $response_json['data'];
		//var_dump($data_response);
		//$flag_success="Y";
	}
	
	//file_put_contents("json.txt", "a");
	return $status;	
}
?>