<?
include('../auth.php');
include('../server.php');
include('../lib.php');
//session_start();
$msg="Perhatian, harap dibiasakan untuk me-Logout aplikasi sebelum menutup browser / mematikan komputer.\\n\\nTerima Kasih.";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<link rel="icon" href="../images/icon.gif">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="stylesheet" type="text/css" href="../tooltip.css" />
<link rel="icon" href="../favicon.ico">
<script language="JavaScript" src="../tooltip.js"></script>
</head>
<body onLoad="set_tab();set_interval()">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
        <td width='98%'><table width="100%"  border="0" class="outside_border">
          <tr>
            <td align="center" valign="middle"><? include("../inc_banner.php"); ?></td>
          </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
          <? require_once("../inc_menu.php"); ?>
          <tr>
            <td align="center" valign="middle" ><p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p class="form_title"><strong>SELAMAT DATANG</strong>
				<span class="merah_tebal">
                <?=$_SESSION["server_user"]; ?>
                </span>
                <br>
                <strong class="form_title">DI APLIKASI</strong> 
                <strong><em style="color:#999">MONITORING SERVER</em></strong>
                <br>
                <!--<em>AJB BUMIPUTERA 1912 </em>-->
                </p>
              <p class="form_title">&nbsp;</p>
              <p class="form_title">&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p></td>
          </tr>
          <? include("../inc_footer.php"); ?>
        </table></td>
        <td width='1%' background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
      </tr>
    </table>
	
	</td>
  </tr>
</table>

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
