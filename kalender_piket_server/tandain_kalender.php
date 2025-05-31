<?
include('../auth.php');
include("../server.php");
include("../lib.php");
include('../auth_role.php');
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
$year="";
$msg="";

if (isset($_REQUEST["year"])) $year = $_REQUEST["year"];
if ($year == "") $year = date('Y');

	
$stambuk_default = $_SESSION["server_user"];
$strsql="select nama, upah from REF_PEGAWAI WHERE STAMBUK = '$stambuk_default'";
$q=oci_parse($conn,$strsql);
oci_execute($q);
$row=oci_fetch_assoc($q);
$nama_default = $row["NAMA"];
$upah_default = $row["UPAH"];

function cek_bulan($bulan_param)
{
	switch($bulan_param)
	{
		case "01" : $bulan_text = "JANUARI"; break;
		case "02" : $bulan_text = "FEBRUARI"; break;
		case "03" : $bulan_text = "MARET"; break;
		case "04" : $bulan_text = "APRIL"; break;
		case "05" : $bulan_text = "MEI"; break;
		case "06" : $bulan_text = "JUNI"; break;
		case "07" : $bulan_text = "JULI"; break;
		case "08" : $bulan_text = "AGUSTUS"; break;
		case "09" : $bulan_text = "SEPTEMBER"; break;
		case "10" : $bulan_text = "OKTOBER"; break;
		case "11" : $bulan_text = "NOVEMBER"; break;
		case "12" : $bulan_text = "DESEMBER"; break;
		default : $bulan_text = "BULAN TIDAK VALID"; break;
	}
	
	return $bulan_text;
}

function display()
{
	global $conn, $year, $stambuk_default;
	
	$data="";
	$no="";
	$xtahun_piket="";
	$xbulan_piket ="";
	
	$strsql="select 
				count(*) as jumlah
			from KALENDER_PIKET 
			WHERE 
				to_char(TGL_PIKET, 'YYYY') = '$year'
				and stambuk = '$stambuk_default'
			order by tgl_piket desc";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$jumlah_data = $row["JUMLAH"];
	
	if ($jumlah_data == 0)
	{
		$data.= "<tr bgcolor='008000'>
						<td align='center' colspan='7'>
							<a href='#' onclick='prev_year($year);'><img src='../images/previous_blue_32.png'></a>
							<font color='yellow' size='50'>$year</font>
							<a href='#' onclick='next_year($year);'><img src='../images/next_blue_32.png'></a>
						</td>
				</tr>"; 
	} 
	else
	{
		$strsql="select 
					ID,
					STAMBUK,
					NAMA,
					to_char(TGL_PIKET, 'dd-mm-yyyy') as xTGL_PIKET,
					to_char(TGL_PIKET, 'mm') as xBLN_PIKET,
					to_char(TGL_PIKET, 'yyyy') as xTHN_PIKET,
					SUDAH_DIBAYAR,
					UPAH
				from KALENDER_PIKET 
				WHERE 
					to_char(TGL_PIKET, 'YYYY') = '$year'
					and stambuk = '$stambuk_default'
				order by tgl_piket desc";
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
		$data="";
		while ($row=oci_fetch_assoc($q)) 
		{
			$no++;
			$id = $row["ID"];
			$stambuk = $row["STAMBUK"];
			$nama = $row["NAMA"];
			$tgl_piket = $row["XTGL_PIKET"];
			$sudah_dibayar = $row["SUDAH_DIBAYAR"];
			
			if ($sudah_dibayar == "Y") 
			{
				$ket_sudah_dibayar = "Sudah Dibayar";
				if(fmod($no,2)==0) $class="baris2"; else $class="baris1";
			}
			else if ($sudah_dibayar == "N") 
			{
				$ket_sudah_dibayar = "Belum Dibayar";
				$class="baris3";;
			}
			
			$upah = number_format($row["UPAH"],0,',','.');
			
			$bulan_piket = $row["XBLN_PIKET"];
			$bulan_text = cek_bulan($bulan_piket);
			$tahun_piket = $row["XTHN_PIKET"];
			
			$pg="tandain_kalender_del.php?id=$id";	
			$adel="<a onMouseOver=\"ddrivetip('Hapus', 30)\" onMouseOut=\"hideddrivetip()\"  href='javascript:confirm_del(\"$pg\");'><img src='../images/img_del.png' border='0'></a>";	
			$aedit="<a onMouseOver=\"ddrivetip('Edit', 30)\" onMouseOut=\"hideddrivetip()\" href='tandain_kalender_edit.php?id=$id'><img src='../images/img_edit.png' border='0'></a>&nbsp;";		
			
			if ($tahun_piket != $xtahun_piket)
			{
				$data.= "<tr bgcolor='008000'>
							<td align='center' colspan='7'>
								<a href='#' onclick='prev_year($year);'><img src='../images/previous_blue_32.png'></a>
								<font color='yellow' size='50'>$tahun_piket</font>
								<a href='#' onclick='next_year($year);'><img src='../images/next_blue_32.png'></a>
							</td>
						</tr>"; 
			}
			
			if ($bulan_piket != $xbulan_piket)
			{
				$data.= "<tr bgcolor='orange'>
							<td align='center' colspan='7'><h3>$bulan_text</h3></td>
						</tr>"; 
			}
			
			$data.= "<tr class='$class'>";
			$data.= "<td align='center'>$no</td>"; 
			$data.= "<td align='center'>$stambuk</td>";
			$data.= "<td align='left'>$nama</td>";
			$data.= "<td align='center'>$tgl_piket</td>";
			$data.= "<td align='left'>$ket_sudah_dibayar</td>";
			$data.= "<td align='right'>$upah</td>";
			$data.= "<td align='center'>$aedit $adel</td></tr>";
			
			$xbulan_piket = $bulan_piket;
			$xtahun_piket = $tahun_piket;
		}
	}
  	echo $data;
}

function save()
{
	global $conn, $msg;
	
	$stambuk=$_POST["stambuk"];
	$nama=$_POST["nama"];
	$tgl_piket=$_POST["tgl_piket"];
	$sudah_dibayar=$_POST["sudah_dibayar"];
	$upah=$_POST["upah"];
	
	$strsql = "select count(*) as jumlah from KALENDER_PIKET where stambuk = '$stambuk' and to_char(tgl_piket,'dd-mm-yyyy') = '$tgl_piket'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$row = oci_fetch_assoc($q);
	$jumlah_data = $row["JUMLAH"];
	
	if ($jumlah_data > 0 )	$msg = "Data untuk stambuk $stambuk pada tanggal $tgl_piket sudah ada!";
	else
	{
		$strsql="insert into KALENDER_PIKET(stambuk, nama, tgl_piket, sudah_dibayar, upah) 
				 values('$stambuk', '$nama', to_date('$tgl_piket','dd-mm-yyyy'), '$sudah_dibayar', $upah) ";
				//echo $strsql;
		if($stambuk!="" and $nama !="" and $tgl_piket !="" and $sudah_dibayar !="" and $upah !="") 
		{
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
			$msg = "Data berhasil disimpan!";
		}
		else
		{
			$msg = "Isi Seluruh data!";
		}
	}
}
if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") save();


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

<link rel="stylesheet" type="text/css" href="../jquery/jquery-ui.css" />
        <script language="JavaScript" src="../jquery/jquery.js"></script>
        <script language="JavaScript" src="../jquery/jquery-ui.js"></script>
        <script language="JavaScript" src="../jquery/validate.js"></script>
<link rel="stylesheet" type="text/css" href="../datepicker.css"/>
<script language="JavaScript" src="../datepicker.js"></script>
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

</head>
<body>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
    <td width='98%'>
    <table width="100%"  border="0" class="outside_border">
        <tr>
          <td align="left" valign="middle"><? require_once("../inc_banner.php"); ?></td>
        </tr>
        <? require_once("../inc_bawahbanner.php"); ?>
        <? require_once("../inc_menu.php"); ?>
        <tr>
        	<td width="100%" align="center" class="title_banner">INPUT TANGGAL</td>
        </tr>
        <tr>
          <td align="left" valign="top" >
          <table width="100%" border="0">
            <tr>
              <td width="14%" align="left" valign="top"><? require_once("inc_menu_kalender_piket.php"); ?></td>
              <td width="86%" align="left" valign="top" >
			  <form action="" method="post" name="myform" id="myform">
			  <table width="100%" cellpadding="2"  cellspacing="1" >
			    <tr align="left" >
			      <td colspan="9" bordercolor="#999999" >
                  <table width="830" cellpadding="2"  cellspacing="2" class=" outside_border" >
			        <tr class="table_header">
			          <td  width="3%" align="center" bordercolor="#999999" >No</td>
			          <td  width="9%" align="center" bordercolor="#999999" >Stambuk</td>
			          <td  width="36%" align="center" >Nama</td>
			          <td  width="23%" align="center" >Tgl Piket</td>
			          <td  width="14%" align="center" >Pembayaran</td>
			          <td  width="8%" align="center" >Uang Piket</td>
			          <td  width="7%" align="center" >Action</td>
			          </tr>
			        <tr >
			          <td align="center" bordercolor="#999999" >&nbsp;</td>
			          <td align="center" bordercolor="#999999" ><input name="stambuk" type="text"  id="stambuk" size="10" maxlength="4" value="<?=$stambuk_default;?>" readonly></td>
			          <td align="left" ><input name="nama" type="text"  id="nama" size="50" value="<?=$nama_default;?>" readonly></td>
			          <td align="left" >
                      	<input name="tgl_piket" type="text" id="tgl_piket" size="10" readonly>
                      	<a href="javascript:displayDatePicker('tgl_piket',false, 'dmy', '-');"><img src="../images/xcalendar.png" alt="" width="16" height="16" border="0" align="absmiddle" ></a>
                      </td>
			          <td align="left" ><select name="sudah_dibayar" id="sudah_dibayar"> 
                      						<option value="">--Select--</option>
                      						<option value="N">Belum Dibayar</option>
                      						<option value="Y">Sudah Dibayar</option>
                                        </select>
                                        
                                        </td>
			          <td align="left" ><input name="upah" type="text" class="required" id="upah" size="10" value="<?=$upah_default;?>"></td>
			          <td align="center" ><input name="btnsubmit" type="submit" id="btnsubmit" value="Submit"></td>
			          </tr>
			        <?  display(); ?>
			        </table>

                    </td>
			      </tr>
			    </table>
                  </form>

                </td>
            </tr>
          </table>            </td>
        </tr>
        <? include("../inc_footer.php"); ?>
    </table></td>
    <td width='1%' background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
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
