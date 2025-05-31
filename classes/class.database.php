<?
class Database {

    // privat

    var $gs_DBName;

    var $gs_DBUser;

    var $gs_DBPass;

    var $query="";

    var $stmt="";

    var $conn="";

    var $error=TRUE;

    var $version="";

    var $errorcode=""; 		// error number

    var $errormessage=""; // error number

    var $errorstring="";  // komplete html error

    var $numcols;

		//var $fied;



  function Database($User="",$Pass="",$DB="",$Server=0){

  		$this->gs_DBUser=$User;

    	$this->gs_DBPass=$Pass;

    	$this->gs_DBName=$DB;



		if(!isset($GLOBALS[md5($this->gs_DBUser.$this->gs_DBPass.$this->gs_DBName)]) || 

	  	        !$GLOBALS[md5($this->gs_DBUser.$this->gs_DBPass.$this->gs_DBName)]){

			

	  		$GLOBALS[md5($this->gs_DBUser.$this->gs_DBPass.$this->gs_DBName)]=

				@oci_connect($this->gs_DBUser,$this->gs_DBPass,$this->gs_DBName);

				

		}

		$this->conn =$GLOBALS[md5($this->gs_DBUser.$this->gs_DBPass.$this->gs_DBName)];

	

		if(!$this->conn){

	   		$this->error($this->conn);

		}

	

	  $this->version=@OCIServerVersion($this->conn);

  }

    

  /**destructor */

  function destruct(){

		@OCIFreeStatement($this->stmt);

		@OCILogoff($this->conn);

					 

		return TRUE;

  }

    

		  /**Define by Name*/

  function definebyname($stmt,$field=""){

				 $this->field=$field;

				 if(!$stmt){

	    		 		$stmt=$this->stmt;

				 }

				 $this->stmt=$stmt;

				 $this->fied=strtoupper($field);

				 @OCIDefineByName($this->stmt,$this->fied,$this->field);

					 

	 return $this->stmt;	

  }

	

			  /**Fetch bu Kampretz*/

  function fetch($stmt){

				 if(!$stmt){

	    		 		$stmt=$this->stmt;

				 }

				  $this->stmt=$stmt;

				 @OCIFetch($this->stmt);

				 //$fied=$this->fied;

					 

	 return $this->error();	

  }

	

  /** build error output

   *    type can be stmt|conn|global

  */

   function error(){
	
	   $type=$this->stmt;
	
	
	$err=@OCIError($type);
/*
	if($error) {
	    $errorstring="<br>\nOCIError: ".$error["code"]." ".
		$error["message"]
		." <br>\nAction: ". $this->query."<br>\n";
	    $this->errorstring=$errorstring;
	    //trace(2,__LINE__,get_class($this), $errorstring);
	    $this->errorcode=$error["code"];
	    $this->errormessage=$error["message"];	
	    $this->error = FALSE;
	    return FALSE;		
		
	} else {
	    $this->errorcode=FALSE;
	    $this->errormessage=FALSE;
	    $this->error = true;
	    return TRUE;
	}
	*/
	return $err['message'];
	
    }

    

  /** parse a query and return a statement */

  function parse($query){
	$this->query=$query;
	$stmt=@OCIparse($this->conn,$query);
	$this->stmt=$stmt;
	$this->error();
	return $stmt;
  }

    

  /** executes a statement */

  function execute($stmt="",$param=OCI_COMMIT_ON_SUCCESS){
	if(!$stmt){
	    $stmt=$this->stmt;
	}
	@OCIExecute($stmt);
	
	return $this->error();
	@oci_close($this->conn);
  }

    

  /** Commit the outstanding transaction */

	function commit(){

	  @OCICommit($this->conn);

		return $this->error();

	}   

  

	/** returns array of assoc array's */

  function result($stmt=FALSE,$from=FALSE,$to=FALSE){

	if(!$stmt){

	    $stmt=$this->stmt;

	}

	$result=array();

	if (!$from && !$to){

	    while(@OCIFetchInto($stmt,$arr,OCI_ASSOC+OCI_RETURN_NULLS)){

			  $result[]=$arr;

	    }

	} else {

	    $counter=0;

	    while(@OCIFetchInto($stmt,$arr,OCI_ASSOC+OCI_RETURN_NULLS)){

		if($counter>=$from && $counter<=$to){

		    $result[]=$arr;

		}

		$counter++;

	    }

	}

	@OCIFreeStatement($stmt);

	return $result;

  }

    

  /** return thge the next row based upon @ocifetchinto($stmt,$arr,OCI_ASSOC+OCI_RETURN_NULLS) */
  
  
  function oracle_execute($sql)
  {
	//$param=;
	//$sql=$sql."vcvcvcv".
	$stmt=@OCIparse($this->conn,$sql);
	$this->stmt=$stmt;
	//$err= $this->error();
	//echo "eror1 :".$err;
	@OCIExecute($stmt,OCI_COMMIT_ON_SUCCESS);
	$err= $this->error();
	//echo "eror2 :".$err;
	if ($err!=""){
	return false; }
	else{
		return $this->stmt;
	}
	//return $this->error();
	
  }

  function nextrow($stmt=FALSE, $param=FALSE){

	if(!$stmt){

	    $stmt=$this->stmt;

	}  

	if(!$param){

	    $param=OCI_ASSOC+OCI_RETURN_NULLS;

	}

	if(@OCIFetchInto($stmt,$arr,$param)){

	    return array_change_key_case($arr,CASE_LOWER);

	} 

	return FALSE;

  }



  /** returns rownum of affected rows */

  function affected($stmt=FALSE){

	if(!$stmt){

	    $stmt=$this->stmt;

	}

	return @OCIRowCount($stmt);

  }



  function numcols($stmt=FALSE){

	if(!$stmt){

	    $stmt=$this->stmt;

	}

	return @OCINumCols($stmt);

  }



  /** returns type of field */

  function fieldtype($field, $stmt=FALSE){

	if(!$stmt){

	    $stmt=$this->stmt;

	}

	return @OCIColumnType($stmt, $field);

  }



  /** returns type of field */

  function fieldsize($field, $stmt=FALSE){

	if(!$stmt){

	    $stmt=$this->stmt;

	}

	return @OCIColumnSize($stmt, $field);

  }
function tes()
{
 if($this->conn) {return "ok";} else{return "gagal";}
}

function record_size($stmt=""){

	
	$nrows = @OCIFetchStatement($stmt,$results);
	//echo $nrows;
	//$record=$this->result();
	return $nrows;
}

} //class Database


	

?>