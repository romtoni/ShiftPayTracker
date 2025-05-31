<?
include('../auth.php');
include("../server.php");
include("../lib.php");
include("xajax_common.php");
//session_start();
$msg="";
function delete()
{
	global $conn;
	
	$nrow=$_POST["nrow"];
	for($i=1; $i<=$nrow; $i++) 
	{
 		$kode_role=$_POST["cek$i"];
  		if($kode_role!="")
		{
			$strsql="BEGIN DELETE_ROLE_TAKTERPAKAI('$kode_role'); END;";
			//echo $strsql;
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
		}
	}
	header("location:cek_role_tak_terpakai.php");
	
}


function open()
{
	global $conn, $jumlah, $datatmpl;
	
	$strsql="select count(*) as JUMLAH from V_CEKROLE_TAKTERPAKAI order by ROLE_TAK_TERPAKAI";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$row=oci_fetch_assoc($q); 
	$jumlah=$row["JUMLAH"];
	
	if ($jumlah==0)
	{
		$datatmpl.="<tr class='baris2'>";
		$datatmpl.="<td align='center' colspan='3'><h3>Tidak Ada Data</h3></td>";
		$datatmpl.="</tr>";
	}
	else
	{
		$strsql="	select
						KODE_ROLE, 
						ROLE_TAK_TERPAKAI 
					from 
						V_CEKROLE_TAKTERPAKAI 
					order by 
						ROLE_TAK_TERPAKAI";
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
		$no="";
		while ($row=oci_fetch_assoc($q)) 
		{
			$no++;
			$kode_role = $row["KODE_ROLE"];
			$role_tak_terpakai = $row["ROLE_TAK_TERPAKAI"];
			
			$datatmpl.="<tr>";
			$datatmpl.="<td align='center'><input type='checkbox' value='$kode_role' name='cek$no' id='cek$no'></td>";
			$datatmpl.="<td align='center'>$no</td>";
			$datatmpl.="<td align='center'>$role_tak_terpakai</td>";
			$datatmpl.="</tr>";
		}
		$datatmpl.="<tr>
           		<td align='left' colspan='3'>
           		 <input name='btnsubmit' id='btnsubmit' type='submit' class='button' value='Hapus Role'>
				</td>
            </tr>";
	}

	
	//echo $data;
}

if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") delete();
open();
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
</script>
</head>
<body onload='display()'>
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
                      <td width="100%" align="center" class="title_banner">CEK ROLE TAK TERPAKAI</td>
                    </tr>
        <tr>
          <td align="center" valign="top">
          <form name="form1" id="form1" method="post" action="">
          <input type='hidden' name="nrow" id="nrow" value="<?=$jumlah?>">
            <table width="100%" border="0">
              <tr>
                <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_users.php"); ?></td>
                <td width="891" align="left" valign="top" class="left_border">
                <table width="428" height="111"  border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="80" valign="top" bordercolor="#999999"><table width="243" border="0" cellpadding="2" cellspacing="2">
                        <thead>
                        <tr class="table_header">
                          <td  width="28" align="center" bordercolor="#999999"><input name="cek_all" type="checkbox" id="cek_all" onClick="cek_semua();" value="Y"></td>
                          <td  width="19" align="center">No</td>
                          <td  width="176" align="center" >Nama Role</td>
                        </tr>
                        </thead>
                        <tbody id="mytable">
                        	<?=$datatmpl;?>
                        </tbody>
                        
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
