<?
class Condition
{
	private $cond="";
	function add_condition($var,$strcond)
	{
		if($var!='') {
			$v_cond=$this->cond;
			if($v_cond!='') $this->cond.=" and ".$strcond.' ';
			else			$this->cond.=$strcond.' ';
			

		}
	}
	function str_condition()
	{
		if($this->cond!="") $this->cond="where ".$this->cond;
		return $this->cond;
	}
}
?>