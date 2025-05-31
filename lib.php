<?
function year($d)
{
$a=explode("-",$d); //dd-mm-yyyy
return $a[2];
}

function month($d)
{
$a=explode("-",$d); //dd-mm-yyyy
return $a[1];
}

function day($d)
{
$a=explode("-",$d); //dd-mm-yyyy
return $a[0];
}



function to_val($v)
{
$v=str_replace(".","",$v);
$v=str_replace(",",".",$v);
if($v=="") $ret=0; else $ret=$v;
return $ret;
}

function html_tag($s)
{
$s=str_replace("<","&lt;",$s);
$s=str_replace(">","&gt;",$s);
return $s;
}

function spasi($n)
{
$s="";
for($i=1;$i<=$n;$i++) $s.="&nbsp; ";
return $s;
}

function spasifpdf($n)
{
$s="";
for($i=1;$i<=$n;$i++) $s.="		";
return $s;
}

function get_script()
{
$server=$_SERVER['SERVER_NAME'];
$a=array();
$a=explode("/",$_SERVER["REQUEST_URI"]);
//request_uri : /cotelligentpl_php/en/activation_fail.php
$n=count($a);
$res=$a[$n-1];
//parsing .php
$a=explode(".",$res);
$n=count($a);
$res=$a[0];
return $res;
}

function get_urldir()
{
$server=$_SERVER['SERVER_NAME'];
$a=array();
$a=explode("/",$_SERVER["REQUEST_URI"]);
//request_uri : /cotelligentpl_php/en/activation_fail.php
$n=count($a);
$res="";
for($i=0;$i<=$n-2;$i++) $res.=$a[$i]."/";
$res=$server.$res;
return $res;
}

function get_address()
{
$server=$_SERVER['SERVER_NAME']; //172.16.32.126
$uri=$_SERVER["REQUEST_URI"]; //dplk/akuntansi/xajax_server.php
$a=array();
$a=explode("/",$uri);
$n=count($a);
$res="";
for($i=0;$i<=$n-2;$i++) $res.=$a[$i]."/";
$res="http://".$server.$res;
return $res;
}

function get_address_top()
{
$server=$_SERVER['SERVER_NAME']; //172.16.32.126
$uri=$_SERVER["REQUEST_URI"]; //dplk/akuntansi/xajax_server.php
$a=array();
$a=explode("/",$uri);
$n=count($a);
$res="";
for($i=0;$i<=$n-3;$i++) $res.=$a[$i]."/";
$res="http://".$server.$res;
return $res;
}

function get_dir()
{
$server=$_SERVER['SERVER_NAME'];
$uri=$_SERVER["REQUEST_URI"];
$root=$_SERVER['DOCUMENT_ROOT'];
$dir=$root.$uri;
$a=array();
$a=explode("/",$dir);
$n=count($a);
$res="";
for($i=0;$i<=$n-2;$i++) $res.=$a[$i]."/";

return $res;
}

function quotedstr($s)
{
$s='"'.$s.'"';
return $s;
}


//============================================================================
function get_field_value($strsql,$fld)
{
 include('server.php');
  
 $q=mysql_query($strsql,$conn) or die(mysql_error());
 $row=mysql_fetch_array($q);
 return $row[$fld];
}	

function slookup_table($def,$name,$strsql,$onchange)
{
 global $conn; 
 if($onchange!="") $och="onChange='$onchange'";
 $data="<select name='$name' id='x$id' $och>";
 $data.="<option value=''>--Select--</option>";
 
 $q=mysql_query($strsql,$conn);
 while($row=mysql_fetch_array($q))
 {
 $disp=$row["card_type"];
 $val=$row["card_type"];
 if($val==$def) $sel=" selected "; else $sel=""; 
 $data.= "<option value='$val' $sel >$disp</option>";
 }
 $data.="</select>";
 return $data;
}

//============================================================================


function slookup_uom($item_id,$name,$id,$def)
{
  global $conn; 
  $strsql="select uom1,uom2,uom3,xuom2,xuom3 from item where item_id='$item_id'";
  $q=mysql_query($strsql,$conn) or die(mysql_error());
  $row=mysql_fetch_array($q);
  $data="<select name='$name' id='$id'>";  
  for($i=1;$i<=3;$i++) {
    $uom=$row["uom$i"];
	$xuom=$row["xuom$i"];
	if($uom==$def) $sel="selected"; else $sel="";
	$data.="<option value='$uom' $sel>$uom</option>";
  }
  $data.="</select>";	
  return $data;	 			
}

function lookup_page($npage,$def)
{
 global $conn; 
 for($i=1;$i<=$npage;$i++)
 {
 if($i==$def) $sel=" selected "; else $sel=""; 
 echo "<option value='$i'" . $sel ."> $i</option>";
 }
}

function lookup_year($def)
{
 global $conn; 
 $strsql="select * from years order by years desc";
 $q=mysql_query($strsql,$conn) or die(mysql_error());
 while($row=mysql_fetch_array($q))
 { if($row["years"]==$def) $sel=" selected "; else $sel=""; 
 echo "<option value='".$row["years"]."'" . $sel ."> ".$row["years"]."</option>";
 }
}


function lookup_month($def)
{
 global $conn; 
 $strsql="select * from months order by months";
 $q=mysql_query($strsql,$conn) or die(mysql_error());
  echo "<option value=''>--Select--</option>";
 while($row=mysql_fetch_array($q))
 {
 if($row["months"]==$def) $sel=" selected "; else $sel=""; 
 echo "<option value='".$row["months"]."'" . $sel ."> ".$row["month_name"]."</option>";
 }
}


function lookup_usertype($def)
{
 global $conn; 
// if ($def=="") $def="Indonesia";
 $q=mysql_query("select * from user_type",$conn);
 echo "<option value=''>--Select--</option>";
 while($row=mysql_fetch_array($q))
 {
 if($row["user_type"]==$def) $sel=" selected "; else $sel=""; 
 echo "<option value='".$row["user_type"]."'" . $sel ."> ".$row["user_type"]."</option>";
 }
}

function lookup_userid($def)
{
 global $conn; 
// if ($def=="") $def="Indonesia";
 $q=mysql_query("select * from users",$conn);
 echo "<option value=''>--Select--</option>";
 while($row=mysql_fetch_array($q))
 {
 if($row["userid"]==$def) $sel=" selected "; else $sel=""; 
 echo "<option value='".$row["userid"]."'" . $sel ."> ".$row["userid"]."</option>";
 }
}

//================= ARRAY LOOKUP ===============
function reversedate($d)  //dmy to ymd dan sebaliknya
{
if($d=="") $ret=""; else
{
$a=array();
$a=explode("-",$d);
$ret= $a[2]."-".$a[1]."-".$a[0];
}
return $ret;
}

function reversedatetime($d,$separator)  //dmy to ymd dan sebaliknya
{
$a=array();
$ds=substr($d,0,10);
$ts=substr($d,10,12);
$a=explode($separator,$ds);
return $a[2].$separator.$a[1].$separator.$a[0]." ".$ts;
}

function read_file($filename)
{
$file = fopen($filename, "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
$res="";
while(!feof($file))
  {
  $res.=fgets($file);
  }
fclose($file);
return $res;
}

function file_ext($fname)
{
$a=array();
$a=explode(".",$fname);
$i=count($a)-1;
return $a[$i];
}

function default_val($s,$defvalue)
{
 if($s=="") $ret=$defvalue; else $ret=$s;
 return $ret;
}

function get_checked($val,$chkval)
{
if($val==$chkval) $ret="checked"; 
return $ret;
}

function dateDiff($dformat, $endDate, $beginDate) 
{     
$date_parts1=explode($dformat, $beginDate);     
$date_parts2=explode($dformat, $endDate);     
//$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);     ==> mdy 
//$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);     ==> mdy
//ke ymd
//echo $date_parts1[1] . " ". $date_parts1[2] ." ". $date_parts1[0] . "<br>";

$start_date=gregoriantojd($date_parts1[1],$date_parts1[2], $date_parts1[0]);     
$end_date=gregoriantojd( $date_parts2[1],$date_parts2[2], $date_parts2[0]); 
return $end_date - $start_date; 
}


function dateAdd($dformat, $beginDate, $nday) 
{     
$date_parts1=explode($dformat, $beginDate);     
$start_date=gregoriantojd($date_parts1[1],$date_parts1[2], $date_parts1[0]);     
$end_date=$start_date+$nday;
return $end_date; 
}

function str_dateAdd($separator, $beginDate, $n) 
{     
//format begindate harus yyyy-mm-dd
$date_parts1=explode($separator, $beginDate);     
$th=$date_parts1[0];
$bl=$date_parts1[1]+0; //dibikin numerik
$hr=$date_parts1[2]+0;

//echo "beg=".$beginDate."<br>";
//echo "$th - $bl - $hr <br>";
$iskabisat=($th%4==0); //mod
$nday=array();
$nday[1]=31;
$nday[2]=28;
$nday[3]=31;
$nday[4]=30;
$nday[5]=31;
$nday[6]=30;
$nday[7]=31;
$nday[8]=31;
$nday[9]=30;
$nday[10]=31;
$nday[11]=30;
$nday[12]=31;
if($iskabisat) $nday[2]=29;

$hr=$hr+$n;
if($hr>$nday[$bl]) //loncat bulan
 { 
   $hr=$hr-$nday[$bl];   
   $bl++;
   if($bl>12) { $bl=1; $th++; } //loncat tahun
   
 }  
$end_date=$th.$separator.str_pad($bl, 2, "0", STR_PAD_LEFT).$separator.str_pad($hr,2,"0",STR_PAD_LEFT);
return $end_date; 
}

function days_in_month($bl,$th) 
{     
$iskabisat=($th%4==0); //mod
$nday=array();
$nday[1]=31;
$nday[2]=28;
$nday[3]=31;
$nday[4]=30;
$nday[5]=31;
$nday[6]=30;
$nday[7]=31;
$nday[8]=31;
$nday[9]=30;
$nday[10]=31;
$nday[11]=30;
$nday[12]=31;
$bl=$bl+0; //hilangkan angka nol didepan bulan bila ada
if($iskabisat) $nday[2]=29;
return $nday[$bl]; 
}


function month_name($i)
{
$m=array();
$m[0]="January";
$m[1]="February";
$m[2]="March";
$m[3]="April";
$m[4]="May";
$m[5]="June";
$m[6]="July";
$m[7]="August";
$m[8]="September";
$m[9]="October";
$m[10]="November";
$m[11]="December";
return $m[$i-1];
}

function nama_bulan($i)
{
$m=array();
$m[0]="Januari";
$m[1]="Februari";
$m[2]="Maret";
$m[3]="April";
$m[4]="Mei";
$m[5]="Juni";
$m[6]="Juli";
$m[7]="Agustus";
$m[8]="September";
$m[9]="Oktober";
$m[10]="Nopember";
$m[11]="Desember";
return $m[$i-1];
}


function month_name_short_english($i)
{
$m=array();
$m[0]="Jan";
$m[1]="Feb";
$m[2]="Mar";
$m[3]="Apr";
$m[4]="May";
$m[5]="Jun";
$m[6]="Jul";
$m[7]="Aug";
$m[8]="Sep";
$m[9]="Oct";
$m[10]="Nov";
$m[11]="Dec";
return $m[$i-1];
}

function month_name_short_indonesian($i)
{
$m=array();
$m[0]="Jan";
$m[1]="Feb";
$m[2]="Mar";
$m[3]="Apr";
$m[4]="Mei";
$m[5]="Jun";
$m[6]="Jul";
$m[7]="Ags";
$m[8]="Sep";
$m[9]="Okt";
$m[10]="Nop";
$m[11]="Des";
return $m[$i-1];
}


function checknull($s)
{
if($s=="") $ret=0; else $ret=$s;
return $ret;
}

function get_member_name($member_id)
{
 global $conn; 
 $q=mysql_query("select nama_member from member where member_id='$member_id'",$conn);
 $row=mysql_fetch_array($q);
 return $row["nama_member"];
}

function today_ind()
{
  $d=date("Y-m-d");
  $a=array();
  $a=explode("-",$d);
  $tahun=$a[0];
  $bulan=$a[1];
  $hari=$a[2];
  $b=$bulan+0;
  $nama_bulan=nama_bulan($b);
  return "$hari $nama_bulan $tahun";
}

function day_english($d)
{
  $a=array();
  $a=explode("-",$d);
  $tahun=$a[0];
  $bulan=$a[1];
  $hari=$a[2];
  $b=$bulan+0;
  $nama_bulan=month_name_short_english($b);
  if($d=="" or $d=="0000-00-00") $ret=""; else $ret="$nama_bulan $hari, $tahun";
  return $ret;
}

function day_indonesian($d) //dd-mm-yyyy
{
  $a=array();
  $a=explode("-",$d);
  $tahun=$a[2];
  $bulan=$a[1];
  $hari=$a[0];
  $b=$bulan+0;
  $nama_bulan=month_name_short_indonesian($b);
  if($d=="" or $d=="00-00-0000") $ret=""; else $ret="$hari $nama_bulan $tahun";  
  return $ret;
}

function day_indonesian_long($d) //dd-mm-yyyy
{
  $a=array();
  $a=explode("-",$d);
  $tahun=$a[2];
  $bulan=$a[1];
  $hari=$a[0];
  $b=$bulan+0;
  $nama_bulan=nama_bulan($b);
  if($d=="" or $d=="00-00-0000") $ret=""; else $ret="$hari $nama_bulan $tahun";  
  return $ret;
}

function Terbilang($x)
{
  $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x <12) {
        $temp = " ". $angka[$x];
    } else if ($x <20) {
        $temp = Terbilang($x - 10). " belas";
    } else if ($x <100) {
        $temp = Terbilang($x/10)." puluh". Terbilang($x % 10);
    } else if ($x <200) {
        $temp = " seratus" . Terbilang($x - 100);
    } else if ($x <1000) {
        $temp = Terbilang($x/100) . " ratus" . Terbilang($x % 100);
    } else if ($x <2000) {
        $temp = " seribu" . Terbilang($x - 1000);
    } else if ($x <1000000) {
        $temp = Terbilang($x/1000) . " ribu" . Terbilang($x % 1000);
    } else if ($x <1000000000) {
        $temp = Terbilang($x/1000000) . " juta" . Terbilang($x % 1000000);
    } else if ($x <1000000000000) {
        $temp = Terbilang($x/1000000000) . " milyar" . Terbilang(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $temp = Terbilang($x/1000000000000) . " trilyun" . Terbilang(fmod($x,1000000000000));
    }      
        return $temp;

}

function format_akunting($n)
{
$n=trim($n);
$minus=substr($n,0,1);
if($minus!='-') return $n; else {
	$n=str_replace('-','',$n);
	$n="($n)";
}
return $n;
}

function get_query($sql,$fld)
{
	global $conn;
	$q=oci_parse($conn,$sql);
	oci_execute($q);
	//echo $sql;
	$row=oci_fetch_assoc($q);
	return $row[$fld];
}

//include("D:/xampp/htdocs/dplk/auth_role.php");

function logout()
{
	session_start();
	global $conn;
	
	$userid=$_SESSION["server_user"];;
	$id=$_SESSION["id"];
	$lastlogout=date("Y-m-d H:i");			
	$strsql="update HS_USERLOG set LASTLOGOUT=to_date('$lastlogout','yyyy-mm-dd HH24:MI:SS'), ISLOGOUT='Y',  ISLOGIN='N' 
			 where ID='$id'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	$strsql="DELETE FROM TMSESSIONS	 where SESSION_ID='$id' and  user_id='$userid'";
	$q=oci_parse($conn,$strsql);
	oci_execute($q);
	
	
	$strcekused="select USEDBY from D_REF";
	$qcekused=oci_parse($conn,$strcekused);
	oci_execute($qcekused);
	$rowcekused=oci_fetch_assoc($qcekused);
	$usedby=$rowcekused["USEDBY"];
	if($usedby==$userid)
	{
	$strisused="update D_REF set ISUSED='N', USEDBY=null";
	$qisused=oci_parse($conn,$strisused);
  	oci_execute($qisused);
	}
	
	oci_close($conn);	
	
	session_destroy();
	header("location:.");	
}
?>