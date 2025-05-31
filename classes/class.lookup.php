<?
class Lookup
{
	public $name;
	public $id;					
	public $sql;
	public $value_field;
	public $list_field;
	public $onchange;
	public $default_value;
	public $no_value=true;
	public $class_name;
	public $separator="|";
	public $disabled=false;
	
	public function dropdown()
	{
    global $conn;
	$onchange=$this->onchange;
	$name=$this->name;
	$id=$this->id;
	$list_field=$this->list_field;
	$value_field=$this->value_field;
	$strsql=$this->sql;
	$def=$this->default_value;
	$no_value=$this->no_value;
	$class=$this->class_name;
	$separator=$this->separator;
	$disabled=$this->disabled;	
	
	
	$aval=array();
	$aval=explode("/",$list_field);
	$n=count($aval);
	$dsb="";
    if($onchange!="") $onchange="onChange='$onchange'";
    if($id!="") $id="id='$id'"; 
	if($class!="") $class="class='$class'";
	if($disabled) $dsb="disabled"; else $read="";
    $data="<select name='$name' $id $onchange $class $dsb>";
	if($no_value==true) $data.="<option value=''>--Select--</option>";
    $q=oci_parse($conn,$strsql);
	oci_execute($q);
	
    while($row=oci_fetch_assoc($q))
	 {
 	 	 $disp="";
	     for($i=0;$i<$n;$i++) {
		    $disp.=$row[$aval[$i]];
			if($i!=$n-1) $disp.=$separator;
	     }	
		 //$disp=$row[$list_field];
		 $val=$row[$value_field];
		 if($val==$def) $sel=" selected "; else $sel=""; 
		 $data.= "<option value='$val' $sel >$disp</option>";
     }
    $data.="</select>";	
    //oci_free_statement($strsql);
    oci_close($conn);	   		
	
    return $data;
	}
}

class LookupBulan
{
	public $name;
	public $id;
	public $default_value;
	public $onchange;
	public $class;	
	public function dropdown()
	{
		$aBulan=array(1,2,3,4,5,6,7,8,9,10,11,12);
		$aNamaBulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
		$n=12;
		$onchange=$this->onchange;
		$def=$this->default_value;
		$class=$this->class;
		$id=$this->id;
		if($id!="") $id="id='$id'";
	    $data="<select name='$name' $id $onchange $class>";
		for($i=0;$i<$n;$i++) {
		  	$val=$aBulan[$i];
		  	$disp=$aNamaBulan[$i];
		    if($val==$def) $sel=" selected "; else $sel=""; 
  		    $data.= "<option value='$val' $sel >$disp</option>";		
		}
		$data.="</select>";
		return $data;
	}
}
class LookupInput
{
	public $name;
	public $id;					
	public $sql;
	public $value_field;
	public $list_field;
	public $onchange;
	public $default_value;
	public $no_value=true;
	public $class_name;
	public $separator="|";

	
	public function dropdown()
	{
    include("../server.php"); 
	$onchange=$this->onchange;
	$name=$this->name;
	$id=$this->id;
	$list_field=$this->list_field;
	$value_field=$this->value_field;
	$strsql=$this->sql;
	$def=$this->default_value;
	$no_value=$this->no_value;
	$class=$this->class_name;
	$separator=$this->separator;

	
	$aval=array();
	$aval=explode("/",$list_field);
	$n=count($aval);
	

    if($onchange!="") $onchange="onChange='$onchange'";
    if($id!="") $id="id='$id'"; 
	if($class!="") $class="class='$class'";
	
    $data="<select name='$name' $id $onchange $class class='required'>";
	if($no_value==true) $data.="<option value=''>--Select--</option>";
    $q=oci_parse($conn,$strsql);
	oci_execute($q);
	
    while($row=oci_fetch_assoc($q))
	 {
 	 	 $disp="";
	     for($i=0;$i<$n;$i++) {
		    $disp.=$row[$aval[$i]];
			if($i!=$n-1) $disp.=$separator;
	     }	
		 //$disp=$row[$list_field];
		 $val=$row[$value_field];
		 if($val==$def) $sel=" selected "; else $sel=""; 
		 $data.= "<option value='$val' $sel >$disp</option>";
     }
    $data.="</select>";	
    //oci_free_statement($strsql);
    oci_close($conn);	   		
	
    return $data;
	}
}
?>