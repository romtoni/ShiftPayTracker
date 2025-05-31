<?
include("../auth.php");
include("../server.php"); 
include("../lib.php"); 
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
 var username=document.getElementById('username').value; 
 var islogin=document.getElementById('islogin').value;
 xajax_display_monitoring_userlog(username,islogin,page);
}

</script>
<!--- GREY BOX -->
<script type="text/javascript">var GB_ROOT_DIR = "./greybox/";</script>
<script type="text/javascript" src="greybox/AJS.js"></script>
<script type="text/javascript" src="greybox/AJS_fx.js"></script>
<script type="text/javascript" src="greybox/gb_scripts.js"></script>
<link href="greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="static_files/help.js"></script>
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
        <td width='20px' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
        <td width='960px'><table width="100%"  border="0" class="outside_border">
            <tr>
              <td align="center" valign="middle"><? include("../inc_banner.php"); ?></td>
            </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
            <? require_once("../inc_menu.php"); ?>
            <tr>
              <td align="center" valign="middle" ><table width="100%"  border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td colspan="2" align="center" valign="middle" class="title_banner" >MONITORING USERLOG</td>
                </tr>
                <tr>
                  <td width="18%" valign="top" align="left"><?php include("inc_menu_users.php");?></td>
                  <td width="82%" align="center" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top">
                  		<!-- <a onmouseover="ddrivetip('Download Invalid Data', 55)" onmouseout="hideddrivetip()" href="javascript:cetak_excel();"><img src="../images/excel.png" alt="" width="32" height="32" border="0"></a> -->
                      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr align="left" >
                          <td valign="top">
                          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="left">
                                <fieldset>
                                <legend class="tebal">Filter</legend>
                                  <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                      <td width="13%" align="left">Username</td>
                                      <td width="1%" align="center">:</td>
                                      <td width="86%" align="left">
                                        <input type="text" name="username" id="username" size="30" value="">
                                      </td>
                                    </tr>
                                    <tr>
                                      <td width="13%" align="left">Status Login</td>
                                      <td width="1%" align="center">:</td>
                                      <td width="86%" align="left">
											<select name="islogin" id="islogin">
                                            	<option value="">--Select--</option>
                                                <option value="N">Tidak</option>
                                                <option value="Y">Ya</option>
                                            </select>

                                      </td>
                                    </tr>


                                    <tr>
                                      <td align="left"><span class="padding_left4">Halaman</span></td>
                                      <td colspan="2"><span style="float:left ">
                                        <select name="page" onChange="display()" id="page">
                                          <? lookup_page($npage,$page_item); ?>
                                        </select>
                                      </span>
                                        <input name="btnprev" type="button" id="btnprev" onClick="this.form.page.value--; display();" value="Prev">
                                        <input name="btnnext" type="button" id="btnnext" onClick="this.form.page.value++; display();" value="Next">
                                        Total
                                        <input name="frek" type="text" class="numeric_textbox" id="frek" size="10" readonly>
                                        records
                                        <input type="button" name="Button" value="Display" onClick="page.value='1'; display()">
                                        <label id="lbl_btnhistory"></label>
                                        <!--<input name="cx_semua" type="checkbox" id="cx_semua" value="y">
                                        Tampilkan semua record--></td>
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
                          	  <tr class='table_header'>
                          	    <td width="3%" rowspan="2" align='center'>No</td>
                          	    <td width="13%" rowspan="2" align='center'>Username</td>
                          	    <td colspan="2" align='center'>History</td>
                          	    <td width="10%" rowspan="2" align='center'>IP Address</td>
                          	    <td colspan="2" align='center'>Status</td>
                          	    <td colspan="3" align='center'>History Kicker</td>
                          	    </tr>
                          	  <tr class='table_header'>
                                    <td width="14%" align='center'>Last Login</td>
                                    <td width="14%" align='center'>Last Logout</td>	
                                    <td width="8%" align='center'> Login</td>
                                    <td width="8%" align='center'> Logout</td>
                                    <td width="8%" align='center'>Force Logout</td>
                                    <td width="14%" align='center'>Username</td>
                                    <td width="8%" align='center'>IP Address</td>
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
