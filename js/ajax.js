﻿var xmlHttp;
var preloadajaxloader = new Image(),ajax_loadingbg = new Image();
preloadajaxloader.src = 'image/ajax-loader.gif';
if(navigator.userAgent.match("MSIE 7|Firefox|Chrome") !== null) ajax_loadingbg.src = 'image/ajax_loading.png';
else ajax_loadingbg.src = 'image/ajax_loading.gif';

function ajax(method,url,send){
	if(window.XMLHttpRequest) xmlHttp = new XMLHttpRequest();
	else{	if(window.ActiveXObject){try{xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){try{xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");}
		catch(e){}}}
	}
	xmlHttp.open(method, url, true);
	if(method == "POST"){
		xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlHttp.setRequestHeader("Content-length", send.length);
		xmlHttp.setRequestHeader("Connection", "close");
	}
	//xmlHttp.setRequestHeader("Content-Type", "text/html;charset=utf-8");
	xmlHttp.setRequestHeader("If-Modified-Since","0");
	xmlHttp.send(send);
}

function getfile(id,s,f,fn){
	showloading('visible');
	ajax("GET","getfile.php?s="+s+"&f="+f,null);
	xmlHttp.onreadystatechange = function(){
		if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
			document.getElementById(id).innerHTML = xmlHttp.responseText;
			setTimeout("showloading('hidden')",500);
			fn();
		}
	}
}

function getrequesturl(){
	ajax("GET","http_referer.php",null);
	xmlHttp.onreadystatechange = function(){
		if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
			response = xmlHttp.responseText.split('&');
			response[0] = response[0].split('=');
			return response[0][1];
		}
	}
}

// loading box
var loadingbody = '<img src="image/ajax-loader.gif" width="16" height="16"> Loading...';
var restorebody = '<img src="image/ajax-loader.gif" width="16" height="16"> Restore...';
if(navigator.userAgent.match('MSIE 6') !== null) document.write('<style>div#loading_bg, div#restore_bg {background-color: #000000;filter: Alpha(Opacity=50);}</style>');
show = '<div id="ajax_loading">' + ((navigator.userAgent.match('MSIE 7|Firefox|Chrome') !== null) ? '<div id="loading">'+loadingbody+'</div>' : '<div id="loading_bg"><div id="loading_body"><table height="100%"><tr><td>' + loadingbody + '</td></tr></table></div></div>') + '</div>';
show+= '<div id="ajax_restore">' + ((navigator.userAgent.match('MSIE 7|Firefox|Chrome') !== null) ? '<div id="restore">'+restorebody+'</div>' : '<div id="restore_bg"><div id="restore_body"><table height="100%"><tr><td>' + restorebody + '</td></tr></table></div></div>') + '</div>';
document.write(show);

function showloading(a){
	ajax_loading_div = document.getElementById('ajax_loading');
	if(a == 'visible') ajax_loading_div.style.top = ((window.innerHeight ? window.innerHeight : document.documentElement.clientHeight) - ajax_loading_div.clientHeight) * 0.5 + document.documentElement.scrollTop + 'px';
	ajax_loading_div.style.visibility = a;
}

function showrestore(){
	ajax_restore_div = document.getElementById('ajax_restore');
	ajax_restore_div.style.top = ((window.innerHeight ? window.innerHeight : document.documentElement.clientHeight) - ajax_restore_div.clientHeight) * 0.5 + document.documentElement.scrollTop + 'px';
	ajax_restore_div.style.visibility = 'visible';
	setTimeout("ajax_restore_div.style.visibility = 'hidden'",500);
}