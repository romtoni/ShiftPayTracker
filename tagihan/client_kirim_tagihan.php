<?
include('../auth.php');
include('../lib.php');
include('../server.php');
include("../classes/class.lookup.php");
include ("../api-client/kirim_va.php");
include("xajax_common.php");
//session_start();
error_reporting(0);
$msg="";
function create_transaksi_tagihan()
{
	global $conn,$msg;
	
	$id_pengenal = '567';
	$kunci_rahasia = 'is8s2btmpcjogqoh1bmvigczt67lpara';
	$userlogin=$_SESSION["server_user"];
		
	$nrow=$_POST["nrow"];
	$nc=0;
	$status_kirim="";
	for($i=1; $i<=$nrow; $i++) 
	{
		$cek=$_POST["cek$i"];
		$no_va=$_POST["no_va$i"];
		$total_tagihan=$_POST["total_tagihan$i"];
		$jenis_proses=$_POST["jenis_proses$i"];
		$nama_tertagih=$_POST["nama$i"];
		$keterangan_tagihan=$_POST["keterangan$i"];

	  	if($cek=="Y")
		{
	 		$strsql="begin SP_CLIENT_CREATE_VA_TRANSAKSI('$no_va', '$total_tagihan', '$keterangan_tagihan', '$jenis_proses', '$userlogin'); end;";
			//echo $strsql."<br>";
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
			
			$strsql="SELECT 
						ID_TRANSAKSI
					 FROM
						TM_CLIENT_API_VA_TRANSAKSI
					WHERE
						NO_VA = '$no_va'
						AND TOTAL_TAGIHAN = '$total_tagihan'
						AND STATUS_TRANSAKSI = 'OPEN'";
			//echo $strsql;
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
			$row=oci_fetch_assoc($q);
			$id_transaksi = $row["ID_TRANSAKSI"];
			$status_sukses="";
			$status_sukses = kirim_data($id_pengenal, $kunci_rahasia, $id_transaksi, $no_va, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $userlogin);
			if ($status_sukses == 'Y') $nc++;
		}
	}
	
	if($nc>0) $msg="$nc VA Berhasil Dikirim";
	else $msg="VA Gagal Dikirim";
	oci_close($conn);
	
}

function kirim_data($id_pengenal, $kunci_rahasia, $id_transaksi, $no_va, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $userlogin)
{
	global $conn;
	
	//KIRIM API KE SERVER
	$status_api_kirim="";
	$status_api_kirim=kirim_tagihan_api($id_pengenal, $kunci_rahasia, $id_transaksi, $no_va, $nama_tertagih, $total_tagihan, $keterangan_tagihan, $userlogin);
	
	//UPDATE TABLE CLIENT
	$strsql="begin SP_CLIENT_UPDATE_VA_TRANSAKSI('$id_transaksi', '$no_va', '$total_tagihan', '$status_api_kirim'); end;";
	//echo $strsql;
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	$status_sukses="";
	if ($status_api_kirim == '000') $status_sukses="Y";
	else $status_sukses="T";
	
	return $status_sukses;
}

if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") 
{
	create_transaksi_tagihan();
	//$msg="VA terkirim";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="stylesheet" type="text/css" href="../tooltip.css" />
<link rel="icon" href="../favicon.ico">
<script language="JavaScript" src="../tooltip.js"></script>
<script language="JavaScript" src="../lib.js"></script>
<link rel="stylesheet" href="../example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="../example-print.css" TYPE="text/css" MEDIA="print">
<link rel="stylesheet" type="text/css" href="../datepicker.css"/>
<script language="JavaScript" src="../datepicker.js"></script>
<style>
#loader {
	position: absolute;
	margin-left: auto;
	margin-right: auto;
	margin-top:280px;
	left: 6px;
	right: 0;
	width: 47px;
	height: 25px;
	z-index:0;
	top: -131px;
}
</style>
<?php $xajax->printJavascript('xajax'); ?>
<script>
function display()
{
 var img="<img src='../images/loader.gif'>";
 document.getElementById('loader').innerHTML=img;
 var page=document.myform.page.value; 
 var stambuk=document.getElementById('stambuk').value;
 var kode_kegiatan=document.getElementById('kode_kegiatan').value;
 var tgl_kegiatan=document.getElementById('tgl_kegiatan').value;
 var no_va=document.getElementById('no_va').value;
 
 xajax_display_client_kirim_tagihan(stambuk,kode_kegiatan,tgl_kegiatan,no_va,page);
}

function cek_semua()
{
	var cek_all= document.getElementById('cek_all').checked;

	//var frek= document.getElementById('frek_ori').value;
	var e= document.getElementById("page"); 
	var pageValue = e.options[e.selectedIndex].value; 
	var i;
	var cek = [];
	var idcek = [];
	var mulai;
	var akhir;
	var cek_disable;		
	
	if (pageValue >1) 
	{ 
		mulai = (25*(pageValue-1))+1; //25 ADALAH JUMLAH DATA YG DITAMPILKAN PER HALAMAN.
		akhir = 25*pageValue;  
	}
	else
	{
		mulai = 1;
		akhir = 25;
	}
		
	if (cek_all == true)
	{
		for (i=mulai;i<=akhir;i++) 
		{
			cek_disable = document.getElementById('cek'+i).disabled;
			if (cek_disable == true )
			{
				//do nothing
				document.getElementById('cek'+i).value="";	
			}
			else
			{
				document.getElementById('cek'+i).checked=true;	
				document.getElementById('cek'+i).value="Y";	
			}

		}
			
	}
	
	if (cek_all == false)
	{
		for (i=mulai;i<=akhir;i++)
		{
			document.getElementById('cek'+i).checked=false;	
			document.getElementById('cek'+i).value="";	
		}
	}
}

function cek(no_param)
{
	var no = no_param;
	if (document.getElementById('cek'+no).checked==false)	document.getElementById('cek'+no).value="";
	if (document.getElementById('cek'+no).checked==true)	document.getElementById('cek'+no).value="Y";
}
</script>
<style type="text/css">
<!--
.outside_border tr td table tr td table tr td table tr td table tr td .rounded_box table tr td {
	font-size: 10px;
}
-->
</style>
</head>
<body onLoad="display()">
<div id="loader" align="center"></div>
<form method="post" name="myform" id="myform" action="">
<input type="hidden" name="nrow" id="nrow">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
        <td width='98%'><table width="100%"  border="0" class="outside_border">
            <tr>
              <td align="center" valign="middle"><? include("../inc_banner.php"); ?></td>
            </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
            <? require_once("../inc_menu.php"); ?>
            <tr>
              <td align="center" valign="middle" ><table width="100%"  border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td colspan="2" align="center" valign="middle" class="title_banner">KIRIM TAGIHAN</td>
                </tr>
                <tr>
                  <td width="13%" valign="top" align="left"><?php include("inc_menu_tagihan.php");?></td>
                  <td width="87%" align="center" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr align="left" >
                          <td valign="top">
                          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="left">
                                <fieldset>
                                <legend class="tebal">Filter</legend>
                                  <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                      <td width="13%" align="left">Nama Pegawai</td>
                                      <td width="1%" align="center">:</td>
                                      <td width="86%" align="left"><?
						  if ($_SESSION["server_role"]==1)
						  {
							  $userlogin ="";
							  $cond_lookup="";
							  $no_value = true;
						  }
						  else
						  {
							  $userlogin = $_SESSION["server_user"];
							  $cond_lookup="WHERE STAMBUK = '$userlogin'";
							  $no_value = false;
						  }
						   
								$lookup=new Lookup();
								$lookup->name="stambuk";
								$lookup->id="stambuk";					
								$lookup->sql="select * from REF_PEGAWAI $cond_lookup order by STAMBUK";
								$lookup->value_field="STAMBUK";
								$lookup->list_field="STAMBUK/NAMA";
								$lookup->default_value=$userlogin;	
								$lookup->no_value=$no_value;					
								$lookup->onchange="";
								$lookup->separator=" - ";
								echo $lookup->dropdown();
								?></td>
                                    </tr>
                                    <tr>
                                      <td align="left">Nama Kegiatan</td>
                                      <td align="center">:</td>
                                      <td align="left"><? 
								$lookup=new Lookup();
								$lookup->name="kode_kegiatan";
								$lookup->id="kode_kegiatan";					
								$lookup->sql="select * from REF_KEGIATAN";
								$lookup->value_field="KODE_KEGIATAN";
								$lookup->list_field="NAMA_KEGIATAN";
								$lookup->default_value="";					
								$lookup->onchange="";
								echo $lookup->dropdown();
								?></td>
                                    </tr>
                                    <tr>
                                      <td align="left">Tgl Kegiatan</td>
                                      <td align="center">:</td>
                                      <td align="left">
                                      <input name="tgl_kegiatan" type="text" id="tgl_kegiatan" size="10" readonly>
                      		<a href="javascript:displayDatePicker('tgl_kegiatan',false, 'dmy', '-');"><img src="../images/xcalendar.png" alt="" width="16" height="16" border="0" align="absmiddle" ></a>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left">Virtual Account</td>
                                      <td align="center">:</td>
                                      <td align="left"><input type="text" name="no_va" id="no_va"></td>
                                    </tr>
                                    <tr>
                                      <td align="left"><span class="padding_left4">Halaman</span></td>
                                      <td colspan="2">
                                        <select name="page" onChange="display()" id="page">
                                          <? lookup_page($npage,$page_item); ?>
                                        </select>
                                        <input name="btnprev" type="button" id="btnprev" onClick="this.form.page.value--; display();" value="Prev">
                                        <input name="btnnext" type="button" id="btnnext" onClick="this.form.page.value++; display();" value="Next">
                                        Total
                                        <input name="frek" type="text" class="numeric_textbox" id="frek" size="10" readonly>
                                        records
                                        <input type="button" name="Button" value="Display" onClick="page.value='1'; display()">
                                        <!--<input name="cx_semua" type="checkbox" id="cx_semua" value="y">
                                        Tampilkan semua record--> </td>
                                        <input name="n_id" id="n_id" type="hidden">
                                      </tr>
                                    <tr>
                                      <td align="left"><input type="submit" name="btnsubmit" id="btnsubmit" value="Proses"></td>
                                      <td colspan="2">&nbsp;</td>
                                    </tr>
                                  </table>
                                </fieldset></td>
                             </table>
                             </td>
                          </tr>
                          <tr>
                          	<td>
                            <table width="100%" >
                          	  <tr class="table_header">
                          	    <td width="24" rowspan="2" align="center">
                                <input name="cek_all" type="checkbox" id="cek_all" onClick="cek_semua();" value="Y">
                                 </td>
                          	    <td width="24" rowspan="2" align="center">No</td>
                          	    <td colspan="2" align="center">Pegawai</td>
                          	    <td colspan="5" align="center">Kegiatan</td>
                          	    <td width="85" rowspan="2" align="center">Virtual Account</td>
                          	    </tr>
                          	  <tr class="table_header">
                          	    <td width="51" align="center">Stambuk</td>
                          	    <td width="173" align="center">Nama</td>
                          	    <td width="32" align="center">Kode</td>
                          	    <td width="164" align="center">Nama</td>
                          	    <td width="48" align="center">Tanggal</td>
                          	    <td width="57" align="center">Biaya</td>
                          	    <td width="145" align="center">Keterangan</td>
                          	    </tr>
                                <tbody id="mytable"></tbody>
                        	  </table>
                              </td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
            </tr>
            <? include("../inc_footer.php"); ?>
        </table></td>
        <td width='20px' background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
      </tr>
    </table>    </td>
  </tr>
</table>
</form>
</body>
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>
