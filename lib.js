// JavaScript Document

function addCommasObj(obj)
{
	var nStr=document.getElementById(obj.name).value;  
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	var ret=x1+x2;
	document.getElementById(obj.name).value=ret;
}


String.prototype.ReplaceAll = function(stringToFind,stringToReplace)
{
    var temp = this;
    var index = temp.indexOf(stringToFind);
        while(index != -1){
            temp = temp.replace(stringToFind,stringToReplace);
            index = temp.indexOf(stringToFind);
        }
        return temp;
}
	
function ToNumber(s)
{
//hilangkan tanda koma atau titik	desimal
 s=s.ReplaceAll(",","");
 s=s.ReplaceAll(".","");
 
 var f=parseFloat(s);
 if(isNaN(f)) var num=0; else num=f;	
 return num;
}

function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function select_all()
{
  var CheckValue=document.myform.csel.checked;
  for (var i = 0; i < document.myform.elements.length; i++) 
   {
     if(document.myform.elements[i].getAttribute("id")=="c[]") document.myform.elements[i].checked=CheckValue;
   }
}

function select_all2()
{
  var CheckValue=document.myform.csel.checked;
  for (var i = 0; i < document.myform.elements.length; i++) 
   {
     if(document.myform.elements[i].getAttribute("name")=="c[]") document.myform.elements[i].checked=CheckValue;
   }
}

function deliver_all()
{
  for (var i = 0; i < document.myform.elements.length; i++) 
   {
     if(document.myform.elements[i].getAttribute("name")=="isdelivered[]") document.myform.elements[i].checked=!document.myform.elements[i].checked;
   }
}

function confirm_del(pg)
{
var isok=confirm("Anda yakin untuk menghapus data ini?");
if (isok==true) 
  {
	  location.href=pg;
  }

}

function confirm_delsel()
{
var isok=confirm("Anda yakin untuk menghapus data ini?");
if (isok==true) 
  {
	  document.myform.delsel.value="Y";
	  document.myform.submit();
	  
  }

}
