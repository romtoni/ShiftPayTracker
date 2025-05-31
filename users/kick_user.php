<?
include('../auth.php');
include('../lib.php');
include('../server.php');
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
$msg="";
function kick_user()
{
	global $conn,$msg, $kode;
	
 	$userlogin_kicker = $_SESSION["server_user"];
//$ip_address_kicker=$_SERVER['REMOTE_ADDR'];
	function get_ip() 
	{
		//Just get the headers if we can or else use the SERVER global
		if(function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}
		else
		{
			$headers = $_SERVER;
		}
	
		//Get the forwarded IP if it exists
		if(array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) )
		{
			$the_ip = $headers['X-Forwarded-For'];
		}
		elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
		{
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
		return $the_ip;
	}

	$ip_address_kicker=get_ip();
	$nrow=$_POST["nrow"];
	$nc=0;
	for($i=1; $i<=$nrow; $i++) 
	{
	  	$username=$_POST["cek$i"];
		
	  	if($username!="")
		{
	 		$strsql="begin UNLOCK_USERLOGIN('$username','$userlogin_kicker','$ip_address_kicker'); end;";
			//echo $strsql;
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
			$nc++;
		}
	}
	
	if($nc>0) $msg="$nc User Berhasil Ditendang";
	else $msg="User Gagal Ditendang";
	oci_close($conn);
	$kode = 1;
}

function tambah_session()
{
	global $conn,$msg, $kode;
	
	$session_baru = time() + (60 * 60);
	
	$nrow=$_POST["nrow"];
	$nc=0;
	for($i=1; $i<=$nrow; $i++) 
	{
	  	$username=$_POST["cek$i"];

	  	if($username!="")
		{
	 		$strsql="begin ADD_SESSION_USERLOGIN('$username', $session_baru); end;";
			//echo $strsql;
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
			$nc++;
		}
	}
	
	//echo $username2;
	if($nc>0) $msg="$nc Session User Berhasil Ditambah 1 JAM";
	else $msg="Session User Gagal Ditambah";
	oci_close($conn);
	$kode = 2;
}

if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") kick_user();
if(isset($_POST["btntambah"]) and $_POST["btntambah"]!="") tambah_session();
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
 xajax_display_user_online(username,page);
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
			}
			else
			document.getElementById('cek'+i).checked=true;	

		}
			
	}
	
	if (cek_all == false)
	{
		for (i=mulai;i<=akhir;i++)
			document.getElementById('cek'+i).checked=false;	
	}
}


document.onkeypress = function noNumbers2(e){
    e = e || window.event;
    var keynum = e.keyCode || e.which;
    if(keynum == 27){
  AJS.AEV(document,"keypress",GB_hide);
  }
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
                  <td colspan="2" align="center" valign="middle" class="title_banner">KICK USER</td>
                </tr>
                <tr>
                  <td width="18%" valign="top" align="left"><?php include("inc_menu_users.php");?></td>
                  <td width="82%" align="center" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
                                      <td width="7%" align="left">Username</td>
                                      <td width="2%" align="center">:</td>
                                      <td width="91%" align="left"><input type="text" name="username" id="username" size="25"></td>
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
                                      <td align="left" colspan="3">
                                      <input name="btnsubmit" id="btnsubmit" type="submit" class="button" value="Kick User">
                                      <input name="btntambah" id="btntambah" type="submit" class="button" value="Tambah Sesi">
										</td>
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
                          	    <td width="38" align="center"><input name="cek_all" type="checkbox" id="cek_all" onClick="cek_semua();" value="Y"></td>
                          	    <td width="21" align="center">No</td>
                          	    <td width="66" align="center">Username</td>
                          	    <td width="282" align="center">Unit Kerja</td>
                          	    <td width="117" align="center">IP Address</td>
                          	    <td width="132" align="center">Login Terakhir</td>
                          	    <td width="112" align="center">Habis Sesi</td>
                          	    <td width="84" align="center">Status Sesi</td>
                          	    <td width="80" align="center">Status Online</td>
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
