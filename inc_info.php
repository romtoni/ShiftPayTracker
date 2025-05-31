<script language="JavaScript">
	var tanggallengkap = new String();
	var namahari = ("Minggu Senin Selasa Rabu Kamis Jumat Sabtu");
	//var namahari = ("Sunday Monday Tuesday Wednesday Thursday Friday Saturday");
	namahari = namahari.split(" ");
	var namabulan = ("Januari Februari Maret April Mei Juni Juli Agustus September Oktober Nopember Desember");
	//var namabulan = ("January February March April May June July August September October November December");
	namabulan = namabulan.split(" ");
	var tgl = new Date();
	var hari = tgl.getDay();
	var tanggal = tgl.getDate();
	var bulan = tgl.getMonth();
	var tahun = tgl.getFullYear();
	tanggallengkap = namahari[hari] + ", " +tanggal + " " + namabulan[bulan] + " " + tahun;
</script>

<script language="JavaScript">
function tampilkanjam()
{
	var waktu = new Date();
	var jam = waktu.getHours();
	var menit = waktu.getMinutes();
	var detik = waktu.getSeconds();
	var ket = new String();
	var teksjam = new String();
	
	if ( jam <10)
	jam = "0" + jam;
	if ( menit <= 9 )
	menit = "0" + menit;
	if ( detik <= 9 )
	detik = "0" + detik;
	
	//if ( jam+menit+detik <= 120000)
	if ( jam < 12)
	ket = " AM";
	else
	ket = " PM";
	teksjam = jam + ":" + menit + ":" + detik + ket;
	tempatjam.innerHTML = teksjam;
	setTimeout ("tampilkanjam()",1000);
}
window.onload = tampilkanjam
</script>