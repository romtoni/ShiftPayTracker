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
 var stambuk=document.getElementById('stambuk').value;
 var kode_kegiatan=document.getElementById('kode_kegiatan').value;
 var tgl_kegiatan=document.getElementById('tgl_kegiatan').value;
 var no_va=document.getElementById('no_va').value;
 
 xajax_display_client_laporan_tagihan(stambuk,kode_kegiatan,tgl_kegiatan,no_va,page);
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
                                  </table>
                                </fieldset></td>
                             </table>
                             </td>
                          </tr>
                          <tr>
                          	<td>
                            <table width="100%" >
                          	  <tr class="table_header">
                          	    <td width="24" rowspan="2" align="center">No</td>
                          	    <td colspan="2" align="center">Pegawai</td>
                          	    <td colspan="5" align="center">Kegiatan</td>
                          	    <td colspan="2" align="center">Virtual Account</td>
                          	    <td width="55" rowspan="2" align="center">Status Transaksi</td>
                          	    <td width="43" rowspan="2" align="center">Tgl Bayar</td>
                        	    </tr>
                          	  <tr class="table_header">
                          	    <td width="51" align="center">Stambuk</td>
                          	    <td width="173" align="center">Nama</td>
                          	    <td width="32" align="center">Kode</td>
                          	    <td width="164" align="center">Nama</td>
                          	    <td width="48" align="center">Tanggal</td>
                          	    <td width="57" align="center">Biaya</td>
                          	    <td width="145" align="center">Keterangan</td>
                          	    <td width="85" align="center">No</td>
                          	    <td width="66" align="center">Tgl Expired</td>
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
