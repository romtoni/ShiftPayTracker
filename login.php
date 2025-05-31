<?
include("server.php");

$username_autologin=$_GET["username"];
$password_autologin=$_GET["password"];
$id_sessions_autologin=$_GET["id_sessions"];
	
if ($username_autologin!="" and $password_autologin!="") 
{
	$username=$username_autologin;
	$password=$password_autologin;
	$id=$id_sessions_autologin;
	$kode="autologin";
}
else 
{
	$username=$_POST["username"];
	$password=$_POST["password"];
}

	//$ip=$_SERVER['REMOTE_ADDR'];
	function get_ip() 
	{
		//Just get the headers if we can or else use the SERVER global
		if(function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}
		else
		{
			$headers = $_SERVER;
		}
	
		//Get the forwarded IP if it exists
		if(array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) )
		{
			$the_ip = $headers['X-Forwarded-For'];
		}
		elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
		{
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
		return $the_ip;
	}

	$ip=get_ip();
	
	$islogout="N";
	$islogin="Y";
	
	/*$strsql="select a.*, b.NAMA_ROLE from TM_USER a 
			 left join MN_ROLE b on b.KODE_ROLE=a.KODE_ROLE
			 where upper(a.USERNAME)=upper('$username') and upper(a.PASSWORD)=upper('$password')";	*/
	$strsql = "SELECT    ID_USER,
						   USERNAME,
						   PASSWORD,
						   KUNCI,
						   HASIL_DECRYPT,
						   KODE_ROLE
				  FROM  V_USERS_ONTHEFLY
			where upper(USERNAME)=upper('$username') and HASIL_DECRYPT='$password'";
	$q=oci_parse($conn,$strsql) or die("Query gagal" );
	oci_execute($q);
	$row=oci_fetch_assoc($q);
	$xusername=$row["USERNAME"];  //harus pakai huruf gede! persis sama dengan koneksi FireBird karena di databasenya juga pake huruf gede
	session_start();
	if(strtoupper($xusername)==strtoupper($username) and $xusername!="")
	{	   
		$_SESSION["server_id_user"]=$row["ID_USER"];
		$_SESSION["server_user"]=$row["USERNAME"];
		$_SESSION["server_rolename"]=$row["ROLE_NAME"];
		$_SESSION["server_role"]=$row["KODE_ROLE"];
		$_SESSION["server_error"]="";
		
		$_SESSION['start'] = time(); // Taking now logged in time.
        // Ending a session in 30 minutes from the starting time.
        $_SESSION['expire'] = $_SESSION['start'] + (15 * 60); // 15 menit
				
		$lastlogin=date("Y-m-d H:i");
		$lastlogout=date("Y-m-d H:i");
		
		if ($kode=="autologin")	
		{ 
			$_SESSION["id"]=$id;
			header("location:home/");
		}
		else 
		{
			$sql_single_sign_on_cek="select count(ISLOGIN) as JUMLAH from HS_USERLOG where USERNAME='$username' and ISLOGIN='Y'";
			$r=oci_parse($conn,$sql_single_sign_on_cek);
			oci_execute($r);
			$row2=oci_fetch_assoc($r);
			$jumlah=$row2["JUMLAH"];
				
			if ($jumlah==0)
			{	
				$strsql="insert into HS_USERLOG(USERNAME,LASTLOGIN,IP_ADDRESS, ISLOGOUT, ISLOGIN) 
						 values('$username',to_date('$lastlogin','yyyy-mm-dd HH24:MI'),'$ip', '$islogout', '$islogin')";
				//echo $strsql;
				$q=oci_parse($conn,$strsql);
				oci_execute($q);
						
				//seleksi user id di HS_USERLOG
				$sql="select SQ_ID_HSUSERLOG.currval as ID_USER_TMP from dual";
				$q=oci_parse($conn,$sql);
				oci_execute($q);
				$row=oci_fetch_array($q);
				$_SESSION["id"]=$row["ID_USER_TMP"];
					
				$content=$_SESSION["id"];
				$waktusesi=$_SESSION['expire'];
					
				$sql="INSERT INTO TM_SESSIONS(ID_SESSIONS, USERNAME, ISLOGIN, ISLOGOUT, WAKTUSESI, IP_ADDRESS, PASSWORD) VALUES('$content', '$username', 'Y', 'N', '$waktusesi', '$ip', '$password')";
				$q=oci_parse($conn,$sql);
				oci_execute($q);
						
				oci_close($conn);
				header("location:home/");
			}
			else 
			{
				session_destroy();
				//echo "User <strong>$userid</strong> masih login";
				header("location:index.php?error=1&username=$username");
			}
		}
	}
	else
	{	
		//echo ("Login gagal! Username/Password tidak benar<br>");
		//echo ("<a href=index.php>Ulangi lagi</a>"); 
		oci_close($conn);
		$_SESSION["server_error"]="Login error";
		header("location:.");
	}		
?>