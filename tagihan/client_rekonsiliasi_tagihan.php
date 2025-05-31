<?
include('../auth.php');
include('../lib.php');
include('../server.php');
include("../classes/class.lookup.php");

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

//session_start();
set_time_limit(360000);
$msg="";
$sheet_ke="1";
$valid_file="";
global $data_tbl;
function upload()
{
	global $conn, $msg, $file_excel, $data_tbl;
	
	$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	
	$file_excel = $_FILES["file_excel"]["name"];
	$file_excel_tmp = $_FILES["file_excel"]["tmp_name"];
	$file_excel_error = $_FILES["file_excel"]["error"];
	$file_excel_type = $_FILES['file_excel']['type'];
	$userlogin = $_SESSION["server_user"];
	
	if(isset($file_excel) && in_array($file_excel_type, $file_mimes)) 
	{
		$arr_file = explode('.', $file_excel);
		$extension = end($arr_file);
	 
	 	switch($extension)
		{
			case 'csv' 	: $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv(); $valid_file = "Y"; break;
			//case 'xls' 	: $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls(); $valid_file = "Y"; break;
			//case 'xlsx' : $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); $valid_file = "Y"; break;		
			default 	: $valid_file = "T"; break;		 
		}
		
		if ($valid_file == "Y")
		{
			$data_tbl=read_file("tmp_import_client_rekonsiliasi_tagihan_preview.html");
			
			if ($file_excel_error == 0) 
			{
				$spreadsheet = $reader->load($file_excel_tmp);
				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				//print_r($sheetData);
				if (isset($sheetData))
				{
					$total_data = count($sheetData);
					for ($no=0; $no<$total_data;)
					{
						foreach($sheetData as $key=>$value)
						{
							if(fmod($no,2)==0) $class="baris1"; else $class="baris2";
							if ($no>0) 
							{
								$executed_date=$sheetData[$no][0];
								$creditted_va=$sheetData[$no][1];
								$creditted_name=$sheetData[$no][2];
								$creditted_cost=$sheetData[$no][3];
								$creditted_information=$sheetData[$no][4];
								$status=$sheetData[$no][5];
								
								$xcreditted_cost=number_format($creditted_cost, 0, ',', '.');
								
								$data_tbl.="<tr class='$class'>
												<td>$no</td>
												<td>$executed_date</td>
												<td>$creditted_va</td>
												<td>$creditted_name</td>
												<td>$xcreditted_cost</td>
												<td>$creditted_information</td>
												<td>$status</td>
											</tr>";

								$sql = "BEGIN SP_CLIENT_REKON_BANK('$executed_date', '$creditted_va', '$creditted_name', '$creditted_cost', '$creditted_information', '$status', '$file_excel', '$userlogin'); END; ";
								$q = oci_parse($conn, $sql);
								oci_execute($q);			
							}
							$no++;
						}
					}
				}
				
				move_uploaded_file($file_excel_tmp,"uploaded_client_rekonsiliasi_tagihan/$file_excel");
				
				$sql = "SELECT COUNT(*) AS JMLDATA FROM TM_CLIENT_API_VA_REKON_BANK WHERE NAMA_FILE = '$file_excel' AND FLAG_REKON ='Y'";
				$q = oci_parse($conn, $sql);
				oci_execute($q);
				$row=oci_fetch_assoc($q);
				$jmldata = $row["JMLDATA"];
				
				if ($jmldata == 0) 
				{
					$msg = "Tidak ada transaksi baru yang bisa direkonsiliasi";
					$data_tbl="";
				}
				else $msg="Ada $jmldata transaksi yang berhasil direkonsiliasi";
			}
			else
			{
				$msg="File yang dilampirkan rusak";
			}
		}
		else
		{
			$msg="Format file tidak diperbolehkan";
		}
	}
	else
	{
		$msg="File belum disertakan";
	}
	
	oci_close($conn);
}

if (isset($_POST["btnproses"]) and $_POST["btnproses"]!="") upload();

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
<script type="text/javascript">
        var GB_ROOT_DIR = "greybox/";
</script>
<script type="text/javascript" src="greybox/AJS.js"></script>
<script type="text/javascript" src="greybox/AJS_fx.js"></script>
<script type="text/javascript" src="greybox/gb_scripts.js"></script>
<link href="greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="static_files/help.js"></script>
<link rel="stylesheet" type="text/css" href="../datepicker.css"/>
<script language="JavaScript" src="../datepicker.js"></script>


<style type="text/css">
<!--
.grid_container {
	width:1050px;
    border:0px solid #cccccc;
	background-color:#f3f3f3;
/*	padding:5px;*/
	overflow-x:scroll;
}

#loading
{
	font-size:18px;
	color:#17548E;
}

.btndisabled
{
	border-top: 1px solid #666666;
   	background: #666666 !important;
   	background: -webkit-gradient(linear, left top, left bottom, from(#666666), to(#000000)) !important;
   	background: -webkit-linear-gradient(top, #666666, #000000) !important;
   	background: -moz-linear-gradient(top, #666666, #000000) !important;
   	background: -ms-linear-gradient(top, #666666, #000000) !important;
   	background: -o-linear-gradient(top, #666666, #000000) !important;
}

.btndisabled:hover
{
	border-top-color: #ffffff !important;
	background: #666666 !important;
}	
-->

</style>
</head>
<body  onLoad="hide_btnproses();set_interval();">
<div id="loader" align="center"></div>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width='1%' background="../images/left_shadow.gif" style="background-position:right; background-repeat:repeat-y ">&nbsp;</td>
        <td width='98%'><table width="100%"  border="0" class="outside_border">
          <tr>
            <td align="center" valign="middle"><? include("../inc_banner.php"); ?></td>
          </tr>
          <tr bgcolor="#E5E4D7">
            <? require_once("../inc_bawahbanner.php"); ?>
          </tr>
          <? require_once("../inc_menu.php"); ?>
          <tr>
            <td class="title_banner" align="center">REKONSILIASI TAGIHAN BANK</td>
          </tr>
          <tr>
            <td align="center" valign="middle" ><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr align="left">
                <td width="151" height="174" align="left" valign="top"><? require_once("inc_menu_tagihan.php"); ?></td>
                <td width="89%" valign="top">
                <form action="" method="post" enctype="multipart/form-data" name="myform" id="myform">
                <input type='hidden' name="nama_file_excel" value="<?=$file_excel; ?>" >
                	<table width="100%">
                    	<tr>
                        	<td>
                            	<fieldset>
                                <table width="100%"  border="0" cellpadding="1" cellspacing="1">
                                  <tr>
                                    <td colspan="3">*Format file yang diperbolehkan : CSV</td>
                                  </tr>
                                  <tr>
                                    <td height="20" colspan="3">
                                            <a onmouseover="ddrivetip('Download Template Rekon CSV', 90)" onmouseout="hideddrivetip()" href="tmp_rekon_va.csv"><img src="../images/csv.png" width="32" height="32" border="0"></a>
                                    </td>
                                    </tr>
                                  <tr>
                                    <td width="8%" height="20">File</td>
                                    <td width="2%" align="center">:</td>
                                    <td width="90%"><input name="file_excel" type="file" id="file_excel">
                                      <input name="btnproses" type="submit" id="btnproses" value="Upload" ></td>
                                  </tr>
                                  <tr>
                                      <td align="" colspan="3">
                                      <table width="100%" border="0">
                                      <tbody id="tbl_data_proses_rekon">
                                        	<?=$data_tbl;?>
                                        </tbody>
                                      </table></td>
                                    </tr>
                                </table>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                          <td height="27"></td>
                        </tr>
                    </table>
                </form>
                </td>
              </tr>
            </table></td>
          </tr>
          <? include("../inc_footer.php"); ?>
        </table></td>
        <td width='20px' background="../images/right_shadow.gif" style="background-position:left; background-repeat:repeat-y ">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
 <? if($msg!="") echo "<script>alert('$msg');</script>"; ?>
</html>
