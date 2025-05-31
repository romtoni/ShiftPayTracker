<?
include('../auth.php');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<link rel="icon" href="../images/icon.gif">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="stylesheet" type="text/css" href="../tooltip.css" />
<script language="JavaScript" src="../tooltip.js"></script>
<link rel="icon" href="../favicon.ico">
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
          <? require_once("../inc_menu.php"); ?>
          <tr>
            <td align="center" valign="middle" ><p>&nbsp;</p>
              <p>&nbsp;</p>
              <p class="form_title"><img src="../images/under_construction.jpg" alt="" width="430" height="218" align="top"></p>
              <p class="form_title">&nbsp;</p></td>
          </tr>
          <? include("../inc_footer.php"); ?>
        </table></td>
        <td width='1%' background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
      </tr>
    </table>
	
	</td>
  </tr>
</table>
</body> 
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>