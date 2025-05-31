<?
include('../auth.php');
include("../server.php");
include("../lib.php");
include('../auth_role.php');
include("../classes/class.lookup.php");
include("xajax_common.php");
//session_start();
$msg="";

function display()
{
	global $conn;
	global $id;
	global $stambuk;
	global $nama;
	global $tgl_piket;
	global $sudah_dibayar;
	global $upah;
	global $selected_nothing, $selected_no, $selected_yes;
	
	
	$id=$_GET["id"];	
					
	$strsql="select 
					ID,
					STAMBUK,
					NAMA,
					to_char(TGL_PIKET, 'dd-mm-yyyy') as xTGL_PIKET,
					SUDAH_DIBAYAR,
					UPAH
				from KALENDER_PIKET 
				where id = $id
				order by tgl_piket desc";
				
				//echo $strsql;
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	
	
	$id = $row["ID"];
	$stambuk = $row["STAMBUK"];
	$nama = $row["NAMA"];
	$tgl_piket = $row["XTGL_PIKET"];
	$sudah_dibayar = $row["SUDAH_DIBAYAR"];
	
	if ($sudah_dibayar == "Y") 
	{
		$selected_nothing = "";
		$selected_no = "";
		$selected_yes = "selected";
	}
	else if ($sudah_dibayar == "N") 
	{
		$selected_nothing = "";
		$selected_no = "selected";
		$selected_yes = "";
	}
	else
	{
		$selected_nothing = "selected";
		$selected_no = "";
		$selected_yes = "";
	}
	
	$upah = $row["UPAH"];
}


function update()
{
	global $conn, $msg, $kode;
	
	$id = $_POST["id"];
	$stambuk=$_POST["stambuk"];
	$nama=$_POST["nama"];
	$tgl_piket=$_POST["tgl_piket"];
	$sudah_dibayar=$_POST["sudah_dibayar"];
	$upah=$_POST["upah"];
	
	$strsql = "select count(*) as jumlah from KALENDER_PIKET 
				where 
					stambuk = '$stambuk' 
					and to_char(tgl_piket,'dd-mm-yyyy') = '$tgl_piket'
					and sudah_dibayar = '$sudah_dibayar'
					and upah = $upah";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$row = oci_fetch_assoc($q);
	$jumlah_data = $row["JUMLAH"];
	
	if ($jumlah_data > 0 )	$msg = "Data tidak ada yg berubah.\\n Atau data untuk stambuk $stambuk pada tanggal $tgl_piket sudah ada!";
	else
	{
	
		$strsql="update KALENDER_PIKET 
					set 
						STAMBUK = '$stambuk', 
						NAMA = '$nama',
						TGL_PIKET = to_date('$tgl_piket','dd-mm-yyyy'),
						SUDAH_DIBAYAR ='$sudah_dibayar',
						UPAH = $upah
					where id=$id";
			// echo $strsql;
			
		if($stambuk!="" and $nama !="" and $tgl_piket !="" and $sudah_dibayar !="" and $upah !="") 
		{
			$q=oci_parse($conn,$strsql);
			oci_execute($q);
			$msg = "Data berhasil disimpan!";
			$kode = 1;
			header("location:tandain_kalender.php");
		}
		else
		{
			$msg = "Isi Seluruh data!";
			$kode = 0;
		}
	}
}


if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") update();
display();
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
        	<td width="100%" align="center" class="title_banner">INPUT TANGGAL &gt; EDIT</td>
        </tr>
        <tr>
          <td align="left" valign="top" >
			<table width="100%" border="0">
            <tr>
              <td width="151" align="left" valign="top"><? require_once("inc_menu_kalender_piket.php"); ?></td>
              <td width="1069" align="left" valign="top" >
			  <form action="" method="post" name="myform" id="myform">
  			  <input type="hidden" name="id" id="id" value="<?=$_GET["id"];?>">
                <table width="100%" cellpadding="2"  cellspacing="1">
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
                          <td align="center" bordercolor="#999999" ><input name="stambuk" type="text"  id="stambuk" size="10" maxlength="4" value="<?=$stambuk;?>" readonly></td>
                          <td align="left" ><input name="nama" type="text"  id="nama" size="50" value="<?=$nama;?>" readonly></td>
                          <td align="left" >
                            <input name="tgl_piket" type="text" id="tgl_piket" value="<?=$tgl_piket?>" size="10" readonly>
                            <a href="javascript:displayDatePicker('tgl_piket',false, 'dmy', '-');"><img src="../images/xcalendar.png" alt="" width="16" height="16" border="0" align="absmiddle" ></a>
                          </td>
                          <td align="left" ><select name="sudah_dibayar" id="sudah_dibayar"> 
                                                <option value="" <?=$selected_nothing?>>--Select--</option>
                                                <option value="N" <?=$selected_no?>>Belum Dibayar</option>
                                                <option value="Y" <?=$selected_yes?>>Sudah Dibayar</option>
                                            </select>
                                            
                                            </td>
                          <td align="left" ><input name="upah" type="text" class="required" id="upah" size="10" value="<?=$upah;?>"></td>
                          <td align="center" ><input name="btnsubmit" type="submit" id="btnsubmit" value="Submit"></td>
                          </tr>
                        </table>
                  
                  </td>
                      </tr>
                </table>
              </form>
              </td>
            </tr>
			</table>            
          </td>
        </tr>
        <? include("../inc_footer.php"); ?>
    </table>
	</td>
    <td width='1%' background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
  </tr>
</table>
</body>
 
<? 
if($msg!="") echo "<script>alert('$msg');</script>"; 

?>
</html>
