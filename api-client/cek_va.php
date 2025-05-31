<?php
include_once("lib_send_api.php");

function cek_va_api($no_va)
{
	$url = 'http://localhost/shift_pay_tracker/api-server/read_va.php';
	$va_pelanggan=array("no_va"=>$no_va);
	
	$response = get_content($url, json_encode($va_pelanggan));	
	$response_json = json_decode($response, true);
	
	$nama_tertagih = "";
	$total_tagihan = "";
	$keterangan_tagihan = "";
	$tgl_va_expired = "";
	$status_expired = "";
	$status_transaksi = "";
	$pesan_error = "";
	
	if ($response_json['status'] !== '000') 
	{
		// handling jika gagal
		$status = $response_json['status'];
		$data_response = $response_json['data'];
		//var_dump($data_response);
		$flag_sukses= "T";
	}
	else 
	{
		$status = $response_json['status'];
		$data_response = $response_json['data'];
		//var_dump($data_response);
		$flag_sukses= "Y";
	}
	
	foreach ($data_response as $key=>$value)
	{
		
		$nama_tertagih = $data_response["nama_tertagih"];
		$total_tagihan = $data_response["total_tagihan"];
		$keterangan_tagihan = $data_response["keterangan_tagihan"];
		$tgl_va_expired = $data_response["tgl_va_expired"];
		$status_expired = $data_response["status_expired"];
		$status_transaksi = $data_response["status_transaksi"];
		$pesan_error = $data_response["pesan_error"];

	}
		
	$data_va="$flag_sukses|$nama_tertagih|$total_tagihan|$keterangan_tagihan|$tgl_va_expired|$status_expired|$status_transaksi|$pesan_error";
	
	//file_put_contents("json.txt", $data_response);
	return $data_va;	
}
?>