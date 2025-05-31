<?
 include("../server.php");  
function display_users($kd_ukerja, $user_id)
{
	global $conn;
 	session_start();
  	set_time_limit(180);  
  	$objResponse = new xajaxResponse(); 

	if($kd_ukerja!="") $cond1="k.kd_ukerja='$kd_ukerja'"; 
  	if($user_id!="") {$cond2=" u.user_id like '%$user_id%'"; if ($cond1!="") $cond2 = "and $cond2";}
  	
	$cond=$cond1.$cond2;
  	if($cond!="") $cond=" and $cond";  
	
//$strsql="select u.USER_ID,u.PWD,r.NAMA_ROLE,u.KODE_KANTOR,k.keterangan, b.keterangan AS nama_bagian
		//from RF_USERS u 
		//left join MN_ROLE r ON u.KODE_ROLE=r.KODE_ROLE 
		//left join trunit_kerja k on k.kd_ukerja=u.kode_kantor
		//left join trbagian b on b.kd_bagian=u.id_bagian
		//$cond
		//order by USER_ID asc";
$strsql="select u.USER_ID,u.PWD,r.NAMA_ROLE,u.KODE_KANTOR,k.keterangan, b.keterangan AS nama_bagian, b.kd_bagian
        from RF_USERS u, MN_ROLE r,trunit_kerja k,trbagian b
        WHERE u.KODE_ROLE=r.KODE_ROLE
        AND k.kd_ukerja=u.kode_kantor
        AND b.kd_bagian=u.id_bagian
        AND b.kd_UKERJA=K.KD_UKERJA
		$cond		
        order by U.USER_ID asc";
//echo $strsql;
$q=oci_parse($conn,$strsql);
oci_execute($q);
$data="";
while ($row=oci_fetch_assoc($q)) 
  {
    $no++;
	if(fmod($no,2)==0) $class="baris2"; else $class="baris1";
	$user_id=$row["USER_ID"];
	$nama_role=$row["NAMA_ROLE"];		
	$pwd=$row["PWD"];
	$kode_kantor=$row["KODE_KANTOR"];
	$keterangan=$row["KETERANGAN"];
	$nama_bagian=$row["NAMA_BAGIAN"];
	$kd_bagian=$row["KD_BAGIAN"];
	$pg="users_del.php?USER_ID=$user_id";	
	$adel="<a href='javascript:confirm_del(\"$pg\");' onmouseover=\"ddrivetip('Delete User', 50)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_del.png' border='0'></a>";	
	$aedit="<a href='users_edit.php?user_id=$user_id&kd_ukerja=$kd_ukerja' onmouseover=\"ddrivetip('Edit User', 40)\" onmouseout=\"hideddrivetip()\"><img src='../images/img_edit.png' border='0'></a>&nbsp;";		
	$data.= "<tr class='$class'>"; 
	$data.= "<td align='center'>$no</td>";
	$data.= "<td align='left'>$user_id</td>";	
	$data.= "<td align='left'>$nama_role</td>";
	$data.= "<td align='left'>$pwd</td>";
	if ($kode_kantor=="") $data.= "<td colspan='2'></td>"; 
	else $data.= "<td align='left'colspan='2'> $keterangan</td>";
	$data.= "<td align='left'>$nama_bagian</td>";
	$data.= "<td align='center'>$aedit $adel</td></tr>";
  }
  //echo $data;
   $objResponse->AddAssign("mytable", "innerHTML", $data);  
 $objResponse->AddAssign('loader', "innerHTML", '');        
  return $objResponse;
}
?>