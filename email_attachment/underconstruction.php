<?php
include('../auth.php');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? include("../inc_webtitle.php"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style.css" />
<link rel="stylesheet" type="text/css" href="../tooltip.css" />
<link rel="shortcut icon" href="../favicon.ico" />
<script language="JavaScript" src="../tooltip.js"></script>
<link rel="stylesheet" href="../example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="../example-print.css" TYPE="text/css" MEDIA="print">
<script language="JavaScript" src="../lib.js"></script>


</head>
<body >
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
        <td width='98%'><table width="100%"  border="0" align="center" class="outside_border">
          <tr>
            <td align="center" valign="middle" class="banner"><? require_once("../inc_banner.php"); ?></td>
          </tr>
          <tr>
            <td class="title_banner" align="center">MONITORING SERVER</td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <table width="100%" border="0" >
                <tr>
                  <td height="178" align="left" valign="top">
                      <table width="100%" cellpadding="2"  cellspacing="1" >
                        <tr align="left" >
                          <td bordercolor="#999999" ><div align="center"><img src="../images/under_construction.jpg" alt="" ></div></td>
                        </tr>
                        </table></td>
                  </tr>
              </table>
             </td>
          </tr>
          <? include("../inc_footer.php"); ?>
        </table></td>
        <td width='1%' align="center" valign="top" background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
<? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>