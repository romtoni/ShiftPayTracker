<?
function display_monitoring_userlog($username,$islogin,$page)
{
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  $cond1="";
  $cond2="";
  $xusername="";
  if($username!="") $cond1="upper(USERNAME) like upper('%$username%')";
  if($islogin!="") 
  {
  	$cond2=" ISLOGIN = '$islogin'"; 
	if($cond1!="" and $cond2!="") $cond2=" and $cond2";
  }
  
  
  $cond=$cond1.$cond2;
  $cond_count=$cond;
  
  if($cond!="") $cond_count=" where $cond"; 
  if($cond!="") $cond=" where $cond";

  $strcount="select count (*) as JUMLAH FROM HS_USERLOG
			 $cond_count";
  //echo $strcount;
  $q=oci_parse($conn,$strcount);
  oci_execute($q);
  $row=oci_fetch_assoc($q);

  //paging header
  $maxrow=25;
  $baris=$maxrow;
  $jmlrec=$row["JUMLAH"];
  $n=$jmlrec/$baris;
  if ($n==floor($jmlrec/$baris)) $npage=$n; else $npage=floor($jmlrec/$baris)+1;  
  if($page=="") $hal=1; else $hal=$page;
  $posisi=($hal-1)*$baris;
  //lookup page
  $datapage="<select name='page' onChange='display()' >";
  for($i=1;$i<=$npage;$i++) 
    {
	  if($i==$page) $sel="selected"; else $sel="";
	  $datapage.="<option value='$i' $sel>$i</option>";
    }   
  $datapage.="</select>";

  //RECORDS
$strsql="SELECT * FROM
		(
		    SELECT a.*, rownum r__
		    FROM
		    (
		        SELECT    ID_HSUSERLOG, 
							USERNAME, 
							to_char(LASTLOGIN, 'DD-MM-YYYY HH24:mi:ss') as XLASTLOGIN, 
							to_char(LASTLOGOUT, 'DD-MM-YYYY HH24:mi:ss') as XLASTLOGOUT, 
							IP_ADDRESS, 
							ISLOGIN, 
							ISLOGOUT, 
							FORCE_LOGOUT, 
							USER_KICKER,
							IP_ADDRESS_KICKER
							
				  FROM   HS_USERLOG
					$cond 
					ORDER BY USERNAME ASC, ID_HSUSERLOG DESC
		    ) a
		    WHERE rownum < (($hal * $baris) + 1 )
		)
		WHERE r__ >= ((($hal-1) * $baris) + 1)";
//echo $strsql;
  $q=oci_parse($conn,$strsql);
  oci_execute($q);
  $no=$posisi;
  $i=0;
  $xstb="";
  $data="";
  while($row=oci_fetch_assoc($q)) 
  {
    	$no++;
		$i++;
		if(fmod($no,2)==0) $class="baris1"; else $class="baris2";
	
		//$no=$row["NO"];
		$id_hsuserlog=$row["ID_HSUSERLOG"];
		$username=$row["USERNAME"];
		$lastlogin=$row["XLASTLOGIN"];
		$lastlogout=$row["XLASTLOGOUT"];
		$ip_address=$row["IP_ADDRESS"];
		$islogin=$row["ISLOGIN"];
		$islogout=$row["ISLOGOUT"];
		$force_logout=$row["FORCE_LOGOUT"];
		$user_kicker=$row["USER_KICKER"];
		$ip_address_kicker=$row["IP_ADDRESS_KICKER"];
		
		if ($username != $xusername and $islogin != 'Y')
		{
			$data.="<tr bgcolor='orange'>
						<td colspan='10' align='center'><strong>$username</strong></td>
					</tr>";
		}
		
		$data.="<tr class='$class'>";
		$data.= "<td align='right'>$no</td>";
    	$data.= "<td align='left'>$username</td>";
    	$data.= "<td align='center'>$lastlogin</td>";	
		$data.= "<td align='center'>$lastlogout</td>";	
    	$data.= "<td align='center'>$ip_address</td>";
		$data.= "<td align='center'>$islogin</td>";
		$data.= "<td align='center'>$islogout</td>";
		$data.= "<td align='center'>$force_logout</td>";
		$data.= "<td align='left'>$user_kicker</td>";
		$data.= "<td align='center'>$ip_address_kicker</td>";
		$data.="</tr>".chr(13).chr(10); 
		
		$xusername = $username;
	
  }
	
  oci_close($conn);	   			  	  
  $jmlrec=number_format($jmlrec);
  $objResponse->AddAssign("frek", "value", $jmlrec);  
  $objResponse->AddAssign("page", "innerHTML", $datapage);    
  $objResponse->AddAssign("mytable", "innerHTML", $data);  
  $objResponse->AddAssign("loader", "innerHTML", '');        
  $objResponse->AddAssign("nrow", "value", $i);  
  return $objResponse;
}


?>