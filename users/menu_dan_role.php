<?
include('../auth.php');
include("../server.php");
include("../lib.php");
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
$msg="";
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
<link rel="stylesheet" href="../example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="../example-print.css" TYPE="text/css" MEDIA="print">
<script type="text/javascript" src="../tabber_nav.js"></script>
<script language="JavaScript" src="../lib.js"></script>
<script type="text/javascript" src="../tabber.js"></script>
<SCRIPT LANGUAGE="JavaScript" src="../sms.js"></script>

<link rel="stylesheet" type="text/css" href="../jquery/jquery-ui.css" />
        <script language="JavaScript" src="../jquery/jquery.js"></script>
        <script language="JavaScript" src="../jquery/jquery-ui.js"></script>
        <script language="JavaScript" src="../jquery/validate.js"></script>
<?php $xajax->printJavascript('xajax/'); ?>
<script>
function display()
{
	var img="<img src='../images/loader.gif'>";
	document.getElementById('loader').innerHTML=img;
	//var menu_utama = document.getElementById("menu_utama").value;
	var sub_menu = document.getElementById("sub_menu").value;
	if (sub_menu == "") { alert ("Pilih Menu!"); document.getElementById('loader').innerHTML=""; }
	else xajax_display_menu_role(sub_menu);
}

function cek_semua()
{
	var cek_all= document.getElementById('cek_all').checked;
	var nrow= document.getElementById("nrow").value; 
	var i;
		
	if (cek_all == true)
	{
		for (i=1;i<=nrow;i++) 
		{
			document.getElementById('cek'+i).checked=true;	
		}
			
	}
	
	if (cek_all == false)
	{
		for (i=1;i<=nrow;i++)
			document.getElementById('cek'+i).checked=false;	
	}
}

function simpan_perubahan()
{
	var nrow= document.getElementById("nrow").value; 
	var i;
	var role_terpilih;
	var kode_role;
	var kode_menu;
	var isselect;
		
	for (i=1;i<=nrow;i++) 
	{
		kode_menu= document.getElementById("kode_menu"+i).value; 
		kode_role= document.getElementById("kode_role"+i).value; 
	
		role_terpilih=document.getElementById('cek'+i).checked;	
		if (role_terpilih==true) isselect="Y";
		else if (role_terpilih==false) isselect="N";
		
		xajax_simpan_perubahan(kode_menu, kode_role, isselect);
	}
	xajax_display_menu_role(kode_menu);
	alert("Perubahan Berhasil Disimpan!");
}
</script>
</head>
<body>
<div id="loader" align="center"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width='20px' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
    <td width='960px'><table width="100%"  border="0" align="center" class="outside_border">
        <tr>
          <td align="center" valign="middle"><? require_once("../inc_banner.php"); ?></td>
        </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
        <? require_once("../inc_menu.php"); ?>
                    <tr>
                      <td width="100%" align="center" class="title_banner"> MONITORING MENU</td>
              </tr>
        <tr>
          <td align="center" valign="top">
          <form name="form1" id="form1" method="post" action="">
          <input type='hidden' name="nrow" id="nrow">
            <table width="100%" border="0">
              <tr>
                <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                <td width="891" align="left" valign="top" class="left_border">
                <table width="100%" height="111"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="80" valign="top" bordercolor="#999999"><table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <tr>
                          <td align="left">Menu Utama</td>
                          <td align="center" >:</td>
                          <td align="left" >
						  			<? 
                                    $lookup=new Lookup();
                                    $lookup->name="menu_utama";
                                    $lookup->id="menu_utama";					
                                    $lookup->sql="select * from MN_MENU WHERE KODE_PARENT IS NULL ORDER BY KODE_MENU";
                                    $lookup->value_field="KODE_MENU";
                                    $lookup->list_field="KODE_MENU/NAMA_MENU";
                                    $lookup->separator=" - ";
                                    $lookup->default_value="";					
                                    $lookup->onchange="xajax_display_submenu(this.value)";
                                    echo $lookup->dropdown();
                                    ?>                                    </td>
                        </tr>
                        <tr>
                          <td align="left">Sub Menu</td>
                          <td align="center" >:</td>
                          <td align="left" >
                          		<SELECT name="sub_menu" id="sub_menu">
                                	<option value="">--Select--</option>
                                </SELECT>                          </td>
                        </tr>
                        <tr>
                          <td  width="99" align="left">&nbsp;</td>
                          <td  width="4" align="center" >&nbsp;</td>
                          <td  width="755" align="left" ><input type='button' id='btn_display' onClick="display()" value="Display"></td>
                        </tr>
                        <tr>
                          <td colspan="3" align="left">
								<label id="id_menu_terpilih">&nbsp;</label>
                          </td>
                          </tr>
                        <tr>
                          <td colspan="3" align="left">
                          <table width="100%" border="0" >
                          	<thead>
                            <tr class="table_header">
                              <td width="1%" align="center"><input type='checkbox' name='cek_all' id='cek_all' onClick="cek_semua()"></td>
                              <td width="6%" align="center">No</td>
                              <td width="6%" align="center">Selected</td>
                              <td width="7%" align="center">Kode Role</td>
                              <td width="12%" align="center">Nama Role</td>
                              <td width="8%" align="center">Kode Menu</td>
                              <td width="26%" align="center">Nama Menu</td>
                              <td width="34%" align="center">Link</td>
                            </tr>
                        	</thead>
                            <tbody id="mytable">
                        	</tbody>
                          </table>                          </td>
                          </tr>

                      
                        
                      </table>
                      </td>
                    </tr>
                </table></td>
              </tr>
            </table>
              </form></td>
        </tr>
        <? include("../inc_footer.php"); ?>
    </table></td>
    <td width='1%' align="center" valign="top" background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
  </tr>
  
</table></tr>
    </table>    </td>
<script type="text/javascript">

/* Since we specified manualStartup=true, tabber will not run after
   the onload event. Instead let's run it now, to prevent any delay
   while images load.
*/

tabberAutomatic(tabberOptions);

</script>

</body>
 
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>
