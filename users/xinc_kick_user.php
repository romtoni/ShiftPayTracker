<?
function display_user_online($username_param,$page)
{
 // session_start();
  include("../server.php");  
  set_time_limit(180);  
  $objResponse = new xajaxResponse(); 
  $cond1="";
  $semua="";
  if($username_param!="") $cond1=" a.USERNAME = '$username_param'";
    
  $userlogin = $_SESSION["server_user"];
  $cond_default=" 	a.USERNAME = b.username
				   AND b.ISLOGIN = 'Y'
				   AND a.USERNAME NOT IN ('$userlogin')
				   AND a.USERNAME = d.username
				   AND d.id_sessions = (SELECT   MAX (id_sessions) AS id_sessions
										 FROM   tm_sessions
										WHERE   username = d.username)
				";
  if ($cond1 != "" and $cond_default !="") $cond_default = " and $cond_default";
	
  $cond=$cond1.$cond_default;
  $cond_count=$cond;
  
  if($cond!="") $cond_count=" where $cond"; 
  if($cond!="") $cond=" where $cond";

  $strcount="select count (*) as JUMLAH from 
  				(
					 SELECT   a.USERNAME,
						   TO_CHAR (b.LASTLOGIN, 'MM/DD/YYYY HH24:MI:SS') AS LASTLOGIN,
						   b.ISLOGIN,
						   b.IP_ADDRESS,
						   d.waktusesi
					FROM   TM_USER a,
						   HS_USERLOG b,
						   TM_SESSIONS d
						
					$cond_count		
					
					order by 
						b.LASTLOGIN desc
				)";
 //echo $strcount;
  $q=oci_parse($conn,$strcount);
  oci_execute($q);
  $row=oci_fetch_assoc($q);

  //paging header
  $maxrow=20;
  if($semua=='true') $baris=99999;  else $baris=$maxrow;
  
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
				  SELECT   	a.USERNAME,
				  			'ZXZ' AS C_KTR,
						   	TO_CHAR (b.LASTLOGIN, 'MM/DD/YYYY HH24:MI:SS') AS LASTLOGIN,
						   	b.ISLOGIN,
						   	b.IP_ADDRESS,
						   'DEPARTEMEN TEKNOLOGI DAN INFORMASI' AS N_KTR_BIL,
						   	d.waktusesi
					FROM   	TM_USER a,
						   	HS_USERLOG b,
						   	TM_SESSIONS d
				   $cond       
				ORDER BY   b.LASTLOGIN DESC
		    ) a
		    WHERE rownum < (($hal * $baris) + 1 )
		)
		WHERE r__ >= ((($hal-1) * $baris) + 1)";
//echo $strsql;
  	$q=oci_parse($conn,$strsql);
  	oci_execute($q);
  	$no=0;
	$data="";
  	while($row=oci_fetch_assoc($q)) 
  	{
		$no++;
		if(fmod($no,2)==0) $class="baris1"; else $class="baris2";
		
		$username=$row["USERNAME"];
		$c_ktr=$row["C_KTR"];
		$ktr=$row["N_KTR_BIL"];
		$lastlogin=date('d-m-Y H:i:s',strtotime($row["LASTLOGIN"]));
		$ip_address=$row["IP_ADDRESS"];
		$islogin=$row["ISLOGIN"];
		$waktusesi = $row["WAKTUSESI"];
		$habis_sesi = date('d-m-Y H:i:s', $waktusesi);
		
		if ($islogin== 'Y')
		{
			$perkiraan_sesi = time();
			if ($perkiraan_sesi <= $waktusesi) 
			{
				$keterangan_sesi = "Masih Ada";
				$disabled_cek = "disabled";
				$img_islogin="<img src='../images/online.jpg'></img>";
			}
			else 
			{
				$keterangan_sesi = "Sudah Habis";
				$disabled_cek = "";
				$img_islogin="<img src='../images/offline.jpg'></img>";
			}
		}
		else
		{
			$keterangan_sesi = "Tidak";
			$disabled_cek = "";
			$img_islogin="<img src='../images/offline.jpg'></img>";
		}
		
		$data.="<tr class='$class'>";
		$data.="<td align='center'><input type='checkbox' value='$username' name='cek$no' id='cek$no' $disabled_cek></td>";
		$data.="<td align='center'>$no</td>";
		$data.="<td align='center'>$username</td>";
		$data.="<td align='left'>$ktr ($c_ktr)</td>";
		$data.="<td align='center'>$ip_address</td>";	
		$data.="<td align='center'>$lastlogin</td>";
		$data.="<td align='center'>$habis_sesi</td>";
		$data.="<td align='center'>$keterangan_sesi</td>";	
		$data.="<td align='center'>$img_islogin</td>";	
		$data.="</tr>".chr(13).chr(10); 
  	}

  oci_close($conn);	   			  	  
  $jmlrec=number_format($jmlrec);
  $objResponse->AddAssign("frek", "value", $jmlrec);  
  $objResponse->AddAssign("page", "innerHTML", $datapage);    
  $objResponse->AddAssign("mytable", "innerHTML", $data);  
  $objResponse->AddAssign("loader", "innerHTML", '');        
  $objResponse->AddAssign("nrow", "value", $jmlrec);      
  return $objResponse;
}

?>