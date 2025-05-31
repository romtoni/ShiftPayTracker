<?
function xterbilang($v_jumlah,$id)
{
   $objResponse = new xajaxResponse();   
   $v_jumlah=to_val($v_jumlah);
   $t=Terbilang($v_jumlah);
   $t=ucfirst(trim($t));
   $objResponse->AddAssign($id,"value",$t);
   $objResponse->AddAssign($id,"innerHTML",$t);   
   return $objResponse; 
}


function format_currency($v_jumlah,$id)
{
   $objResponse = new xajaxResponse();   
   $v_jumlah=to_val($v_jumlah);
   $v_jumlah=number_format($v_jumlah,2,',','.');  
   $objResponse->AddAssign($id,"value",$v_jumlah);
   return $objResponse; 
}
function format_currency_tiga_digit($v_jumlah,$id)
{
   $objResponse = new xajaxResponse();   
   $v_jumlah=to_val($v_jumlah);
   $v_jumlah=number_format($v_jumlah,3,',','.');  
   $objResponse->AddAssign($id,"value",$v_jumlah);
   return $objResponse; 
}

function dateval($dt,$separator,$id)
{
//dimungkinkan mengetikan 025012012
if(!strpos($dt,$separator)) {
	$len=strlen($dt);
	if($len==8) { //SPLITKAN SEPARATOR
		$h=substr($dt,0,2);
		$b=substr($dt,2,2);
		$t=substr($dt,4,4);
		$dt=$h.$separator.$b.$separator.$t;
	}
}
//format dd-mm-yyyy
$objResponse = new xajaxResponse();   
$a=array();
$a=explode($separator,$dt);
$n=count($a);
if($n<3 and $n>1) $err="Format tanggal SALAH"; else
{
$hari=$a[0];
$bulan=$a[1];
$tahun=$a[2];
$err="";
if($bulan>12) $err.="Bulan SALAH\n"; else
{
  $nhari=days_in_month($bulan,$tahun);
  if($hari>$nhari) $err.="Tanggal SALAH\n";
}  
}
if($err!='') {
	$objResponse->AddAlert($err);
	$objResponse->AddAssign($id,"value",date('d-m-Y'));
} else $objResponse->AddAssign($id,"value",$dt);
return $objResponse; 
}
?>