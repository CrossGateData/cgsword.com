<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>雪拉威森塔地圖::魔力寶貝 - 御劍軒</title>
<link rel="stylesheet" type="text/css" href="css.css">
<style>
.sub { cursor: pointer;}
</style>
</head>

<body>
	<div class="head">雪拉威森塔地圖</div>
	<table class="style">
		<tr>
			<form id="floor_form" method="POST" action="100fmap.php">
			<td style="line-height: 18px">請輸入你所需要的地圖樓層: <input id="floor" type="text" name="floor" value=""> <input type="submit" value="發送"><br>
				輸入「1 50 95-100」即顯示第1,50,95,96,97,98,99,100層地圖<br>
				圖片均由 <a href="http://homepage3.seed.net.tw/web@3/freeflying" target="_blank">飛翔之翼</a> 提供</td>
			</form>
		</tr>
	</table>
		<table id="every" class="style" style="margin-top: 5px">
		<tr>
			<th colspan="5">雪塔各層</th>
		</tr>
		<tr>
			<td>01-10</td>
			<td>11-20</td>
			<td>21-30</td>
			<td>31-40</td>
			<td>41-50</td>
		</tr>
		<tr>
			<td>51-60</td>
			<td>61-70</td>
			<td>71-80</td>
			<td>81-90</td>
			<td>91-100</td>
		</tr>
	</table>
	<table id="defualt" class="style" style="margin-top: 5px">
		<tr>
			<th colspan="2">預設路線圖 - 按下該列即可</th>
		</tr>
		<tr class="head">
			<td>相關任務</td>
			<td>樓層</td>
		</tr>
		<tr>
			<td>人形系、植物系、飛行系、昆蟲系寵物座騎學習</td>
			<td>1-5</td>
		</tr>
		<tr>
			<td>特殊系、野獸系寵物座騎學習</td>
			<td>1 15 14</td>
		</tr>
		<tr>
			<td>金屬系、龍　系寵物座騎學習</td>
			<td>1 35 34</td>
		</tr>
		<tr>
			<td>不死系寵物座騎學習</td>
			<td>1 40 39</td>
		</tr>
		<tr>
			<td>扮裝欺詐(羊頭狗肉習得)</td>
			<td>1 45-42</td>
		</tr>
		<tr>
			<td>大小寶箱(因果報應習得)</td>
			<td>1 49-54</td>
		</tr>
		<tr>
			<td>解救普塔(騎士之譽習得)</td>
			<td>1 50 60-62 65</td>
		</tr>
		<tr>
			<td>解咒之藥(精神衝擊波習得)</td>
			<td>1 95-93</td>
		</tr>
		<tr>
			<td>聖域守護護(喵喵帽)</td>
			<td>1 50 95-100</td>
		</tr>
	</table>
	<script>
		td = document.getElementById('every').getElementsByTagName('td');
		for(i=0;i<td.length;i++){
			td[i].onmouseover = function(){this.className = 'sub';}
			td[i].onmouseout = function(){this.className = '';}
			td[i].onclick = function(){
				document.getElementById('floor').value = this.innerHTML;
				document.getElementById('floor_form').submit();
			}
		}
		table = document.getElementById('defualt');
		for(i=2;i<table.rows.length;i++){
			row = table.rows[i];
			row.onmouseover = function(){this.className = 'sub';}
			row.onmouseout = function(){this.className = '';}
			row.onclick = function(){
				document.getElementById('floor').value = this.cells[1].innerHTML;
				document.getElementById('floor_form').submit();
			}
		}
	</script>
	</body>

</html>