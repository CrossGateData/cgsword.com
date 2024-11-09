var i=0
var show = "index";
var divlist = new Array();
var historylist = new Array();
function getskill(id,page){
	if(id == "history"){
		i = i-1;
		showrestore();
		document.getElementById(show).style.display = 'none';
		document.getElementById(historylist[i]).style.display = '';
		show = historylist[i];
	}else{
	if(id != show){
		document.getElementById(id).style.display = 'block';
		if(divlist[id] != 1){
			getfile(id,'skill',page);
			divlist[id] = 1;
		}else showrestore();
		document.getElementById(show).style.display = 'none';
		historylist[i] = show;
		i++;
		show = id;
	}}
}