<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>留言板</title>
<link rel="stylesheet" type="text/css" href="css.css">
<script type="text/javascript">
function newthread_submit_check(s){
	var str = document.getElementById('newthread_'+s).value;
	var len = 0;
	for(var i = 0; i < str.length; i++) len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (document.charset == 'utf-8' ? 3 : 2) : 1;
	document.getElementById('newthread_'+s+'_check').innerHTML= len;
	document.getElementById('newthread_submit').disabled=(document.getElementById('newthread_name').value.length>50 || document.getElementById('newthread_contact').value.length>50)?true:false;
}
function addreply(table,tid){
	i=0;
	while(i<2){
		table = table.parentNode;
		if(table.tagName.match(/table/i)) i++;
	}
	if(table.rows.length == 2){
		cell = table.insertRow(2).insertCell(0);
		cell.className = 'content';
		cell.innerHTML = document.getElementById('replyformat').innerHTML.replace(/<TID>/i,'<input name="tid" type="hidden" value="'+tid+'">').replace(/<img>/,'<img src="guestbook.php?a=v">');
		table.rows[2].insertCell(1).className="threadmenu";
	}else table.rows[2].style.display = '';
}
window.onscroll = function(){
	document.getElementById('contralbar').style.top = document.documentElement.scrollTop + 'px';
}
</script>
<style type="text/css">
a img {
	border: 0px transparent none;
	margin: 1px;
}
hr {
	border: 1px dotted #663300;
	width: 95%;
	height: 1px;
}
textarea {
	overflow: auto;
}
#newthread table {
	width: 600px;
	margin: auto;
	border-collapse: collapse;
	background: #F8F0E9 url('image/guestbook/CG0011926.gif') no-repeat right bottom;
}
#newthread td {
	padding: 2px 2px 2px 5px;
}
table.thread {
	border: 1px solid #8C7D52;
	border-collapse: collapse;
	width: 600px;
	text-align: left;
	line-height: 18px;
}
table.thread td.head {
	border-bottom: 1px #8C7D52 solid;
	background-color: #DECFAD;
	padding: 5px;
}
table.thread td.content {
	background-color: #FFF7E7;
	width: 480px;
	padding-left: 10px;
	background-image: url('image/guestbook/title.gif');
	background-repeat: repeat-x;
}
table.thread td.threadmenu {
	width: 120px;
	border-left: 1px #8C7D52 solid;
	background: #EEE7D5;
	color: #EEE7D5;
}
table.thread span.name {
	float: left;
}
table.thread span.time {
	float: right;
}
div#threadlist {
	width: 600px;
	float: left;
}
div#contralbar {
	float: right;
	width: 125px;
	position: relative;
}
</style>
</head>

<body>

<div class="head">
	留言板</div>
<form id="newthread" method="post" action="https://cgsword.com/guestbook.php?a=newthread" style="display: none">
	<table style="text-align: left">
		<tr>
			<td style="background: #804000; padding: 5px; text-align: center; color: #ffffff" colspan="2">發新留言</td>
		</tr>
		<tr valign="top">
			<td>名稱: <input id="newthread_name" name="name" type="text" style="width: 200px; height: 18px" onkeyup="newthread_submit_check('name')">
			(<span id="newthread_name_check" style="vertical-align:top">0</span>/50字元)</td>
		</tr>
		<tr>
			<td>標題: <input name="title" type="text" style="width: 250px; height: 18px"></td>
		</tr>
		<tr>
			<td>內容: <textarea name="contents" style="width: 300px; height: 120px"></textarea></td>
		</tr>
		<tr>
			<td>驗證: <input type="text" name="verify" style="height: 18px"><img id="newthreadvimg" style="vertical-align:bottom"></td>
		</tr>
		<tr>
			<td style="text-align: center">
			<input id="newthread_submit" type="submit" value="發出留言" style="width: 80px; height: 35px" onclick="this.style.display='none'"><br><br>
			如需附圖，可到<a href="https://www.facebook.com/page.cgsword" target="_blank">Facebook專頁</a>發訊息或帖子，專頁只會回應需新增或更新的情報</td>
		</tr>
	</table>
</form>
<div id="replyformat" style="display: none">
	<form method="post" action="https://cgsword.com/guestbook.php?a=reply" style="margin: 0px">
		<table>
			<tr>
				<td style="background: #804000; padding: 5px; text-align: center; color: #ffffff">
				回覆<tid></td>
			</tr>
			<tr>
				<td>名稱: <input name="name" type="text" style="width: 200px; height: 18px"></td>
			</tr>
			<tr>
				<td>聯絡: <input name="contact" type="text" style="width: 250px; height: 18px"></td>
			</tr>
			<tr>
				<td>內容:
				<textarea name="contents" style="width: 300px; height: 120px"></textarea></td>
			</tr>
			<tr>
				<td>驗證: <input type="text" name="verify" style="height: 18px"><img></td>
			</tr>
			<tr>
				<td style="text-align: center">
				<input type="submit" value="發送留言" style="width: 80px; height: 25px" onclick="this.style.display='none'">　　　　<input type="button" value="取消" style="width: 80px; height: 25px" onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.display='none'"></td>
			</tr>
		</table>
	</form>
</div>
<div style="width: 750px; margin: auto">
		<div id="threadlist">
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2024-11-01 06:18 PM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b>回鍋</b></p>
				以前帳號沒ㄌ<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4949)"></div></td></tr></table>
				</td>
				<td class="threadmenu">4949</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2024-10-15 10:36 PM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b></b></p>
				砂糖可以疊40個唷～<br />
生產製作的預估欄位貌似沒有更新到(?<br />
再麻煩站長確認了<br />
謝謝<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4948)"></div></td></tr></table>
				</td>
				<td class="threadmenu">4948</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2024-03-04 10:45 AM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b></b></p>
				至今仍然來參考的網頁<br />
<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4947)"></div></td></tr></table>
				</td>
				<td class="threadmenu">4947</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2024-02-01 01:39 PM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b></b></p>
				少了炎晶寶石<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4946)"></div></td></tr></table>
				</td>
				<td class="threadmenu">4946</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2023-11-03 03:14 PM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b>新增新職業</b></p>
				<br />
<br />
戰鬥系「刀客」與生產系「鍛刀師傅」<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4945)"></div></td></tr></table>
				</td>
				<td class="threadmenu">4945</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2023-07-28 09:25 AM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b>裝備能力計算</b></p>
				因為已經新開了精10，不知是否可以更新一下裝備能力計算裡的精11選項，感謝<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4943)"></div></td></tr></table>
				<hr>
				<div style="padding-left: 7px"><i>No Author Name</i> 於 2024-07-28 11:37 PM 回覆<br><br>
					<div style="padding-left: 10px">行家</div>
														</div>
				<hr>
				</td>
				<td class="threadmenu">4943</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2023-03-13 05:36 PM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b>護士就職</b></p>
				第二個<br />
157.160附近找不到<br />
<br />
白天時跟【法蘭城】(153,139)南北向街上隨機出現的護士喬斯琪對話<br />
153.139有找到<br />
(初心版)<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4942)"></div></td></tr></table>
				</td>
				<td class="threadmenu">4942</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2023-01-25 07:59 AM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b>新轉址的網站被抓到有病毒</b></p>
				不知道是否有人跟我一樣<br />
點進去就被防毒擋下<br />
因為有病毒@Q@&quot;<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4941)"></div></td></tr></table>
				<hr>
				<div style="padding-left: 7px"><i>No Author Name</i> 於 2023-02-03 11:59 PM 回覆<br><br>
					<div style="padding-left: 10px">WIN10 沒抓到病毒<br />
可能你的太敏感</div>
														</div>
				<hr>
				</td>
				<td class="threadmenu">4941</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2023-01-04 01:10 AM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b>站長您好</b></p>
				站長您好, 我是魔力寶貝台服的玩家果凍,<br />
早在港服時期已經有留意您既網站,<br />
非常敬佩您對廣大魔力玩家既付出同埋熱誠,<br />
希望可以認識您, 同時都有事情想請教下<br />
LINE: BING9483<br />
THANKS!<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4940)"></div></td></tr></table>
				</td>
				<td class="threadmenu">4940</td>
			</tr>
		</table>
		<br>
				<table class="thread">
			<tr>
				<td class="head" colspan="2"><span class="name">Name: <i>No Author Name</i></span><span class="time">2022-10-22 11:46 PM</span></td>
			</tr>
			<tr valign="top">
				<td class="content"><br>
				<p><b>練技最好地方</b></p>
				請問…在那練技最好？我弓手59…格鬥…59…傳士51…去那地方練比較快唷？<br>
				<br>
												<table><tr><td>
				<div style="width: 100px; height: 20px; background-image: url('image/guestbook/threadmenu.gif'); background-position: -100px 20px; margin: 5px" onmouseover="this.style.backgroundPosition='-100px 0px'" onmouseout="this.style.backgroundPosition='-100px -20px'" onclick="addreply(this,4937)"></div></td></tr></table>
				<hr>
				<div style="padding-left: 7px"><i>No Author Name</i> 於 2023-01-05 10:38 AM 回覆<br><br>
					<div style="padding-left: 10px">砍牛</div>
														</div>
				<hr>
				<div style="padding-left: 7px"><i>No Author Name</i> 於 2023-02-24 12:27 PM 回覆<br><br>
					<div style="padding-left: 10px">去冰數</div>
														</div>
				<hr>
				</td>
				<td class="threadmenu">4937</td>
			</tr>
		</table>
		<br>
		</div>
		<div id="contralbar">
		<button onclick="document.getElementById('newthread').style.display = 'block';document.documentElement.scrollTop='0px';document.getElementById('newthreadvimg').src='guestbook.php?a=v'" style="width: 100px; height: 40px">
		發新留言</button><br>
		<br>
		 留言總數 4591<br>
		<br>
		上一頁		<a href="guestbook.php?page=2&amp;s=">下一頁</a>		<form method="POST" action="guestbook.php">
			<input name="s" value=""><input type="submit" value="搜尋">
		</form>
	</div>
</div>

</body>

</html>