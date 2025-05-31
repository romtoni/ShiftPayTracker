<?
function lookup_akun($i_transaksijenis)
{
 global $conn;
 $strsql="select j.c_akun,a.n_akun 
  		  from TMTRANSAKSIJENISAKUN J 
		  LEFT JOIN trakun a ON a.C_AKUN = j.C_AKUN
		  where j.i_transaksijenis='$i_transaksijenis' order by j.c_akun";
		  
 $q=oci_parse($conn,$strsql);
 oci_execute($q);
 $data="<select name='c_akun' id='c_akun' onchange='xajax_create_radiobutton(this.value,\"$i_transaksijenis\")'>";
  $data.="<option value=''>--Pilih--</option>"; 
 while($row=oci_fetch_array($q))
 {
   $val=$row["C_AKUN"];
   $dsp=$row["N_AKUN"];   
   if($val==$def) $sel=" selected "; else $sel=""; 
   $data.="<option value='$val'>$val - $dsp</option>";
 } 
  $data.="</select>"; 
  $objResponse = new xajaxResponse();
  $objResponse->addAssign("id_c_akun", "innerHTML", $data);
  return $objResponse;
}

function lookup_akun_new($i_transaksijenis)
{
 global $conn;
 $strsql="select j.c_akun,a.n_akun 
  		  from TMTRANSAKSIJENISAKUN J 
		  LEFT JOIN trakun a ON a.C_AKUN = j.C_AKUN
		  where j.i_transaksijenis='$i_transaksijenis' order by j.c_akun";
		  
 $q=oci_parse($conn,$strsql);
 oci_execute($q);
 $data="<select name='c_akun' id='c_akun' onchange='xajax_create_radiobutton_new(this.value,\"$i_transaksijenis\")'>";
  $data.="<option value=''>--Pilih--</option>"; 
 while($row=oci_fetch_array($q))
 {
   $val=$row["C_AKUN"];
   $dsp=$row["N_AKUN"];   
   if($val==$def) $sel=" selected "; else $sel=""; 
   $data.="<option value='$val'>$val - $dsp</option>";
 } 
  $data.="</select>"; 
  $objResponse = new xajaxResponse();
  $objResponse->addAssign("id_c_akun", "innerHTML", $data);
  return $objResponse;
}

function lookup_akun_koreksi($i_transaksijenis)
{
 global $conn;
 $strsql="select j.c_akun,a.n_akun 
  		  from TMTRANSAKSIJENISAKUN J 
		  LEFT JOIN trakun a ON a.C_AKUN = j.C_AKUN
		  where j.i_transaksijenis='$i_transaksijenis' order by j.c_akun";
		  
 $q=oci_parse($conn,$strsql);
 oci_execute($q);
 $data="<select name='c_akun' id='c_akun' onChange='xajax_create_radiobutton_koreksi(this.value,\"$i_transaksijenis\")'>";
  $data.="<option value=''>--Pilih--</option>"; 
 while($row=oci_fetch_array($q))
 {
   $val=$row["C_AKUN"];
   $dsp=$row["N_AKUN"];   
   if($val==$def) $sel=" selected "; else $sel=""; 
   $data.="<option value='$val'>$val - $dsp</option>";
 } 
  $data.="</select>"; 
  $objResponse = new xajaxResponse();
  $objResponse->addAssign("id_c_akun", "innerHTML", $data);
  return $objResponse;
}

function lookup_akun_koreksi_new($i_transaksijenis)
{
 global $conn;
 $strsql="select j.c_akun,a.n_akun 
  		  from TMTRANSAKSIJENISAKUN J 
		  LEFT JOIN trakun a ON a.C_AKUN = j.C_AKUN
		  where j.i_transaksijenis='$i_transaksijenis' order by j.c_akun";
		  
 $q=oci_parse($conn,$strsql);
 oci_execute($q);
 $data="<select name='c_akun' id='c_akun' onChange='xajax_create_radiobutton_koreksi(this.value,\"$i_transaksijenis\")'>";
  $data.="<option value=''>--Pilih--</option>"; 
 while($row=oci_fetch_array($q))
 {
   $val=$row["C_AKUN"];
   $dsp=$row["N_AKUN"];   
   if($val==$def) $sel=" selected "; else $sel=""; 
   $data.="<option value='$val'>$val - $dsp</option>";
 } 
  $data.="</select>"; 
  $objResponse = new xajaxResponse();
  $objResponse->addAssign("id_c_akun", "innerHTML", $data);
  return $objResponse;
}

function lookup_nktrasl($ktrasl)
{
 global $conn;
 $def=$ktrasl;
 session_start();  //ambil dbname
 include("../server.php");
 $strsql="select KDKTR,NMKTR from D_KTR";
 $q=oci_parse($conn,$strsql);
 oci_execute($q);
 $data="<select name='nktrasl_dropdown' id='nktrasl_dropdown' onchange='xajax_cetak_kodekantor_bynama(this.value)'>";
 $data.="<option value=''>--Select--</option>"; 
 while($row=oci_fetch_array($q))
 {
   $kdktr=$row["KDKTR"];
   $val=$row["NMKTR"];
   $dsp=$row["NMKTR"];
   if($kdktr==$def) $sel=" selected "; else $sel=""; 
   $data.="<option value='$val' $sel>$dsp</option>";
 } 
 oci_close($conn);
  $data.="</select>"; 
  $objResponse = new xajaxResponse();
  $objResponse->addAssign("nktrasl_dropdown", "innerHTML", $data);
  return $objResponse;
}

function lookup_kdktrasl($nktrasl)
{
 global $conn;
 $def=$nktrasl;
 session_start();  //ambil dbname
 include("../server.php");
 $strsql="select KDKTR,NMKTR from D_KTR";
 $q=oci_parse($conn,$strsql);
 oci_execute($q);
 $data="<select name='ktrasl_dropdown' id='ktrasl_dropdown' onchange='xajax_cetak_kodekantor_bykode(this.value)'>";
 $data.="<option value=''>--Select--</option>"; 
 while($row=oci_fetch_array($q))
 {
   $nmktr=$row["NMKTR"];
   $val=$row["KDKTR"];
   $dsp=$row["KDKTR"];
   if($nmktr==$def) $sel=" selected "; else $sel=""; 
   $data.="<option value='$val' $sel>$dsp</option>";
 } 
 oci_close($conn);
  $data.="</select>"; 
  $objResponse = new xajaxResponse();
  $objResponse->addAssign("ktrasl_dropdown", "innerHTML", $data);
  return $objResponse;
}
?>