<?php
function table_cek_va($kode_pesan, $pesan_error, $no_va_pelanggan, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $tgl_expired, $status_expired, $status_transaksi)
{
	if ($kode_pesan == 'VALID')
	{
		$tbl_msg="<tr class='title_banner'>
					<td width='100%' colspan='3' align='left'>Detail Virtual Account</td>
				  </tr>
				  <tr>
					<td width='241'>No Virtual Account</td>
					<td width='10'>:</td>
					<td width='946'>$no_va_pelanggan</td>
				  </tr>
				  <tr>
					<td>Nama Tertagih</td>
					<td>:</td>
					<td>$nama_tertagih</td>
				  </tr>
				  <tr>
					<td>Total Tagihan</td>
					<td>:</td>
					<td>$total_tagihan</td>
				  </tr>
				  <tr>
					<td>Keterangan Tagihan</td>
					<td>:</td>
					<td>$keterangan_tagihan</td>
				  </tr>
				  <tr>
					<td>Tgl Expired</td>
					<td>:</td>
					<td>$tgl_expired</td>
				  </tr>
				  <tr>
					<td>Status Expired</td>
					<td>:</td>
					<td>$status_expired</td>
				  </tr>
				  <tr>
					<td>Status Transaksi</td>
					<td>:</td>
					<td>$status_transaksi</td>
				  </tr>";
	}
	else
	{
		$tbl_msg="<tr class='title_banner'>
					<td width='100%' colspan='3' align='left'>Detail Virtual Account</td>
				  </tr>
				  <tr>
					<td colspan='3'>$pesan_error</td>
				  </tr>";
	}
	return $tbl_msg;
}
?>