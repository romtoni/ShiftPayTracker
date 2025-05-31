<?
class Paging
{
	public $baris=25;
	public $jml_rec;
	public $hal_per_block=50;
	public $page;	
	public $hal;
	public $posisi;
	
	function page()
	{

	  $baris=$this->baris;  
	  $jmlrec=$this->jml_rec;  
	  $hal_per_block=$this->hal_per_block;  
	  $page=$this->page;  
	  
	  $n=$jmlrec/$baris;
	  if ($n==floor($jmlrec/$baris)) $npage=$n; else $npage=floor($jmlrec/$baris)+1;  
	  if($page=="") $hal=1; else $hal=$page;
	  $this->hal=$hal;
	  $posisi=($hal-1)*$baris;
	  $this->posisi=$posisi;
	  //------------------ NEW PAGING BY PAGING --------------------------------
	  $faktor_mulai=floor($hal/$hal_per_block);
	  if(fmod($hal,$hal_per_block)==0) $faktor_mulai--;
	  $faktor_sampai=ceil($hal/$hal_per_block);
	  $mulai=($hal_per_block*$faktor_mulai)+1;
	  $sampai=($hal_per_block*$faktor_sampai);
	  if($sampai>$npage) $sampai=$npage;
  
	  $datapage="<select name='page' onChange='display()' >";
  
	  if($mulai<>1) {
	  $firstpage=1;
	  $datapage.="<option value='$firstpage'> << </option>";	
	  }
  
	  if($mulai>1) {
		  $prevpage=$mulai-1;
		  $datapage.="<option value='$prevpage'> < </option>";	
	  }
  
	  for($i=$mulai;$i<=$sampai;$i++) 
	  {	
		  if($i==$page) $sel="selected"; else $sel="";
		  $datapage.="<option value='$i' $sel>$i</option>";
	  }
	
	  if($sampai<$npage) {
		  $nextpage=$sampai+1;
		  $datapage.="<option value='$nextpage'> > </option>";	
	  }
	
	  if($sampai<>$npage) {
	  $lastpage=$npage;
	  $datapage.="<option value='$npage'> >> </option>";	
	  }
	
	  $datapage.="</select>";
	  return $datapage;
	}
	
	function init_page()
	{
		$dt="<div id='page' style='float:left '>
                        <select name='page' onchange='display()' id='page'>
                        </select>
                      </div>
                        <input name='btnprev' type='button' id='btnprev' onclick='this.form.page.value--; display();' value='Prev' />
                        <input name='btnnext' type='button' id='btnnext' onclick='this.form.page.value++; display();' value='Next' />
				       Total&nbsp;<input name='frek' type='text' class='numeric_textbox' id='frek' size='10' readonly='readonly' />&nbsp;records
					  <input type='button' name='Button' value='Display' onclick='page.value=1;display()' />";
		echo $dt;
	}
	
}
?>