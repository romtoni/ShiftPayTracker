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
 var flag_aktif=document.getElementById('flag_aktif').value;

 xajax_display_server_create_akses_tagihan(kode_pelanggan,flag_aktif,page);
}

function status_akses(id_akses_param, flag_aktif_param)
{
	var id_akses =id_akses_param;
	var flag_aktif = flag_aktif_param;
	var pesan;

	if (flag_aktif == 'Y') pesan= "mengaktifkan";
	else  pesan= "menonaktifkan";
	
	var isok=confirm("Apakah anda yakin untuk "+pesan+" akses pelanggan ini?");
	if (isok==true)
	{
		xajax_edit_status_akses(id_akses, flag_aktif);
		alert("Proses "+pesan+" berhasil dilakukan");
		display();
	}
}

</script>
<!--- GREY BOX -->
<script type="text/javascript">var GB_ROOT_DIR = "./greybox/";</script>
<script type="text/javascript" src="greybox/AJS.js"></script>
<script type="text/javascript" src="greybox/AJS_fx.js"></script>
<script type="text/javascript" src="greybox/gb_scripts.js"></script>
<link href="greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!--- GREY BOX -->
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
                  <td colspan="2" align="center" valign="middle" class="title_banner">CREATE ASKES TAGIHAN</td>
                </tr>
                <tr>
                  <td width="13%" valign="top" align="left"><?php include("inc_menu_tagihan.php");?></td>
                  <td width="87%" align="center" valign="top">
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <TR>
                  	<td>
                    	<a href='server_create_akses_tagihan_add.php' onmouseover="ddrivetip('Tambah Akses', 40)" onmouseout="hideddrivetip()"><img src='../images/xinsert.png'></a>
                    </td>
                  </TR>
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
                                      <td align="left">Status Aktif</td>
                                      <td align="center">:</td>
                                      <td align="left">
                                        <select id="flag_aktif" name="flag_aktif">
                                          <option value="">--Select--</option>
                                          <option value="Y">Aktif</option>
                                          <option value="T">Non Aktif</option>
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
                          	    <td width="46" align="center">No</td>
                          	    <td width="299" align="center">Nama Pelanggan</td>
                          	    <td width="135" align="center">Jenis Bisnis</td>
                          	    <td width="120" align="center">ID Pengenal</td>
                          	    <td width="231" align="center">Kunci Rahasia</td>
                          	    <td width="67" align="center">Flag Aktif</td>
                          	    <td width="86" align="center">Action</td>
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
