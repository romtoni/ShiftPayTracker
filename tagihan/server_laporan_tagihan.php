<?
include('../auth.php');
include('../lib.php');
include('../server.php');
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
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
 var kode_pelanggan=document.getElementById('kode_pelanggan').value;
 var no_va=document.getElementById('no_va').value;
 var tgl_va_expired=document.getElementById('tgl_va_expired').value;
 var status_expired=document.getElementById('status_expired').value;
 var flag_bayar=document.getElementById('flag_bayar').value;
 var tgl_bayar=document.getElementById('tgl_bayar').value;
 var status_transaksi=document.getElementById('status_transaksi').value;

 xajax_display_server_laporan_tagihan(kode_pelanggan,no_va,tgl_va_expired,status_expired,flag_bayar,tgl_bayar,status_transaksi,page);
}

function download_csv()
{
	 var kode_pelanggan=document.getElementById('kode_pelanggan').value;
	 var no_va=document.getElementById('no_va').value;
	 var tgl_va_expired=document.getElementById('tgl_va_expired').value;
	 var status_expired=document.getElementById('status_expired').value;
	 var flag_bayar=document.getElementById('flag_bayar').value;
	 var tgl_bayar=document.getElementById('tgl_bayar').value;
	 var status_transaksi=document.getElementById('status_transaksi').value;
	 
	 var params="kode_pelanggan="+kode_pelanggan+"&no_va="+no_va+"&tgl_va_expired="+tgl_va_expired+"&status_expired="+status_expired+"&flag_bayar="+flag_bayar+"&tgl_bayar="+tgl_bayar+"&status_transaksi="+status_transaksi;
	 window.open("server_laporan_tagihan_csv.php?"+params);
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
                  <td colspan="2" align="center" valign="middle" class="title_banner">LAPORAN TAGIHAN</td>
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
                            <td height="20" colspan="3">
                                    <a onmouseover="ddrivetip('Download CSV Laporan Transaksi', 90)" onmouseout="hideddrivetip()" href="#" onclick="download_csv()"><img src="../images/csv.png" width="32" height="32" border="0"></a>
                            </td>
                            </tr>
                            <tr>
                              <td align="left">
                                <fieldset>
                                <legend class="tebal">Filter</legend>
                                  <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                      <td width="13%" align="left">Nama Pelanggan</td>
                                      <td width="1%" align="center">:</td>
                                      <td width="86%" align="left">
									  <? 
										$lookup=new Lookup();
										$lookup->name="kode_pelanggan";
										$lookup->id="kode_pelanggan";					
										$lookup->sql="select * from TM_SERVER_API_VA_PELANGGAN order by NAMA_PELANGGAN";
										$lookup->value_field="KODE_PELANGGAN";
										$lookup->list_field="NAMA_PELANGGAN";
										$lookup->default_value="";					
										$lookup->onchange="";
										$lookup->separator=" - ";
										echo $lookup->dropdown();
										?>
										</td>
                                    </tr>
                                    <tr>
                                      <td align="left">Virtual Account</td>
                                      <td align="center">:</td>
                                      <td align="left"><input type="text" name="no_va" id="no_va"></td>
                                    </tr>
                                    <tr>
                                      <td align="left">Tgl VA Expired</td>
                                      <td align="center">:</td>
                                      <td align="left"><input name="tgl_va_expired" type="text" id="tgl_va_expired" size="10" >
                                        <a href="javascript:displayDatePicker('tgl_va_expired',false, 'dmy', '-');"><img src="../images/xcalendar.png" alt="" width="16" height="16" border="0" align="absmiddle" ></a></td>
                                    </tr>
                                    <tr>
                                      <td align="left">Status Expired</td>
                                      <td align="center">:</td>
                                      <td align="left">
                                      		<select id="status_expired" name="status_expired">
                                            	<option value="">--Select--</option>
                                            	<option value="Y">Sudah Expired</option>
                                            	<option value="T">Belum Expired</option>
                                            </select>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left">Status Bayar</td>
                                      <td align="center">:</td>
                                      <td align="left">
                                        <select id="flag_bayar" name="flag_bayar">
                                          <option value="">--Select--</option>
                                          <option value="Y">Sudah Bayar</option>
                                          <option value="T">Belum Bayar</option>
                                          </select>
                                        </td>
                                    </tr>
                                    <tr>
                                      <td align="left">Tgl Bayar</td>
                                      <td align="center">:</td>
                                      <td align="left"><input name="tgl_bayar" type="text" id="tgl_bayar" size="10" >
                                        <a href="javascript:displayDatePicker('tgl_bayar',false, 'dmy', '-');"><img src="../images/xcalendar.png" alt="" width="16" height="16" border="0" align="absmiddle" ></a></td>
                                    </tr>
                                    <tr>
                                      <td align="left">Status Transaksi</td>
                                      <td align="center">:</td>
                                      <td align="left">
                                      		<select id="status_transaksi" name="status_transaksi">
                                            	<option value="">--Select--</option>
                                            	<option value="OPEN">Open</option>
                                            	<option value="CLOSE" >Close</option>
                                            </select>
                                      </td>
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
                                  </table>
                                </fieldset></td>
                             </table>
                             </td>
                          </tr>
                          <tr>
                          	<td>
                            <table width="100%" >
                          	  <tr class="table_header">
                                    <td width="34" align="center">No</td>
                                    <td width="150" align="center">Nama Pelanggan</td>
                                    <td width="107" align="center">Jenis Bisnis</td>
                                    <td width="92" align="center">Virtual Account</td>
                                    <td width="94" align="center">Nama Tertagih</td>
                                    <td width="94" align="center">Total Tagihan</td>
                                    <td width="181" align="center">Keterangan</td>
                                    <td width="85" align="center">Tgl VA Expired</td>
                                    <td width="91" align="center">Status Expired</td>
                                    <td width="93" align="center">Status Transaksi</td>
                                    <td width="60" align="center">Status Bayar</td>
                                    <td width="60" align="center">Tgl Bayar</td>
                                    <td width="60" align="center">Tgl Download</td>
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
</html>
