<?
class DB
{
  public $sql;
  public function execute()
  {
   session_start();
   global $conn;
   /*
   $dir=get_dir();
   $svr=$dir."server.php";
   if(!file_exists($svr)) include("../server.php"); else include($svr);  */
   $strsql=$this->sql;
   $q=oci_parse($conn,$strsql) or die(mysql_error());
   oci_execute($q);
   oci_close($conn);
  }

  public function open()
  {
   global $row;
   global $conn;
   session_start(); 
   /*
   $dir=get_dir();
   $svr=$dir."server.php";
   if(!file_exists($svr)) include("../server.php"); else include($svr);  */
   $strsql=$this->sql;
   $q=oci_parse($conn,$strsql) or die(mysql_error());
   oci_execute($q);   
   $row=oci_fetch_array($q);
   oci_close($conn);
  }
  
  public function field($fname)
  {
  global $row;
  return $row["$fname"];  
  }
}	
?>