<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
//session_start();

$data_kalender="";
$nama="";
$year="";

$lang['January']   = 'January';
$lang['February']  = 'February';
$lang['March']     = 'March';
$lang['April']     = 'April';
$lang['May']       = 'May';
$lang['June']      = 'June';
$lang['July']      = 'July';
$lang['August']    = 'August';
$lang['September'] = 'September';
$lang['October']   = 'October';
$lang['November']  = 'November';
$lang['December']  = 'December';

function cek_month($param)
{
	switch($param)
	{
		case "01" : $month_text_now = "January"; break;
		case "02" : $month_text_now = "February"; break;
		case "03" : $month_text_now = "March"; break;
		case "04" : $month_text_now = "April"; break;
		case "05" : $month_text_now = "May"; break;
		case "06" : $month_text_now = "June"; break;
		case "07" : $month_text_now = "July"; break;
		case "08" : $month_text_now = "August"; break;
		case "09" : $month_text_now = "September"; break;
		case "10" : $month_text_now = "October"; break;
		case "11" : $month_text_now = "November"; break;
		case "12" : $month_text_now = "December"; break;
		default : $month_text_now = "Invalid Month"; break;
	}
	
	return $month_text_now;
}

//$date_now = date('d');
//$month_text_now = date('F'); //text

if (isset($_GET["year"])) $year = $_GET["year"];
if ($year =="") $year = date('Y');
$kd_userlogin = $_SESSION["server_user"];

$absen = array();
$strsql = "SELECT    COUNT(*) AS JMLDATA
			  FROM   KALENDER_PIKET
			 WHERE   TO_CHAR (TGL_PIKET, 'yyyy') = '$year' AND STAMBUK = '$kd_userlogin'";
$q=oci_parse($conn,$strsql);
oci_execute($q);
$itmp = 0;
$row=oci_fetch_assoc($q);
$jmldata=$row["JMLDATA"];

if ($jmldata==0)
{
$strsql = "SELECT    STAMBUK,
					 NAMA,
					 NULL AS XTGL_PIKET,
					 NULL AS SUDAH_DIBAYAR,
					 NULL AS UPAH,
					 NULL AS TGL_PIKET
			  FROM   REF_PEGAWAI
			 WHERE   STAMBUK = '$kd_userlogin'";
}
else
{
$strsql = "SELECT    STAMBUK,
					 NAMA,
					 TO_CHAR (TGL_PIKET, 'dd-mm-yyyy') AS XTGL_PIKET,
					 SUDAH_DIBAYAR,
					 UPAH,
					 TGL_PIKET
			  FROM   KALENDER_PIKET
			 WHERE   TO_CHAR (TGL_PIKET, 'yyyy') = '$year' AND STAMBUK = '$kd_userlogin'";
}
//echo $strsql;
$q=oci_parse($conn,$strsql);
oci_execute($q);
$itmp = 0;
while($row=oci_fetch_assoc($q))
{
	$stambuk = $row["STAMBUK"];
	$nama = $row["NAMA"];
	$tgl_piket = $row["XTGL_PIKET"];
	$sudah_dibayar = $row["SUDAH_DIBAYAR"];
	$upah = $row["UPAH"];
							
	$date_now = substr($tgl_piket,0,2);
	$month_text_now = cek_month(substr($tgl_piket,3,2));
							
	$text = $month_text_now."|".$sudah_dibayar."|".$tgl_piket;
	$absen[$itmp]=array($date_now=>$text);

	$itmp+=1;
}


$data_kalender.= "<table border='0' width='100%' cellpadding='2' cellspacing='0' style='border-collapse: collapse' bordercolor='#C0C0C0'>";
$data_kalender.= "<tr>
					<th >
						<a href='#' onclick='prev_year($year);'><img src='../images/previous_blue_32.png'></a> 
						<font color ='#008000' size='50'>$year</font>
						<a href='#' onclick='next_year($year);'><img src='../images/next_blue_32.png'></a>
					</th>
					<th  colspan='31' align='right'>
						<font color ='#008000' size='50'>$nama</font>
					</th>
				</tr>";

		for ($monthno = 1; $monthno <= 12; $monthno++) 
		{
			$day_in_mth = date('t', mktime(0, 0, 0, $monthno, 1, $year)) ;
			$monthfulltext = date('F', mktime(0, 0, 0, $monthno, 1, $year));
			
			$data_kalender.= "<tr>
								<td bgcolor='#f88f07' width='80' height='25'>
									<b>&nbsp;&nbsp;&nbsp;".strtoupper($lang[$monthfulltext])."</b>
								</td>";
					
			for ($date_of_mth = 1; $date_of_mth <= $day_in_mth; $date_of_mth++) 
			{
					$date_no = date('j', mktime(0, 0, 0, $monthno, $date_of_mth, $year));
					$day_of_wk = date('w', mktime(0, 0, 0, $monthno, $date_of_mth, $year));
					$currentdate = date('d-m-Y', mktime(0, 0, 0, $monthno, $date_of_mth, $year));
					$date_no = strlen($date_no)==1 ? '0'.$date_no : $date_no;
					$linkdate  = $date_no;
                	$warnabg = "#fcf692"; 
					$currentdate_tgl = substr($currentdate,0,2);
			     
					
					foreach($absen as $asb)
                	{
						foreach($asb as $a=>$b)
                    	{
							if($a==$currentdate_tgl)
                        	{
								
								//echo $a."=>".$b."<br>";
								$exp = explode('|',$b);
								$month_text_now = $exp[0];
								$sudah_dibayar = $exp[1];
								$tgl_piketz = $exp[2];
								if (($a == $currentdate_tgl) && ($monthfulltext == $month_text_now)) 
								{
									//UANG SUDAH DITERIMA
									if  ($sudah_dibayar == "Y")
									{
										$warnabg = '#008000'; 
										$text = "Uang piket sudah dibayar";
										$linkdate = '<a href="#" onMouseover="fixedtooltip(\''.$text.'\', this, '.
													'event, \'100px\')" onMouseout="delayhidetip()">'.
													'<font color="#ffffff">'.$date_no.'</font></a>';
									}
									//UANG BELUM DITERIMA
									else if  ($sudah_dibayar == "N")
									{
										$warnabg = '#b5e61d'; 
										$text = "Uang piket belum dibayar";
										$linkdate = '<a href="#" onMouseover="fixedtooltip(\''.$text.'\', this, '.
                                                'event, \'100px\')" onMouseout="delayhidetip()">'.
                                                '<font color="#000000">'.$date_no.'</font></a>';
									}
								}
							}
						}
						
						
					}
					
						//CETAK KALENDER
					if  ($day_of_wk == 0 || $day_of_wk == 6) 
					{
							//sabtu/minggu 
							if($day_of_wk == 0) $alt = 'Minggu';
							if($day_of_wk == 6) $alt = 'Sabtu';
							
							if ($warnabg == '#008000') 
							{
								$warnabg = "#008000";
								$font_color = "#000000";
							}
							else if ($warnabg == '#b5e61d')
							{
								$warnabg = "#b5e61d";
								$font_color = "#000000";
							}
							else 
							{
								$warnabg = "red";
								$font_color = "#ffffff";
							}
							
                    		//$warnabg = $warnabg == '#b5e61d' ? $warnabg:"red";
							
							$data_kalender.= "<td align='center' width='10' bgcolor='$warnabg'>
																				<font color='$font_color'>$linkdate</font>
																				</td>";
					}		
					else 
					{ 	

							$data_kalender.= "<td align='center' width='10' bgcolor='$warnabg'>
														<font color='black'>$linkdate</font>
														</td>";

					}
					
					
			}
			$data_kalender.= "</tr>";
		}
$data_kalender.= "</table>";



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
<script>
function prev_year(param)
{
	var tahun_sbl = eval(param) - eval(1);
	window.location = '?year='+tahun_sbl;
}

function next_year(param)
{
	var tahun_stl = eval(param) + eval(1);
	window.location = '?year='+tahun_stl;
}
</script>
<script type="text/javascript">

/***********************************************
* Fixed ToolTip script- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
* http://www.dynamicdrive.com/dynamicindex5/fixedtooltip.htm
***********************************************/
		
var tipwidth='150px' //default tooltip width
var tipbgcolor='lightyellow'  //tooltip bgcolor
var disappeardelay=250  //tooltip disappear speed onMouseout (in miliseconds)
var vertical_offset="0px" //horizontal offset of tooltip from anchor link
var horizontal_offset="-3px" //horizontal offset of tooltip from anchor link

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="fixedtipdiv" style="visibility:hidden;width:'+tipwidth+';background-color:'+tipbgcolor+'" ></div>')

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide(obj, e, visible, hidden, tipwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (tipwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=tipwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
}
return edgeoffset
}

function fixedtooltip(menucontents, obj, e, tipwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidetip()
dropmenuobj=document.getElementById? document.getElementById("fixedtipdiv") : fixedtipdiv
dropmenuobj.innerHTML=menucontents

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", tipwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
}
}

function hidetip(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidetip(){
if (ie4||ns6)
delayhide=setTimeout("hidetip()",disappeardelay)
}

function clearhidetip(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

</script>
<style type="text/css">

#fixedtipdiv{
position:absolute;
padding: 2px;
border:1px solid black;
font:normal 12px Verdana;
line-height:18px;
z-index:100;
}

</style>
</head>
<body>
<div id="loader" align="center"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
    <td width='98%'><table width="100%"  border="0" align="center" class="outside_border">
        <tr>
          <td align="center" valign="middle"><? require_once("../inc_banner.php"); ?></td>
        </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
        <? require_once("../inc_menu.php"); ?>
        <tr>
        	<td width="100%" align="center" class="title_banner">CEK KALENDER</td>
        </tr>                    

        <tr>
          <td align="center" valign="top">
            <table width="100%" border="0">
              <tr>
                <td width="125" height="97" align="left" valign="top"><? require_once("inc_menu_kalender_piket.php"); ?></td>
                <td width="891" align="center" valign="top" class="left_border">
                    	<?=$data_kalender?>
                        
                </td>
              </tr>
			  <tr>
                <td>&nbsp;</td>
                <td >
                    <table width="100%">
                        <tr>
                            <td nowrap width="1200">&nbsp;</td>
                            <td nowrap height="15" width="12" bgcolor="#008000"></td>
                            <td nowrap height="15" width="64"><font size="1" face="Tahoma">&nbsp;Sudah Dibayar</font></td>
 							<td nowrap height="15" width="12" bgcolor="#b5e61d"></td>
                            <td nowrap height="15" width="64"><font size="1" face="Tahoma">&nbsp;Belum Dibayar</font></td>   
                            <td nowrap height="15" width="12" bgcolor="red"></td>
                            <td nowrap height="15" width="64"><font size="1" face="Tahoma">&nbsp;Sabtu/Minggu</font></td>
                        </tr>
                    </table>
                </td>
                
              </tr>              
            </table>
            
          </td>
        </tr>
        
        <? include("../inc_footer.php"); ?>
    </table></td>
    <td width='1%' align="center" valign="top" background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
  </tr>
</table></tr>
    </table>    </td>
</body>
</html>