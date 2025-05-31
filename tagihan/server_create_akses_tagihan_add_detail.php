<?
include("../auth.php");
include("../server.php");
include("../lib.php");
include("../auth_role.php");
include("../classes/class.lookup.php");
//session_start();
$msg = "";
$kode_pelanggan="";
$nama_pelanggan="";

function open()
{
	global $conn, $id_akses, $kode_pelanggan, $nama_pelanggan, $jenis_bisnis, $kunci_rahasia, $id_pengenal, $data;
	
	if (isset($_GET["id_akses"])) $id_akses = $_GET["id_akses"];
	
	$strsql="SELECT    
					ID_AKSES_DETAIL,
					ID_AKSES,
					NAMA_PELANGGAN,
					JENIS_BISNIS,
					ID_PENGENAL,
					KUNCI_RAHASIA,
					KODE_PELANGGAN,
					FLAG_AKTIF,
					IP_ADDRESS
			FROM 
				V_SERVER_AKSES_TAGIHAN_DETAIL 
			WHERE 
				ID_AKSES = '$id_akses'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	$no="";
	$data="";
	while($row=oci_fetch_assoc($q))
	{
		$no++;
		if(fmod($no,2)==0) $class='baris1'; else $class='baris2';
		$id_akses_detail = $row["ID_AKSES_DETAIL"];
		$kode_pelanggan = $row["KODE_PELANGGAN"];
		$nama_pelanggan = $row["NAMA_PELANGGAN"];
		$jenis_bisnis = $row["JENIS_BISNIS"];
		$kunci_rahasia = $row["KUNCI_RAHASIA"];
		$id_pengenal = $row["ID_PENGENAL"];
		$ip_address = $row["IP_ADDRESS"];
		
		$adel="<a href='server_create_akses_tagihan_del.php?id_akses=$id_akses&ip_address=$ip_address'><img src='../images/img_del.png' onclick='return confirm(\"Apakah anda Yakin??\")'></a>";
		$data.="<tr class='$class'>
				<td align='center'>$no</td>
				<td>$kode_pelanggan</td>
				<td>$nama_pelanggan</td>
				<td>$jenis_bisnis</td>
				<td align='center'>$id_pengenal</td>
				<td align='center'>$kunci_rahasia</td>
				<td>$ip_address</td>				
				<td align='center'>$adel</td>
				<tr>";
	}
}

function save()
{
	global $conn, $msg, $kode;
	$kode="";
	$kode_pelanggan=$_POST["kode_pelanggan"];
	$ip_address=$_POST["ip_address"];
	$userlogin=$_SESSION["server_user"];
	
	if ($kode_pelanggan=="" or $ip_address=="" or $userlogin=="")
	{
		$msg = "Masih ada field yang kosong";
		$kode = "T";
	}
	else
	{
		$strsql="BEGIN SP_SERVER_CREATE_VA_AKSES('$kode_pelanggan','$ip_address','$userlogin'); END;";
		$q=oci_parse($conn,$strsql);
		oci_execute($q);
		
		$msg = "Akses dari IP : $ip_address untuk pelanggan tersebut berhasil dibuat";
		$kode = "Y";
	}
	oci_close($conn);
}


if(isset($_POST["btnsubmit"]) and $_POST["btnsubmit"]!="") save();
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
<script language="JavaScript" src="../lib.js"></script>
<link rel="stylesheet" type="text/css" href="../datepicker.css"/>
<script language="JavaScript" src="../datepicker.js"></script>
</head>
<body>
<div id="loader" align="center"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
                      <td width="100%" align="center" class="title_banner">CREATE AKSES TAGIHAN DETAIL</td>
                    </tr>
        <tr>
          <td align="center" valign="top"><form name="form1" id="form1" method="post" action="">
          
            <table width="100%" border="0">
              <tr>
                <td width="1040" align="left" valign="top" class="left_border">
                <table width="100%" height="111"  border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                      <td height="80" valign="top" bordercolor="#999999">
                      <table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <thead>
                        <tr class="table_header">
                          <td  width="19" align="center" bordercolor="#999999">No</td>
                          <td  width="982" align="center">Kode Pelanggan</td>
                          <td  width="982" align="center">Nama Pelanggan</td>
                          <td  width="982" align="center">Jenis Bisnis</td>
                          <td  width="982" align="center">Id Pengenal</td>
                          <td  width="982" align="center">Kunci Rahasia</td>
                          <td  width="126" align="center">IP Address</td>
                          <td  width="56" align="center" >Action</td>
                        </tr>
                        <tr >
                          <td  align="center"  >&nbsp;</td>
                          <td align="left" >
                          <input type="hidden" name="kode_pelanggan" id="kode_pelanggan" value="<?=$kode_pelanggan?>">
                          <?=$kode_pelanggan?>
                          </td>
                          <td align="left" ><?=$nama_pelanggan?></td>
                          <td align="left" ><?=$jenis_bisnis?></td>
                          <td align="center" ><?=$id_pengenal?></td>
                          <td align="center" ><?=$kunci_rahasia?></td>
                          <td align="left"><input type="text" name="ip_address" id="ip_address" ></td>
                          <td align="center" ><input type="submit" name="btnsubmit" id="btnsubmit" value="Simpan"></td>
                        </tr>
                        </thead>
                        <tbody id="mytable">
                        <?=$data ?>
                        </tbody>
                      </table
                      ></td>
                    </tr>
                </table></td>
              </tr>
            </table>
              </form></td>
        </tr>
        <? include("../inc_footer.php"); ?>
    </table>
</body>
<? 
if($msg!="") 
{
	echo "<script>alert('$msg');</script>"; 
	if ($kode == "Y") echo "<script>window.location.href='server_create_akses_tagihan_add_detail.php?id_akses=$id_akses'</script>"; 
}
?>
</html>