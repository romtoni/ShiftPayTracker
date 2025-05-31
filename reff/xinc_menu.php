<?
function menu_terakhir($kode_parent)
{
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  $str="select * from  mn_menu where kode_parent='$kode_parent' order by kode_menu desc";
  $q=oci_parse($conn,$str);
  oci_execute($q);
  $row=oci_fetch_assoc($q);
  $nama_menu=$row["NAMA_MENU"];
  $kode_menu=$row["KODE_MENU"]; 
  $urutan=$row["URUTAN"];      
  $objResponse->AddAssign("last_menu", "value", $kode_menu." ".$nama_menu);        
  $objResponse->AddAssign("last_urutan", "value", $urutan);            
  oci_close($conn);
  return $objResponse;
}

function menu_terakhir_foredit($kode_parent)
{
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  $str="select * from  mn_menu where kode_parent='$kode_parent' order by kode_menu desc";
  $q=oci_parse($conn,$str);
  oci_execute($q);
  $row=oci_fetch_assoc($q);
  $nama_menu=$row["NAMA_MENU"];
  $kode_menu=$row["KODE_MENU"];    
  $objResponse->AddAlert($kode_menu);          
  $objResponse->AddAssign("last_menu", "value", $kode_menu);        
  oci_close($conn);
  return $objResponse;
}
?>