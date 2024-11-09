<!DOCTYPE html>
<html>
<head>
<title>寵物能力模擬::魔力寶貝 - 御劍軒</title>
<link rel="stylesheet" type="text/css" href="css.css">
<script src="js/jquery-1.10.2.min.js"></script>
<script>
$(function(){
	var lastCalTime = new Date();
	$('#calForm').on({
		submit: function(e){
			e.preventDefault();
			var calTime = new Date();
			if( calTime - lastCalTime < 200 ) {
				console.log('Too fast');
				return;
			}
			var form = this;
			$('[type=submit]', form).prop('disabled', true);
			lastCalTime = calTime;
			$.ajax({url:'',type:'post',dataType:'json',data:$(form).serialize(),success:function(data){
				var pet = $(form).data('pet');
				if( pet ) pet.calResult = data;
				$(form).trigger('render',[data]);
			},complete:function(){
				$('[type=submit]', form).removeAttr('disabled');
			}});
		},
		importPet: function(e, pet){
			$(this).data('pet', pet);
			$(this).addClass('bindPet');
			$('#calForm input[name=cur_level]').val(pet.level);
			$('#calForm input[name=cur_ability]').val([pet.hp,pet.mp,pet.atk,pet.str,pet.spd].join(' '));
			$('#calForm input[name=rp]').val(pet.bp.remain);
			$('#calForm input[name=multiplier]').val(pet.multiplier);
			if( pet.bp.remain == pet.level - 1 ) $('#calForm input[name=ap][value=5]').prop('checked', true);
			else if( pet.level > 1 && pet.bp.remain == 0 )  $('#calForm input[name=ap][value=6]').prop('checked', true);
			if( pet.level == 1 ) $('#calForm input[name=from_ability]').val($('#calForm input[name=cur_ability]').val());
			if( pet.lockPet ) {
				$(this).trigger('lockPet', [pet.lockPet]);
				$('#petList').scrollTop(pet.lockPet.offset().top);
			}
			if( pet.calResult ) $(this).trigger('render',[pet.calResult]);
			pet.dom.addClass('binded');
		},
		unbindPet: function(){
			$(this).removeClass('bindPet');
			$(this).find('.data-petname').text('');
			var pet = $(this).data('pet');
			if( !pet ) return;
			$('#petList').removeClass('filter');
			$('#petList .lock').removeClass('lock');
			this.reset();
			$(this).data('pet', null);
			pet.dom.removeClass('binded');
		},reset: function(){
			$('#caldata').empty();
		},lockPet: function(e, dom){
			var pet = $(this).data('pet');
			if( pet ) pet.lockPet = dom;
			$(this).find('input[name=multiplier]').val(dom.data('multiplier'));
			dom.parent().children('.lock').removeClass('lock');
			dom.addClass('lock');
			$(this).find('.data-petname').text(dom.text());
		},render: function(e, data){
			var dataT = $('#caldata').empty(),html='';
			var max_g=min_g=0;
			if( data.type == 'normal' ) {
				data.data.forEach(function(item,i){
					html+='<tr>';
					var count=0,rand=[],grow=[];
					for(var j=0;j<5;j++){grow.push(item[j][1]);count+=item[j][1];rand.push(item[j][2])};
					html+='<td>'+(['+體','+力','+防','+敏','+魔','無+'][item[5]])+'</td>';
					html+='<td>'+count+'檔</td>';
					html+='<td>'+grow.join(' ')+'</td>';
					html+='<td>'+rand.join('')+'</td>';
					var m=$('#calForm input[name=multiplier]').val();
					html+='<td><a data-sim="['+m+',['+grow.join(',')+'],['+rand.join(',')+']]">模擬</a></td>';
					html+='<tr>';
					if(min_g<count)min_g=count;
					if( i==0||count>max_g) max_g=count;
				});
				
			} else if( data.type == 'level1' ) {
				data.result.posi.forEach(function(item,i){
					html+='<tr>';
					var count=0,grow=[];
					item.forEach(function(cell){grow.push(cell);count+=cell;});
					count-=10;
					html+='<td>'+count+'檔</td>';
					html+='<td>'+grow.join(' ')+'</td>';
					html+='</tr>';
					if(min_g<count)min_g=count;
					if( i==0||count>max_g) max_g=count;
				});
			} else if (data.type == 'use_level1') {
				data.data.forEach(function(item,i){
					html+='<tr>';
					var count=0,grow=[];
					item[0].forEach(function(cell){grow.push(cell);count+=cell;});
					html+='<td>'+count+'檔</td>';
					html+='<td>'+grow.join(' ')+'</td>';
					html+='<td>'+item[1].join('')+'</td>';
					var m=$('#calForm input[name=multiplier]').val();
					html+='<td><a data-sim="['+m+',['+grow.join(',')+'],['+item[1].join(',')+']]">模擬</a></td>';
					html+='</tr>';
					if(min_g<count)min_g=count;
					if( i==0||count>max_g) max_g=count;
				});
			}
			dataT.html(html);
			dataT.find('[data-sim]').on('click', function(){
				var data = $(this).data('sim');
				moveToSim(data[0],data[1],data[2]);
			});
			var pet = $(this).data('pet');
			if( pet ) {
				pet.summary = {max_g:max_g,min_g:min_g};
				pet.dom.find('.title').text('Lv'+pet.level+' '+[pet.hp,pet.mp,pet.atk,pet.str,pet.spd].join(' ')+'('+(min_g==max_g?max_g:min_g+'-'+max_g)+'檔)');
			}
		}
	});
	$('#calForm .bindPet-unlockBtn').on('click', function(){
		$('#calForm').trigger('unbindPet');
	});
	$('#simForm').on({
		submit: function(e){
			e.preventDefault();
			var form = this;
			var grow = $('[name=grow]',form).val();
			var rand = $('[name=random]',form).val();
			var mult = $('[name=multiplier]', form).val();
			var level = $('[name=level]', form).val();
			var ap = $('[name=ap]:checked', form).val();
			var am = $('[name=am]', form).val();
			var result = genDlist(grow,rand,mult,level,ap,am);
			$('[name=grow]',form).val(result.grow);
			$('[name=rand]',form).val(result.rand);
			$('[name=multiplier]', form).val(result.mult|0);
			$('[name=level]', form).val(result.lv);
			var div = $('#simResult').css('display',''), table=$('#simData').empty();
			var useInt = $('[name=useInt]', form).prop('checked');
			var showBA = $('[name=showBA]', form).prop('checked');
			if( result.error == 1 ) {
				
			} else {
				var head = ['等級','體力','魔力','攻擊','防禦','敏捷','精神','回復','體BP','攻BP','防BP','敏BP','魔BP','剩餘BP'];
				var html = '<tr>';
				for(var i=0;i<head.length;i++) html+='<th>'+head[i]+'</th>';
				html+='</tr>';
				result.data.forEach(function(item){
					html+='<tr>';
					html+='<td>'+item.level+'</td>';
					item.dp.forEach(function(d){html+='<td>'+(useInt?Math.floor(d):d.toFixed(2))+'</td>'});
					item.bp.forEach(function(b,i){
						html+='<td>'+(useInt?(b|0):b.toFixed(2))+(showBA&&item.ba[i]>0?'(+'+item.ba[i]+')':'')+'</td>'
						
					});
					html+='<td>'+item.bpremain+'</td>';
					html+='</tr>';
				});
				table.html(html);
				for(var i=0,dg=[],eb=[],td=[],bd=[];i<result.dg.length;i++){
					dg.push(result.dg[i].toFixed(2));
					td.push(((result.data[result.data.length-1].dp[i]-result.data[0].dp[i])/(result.data[result.data.length-1].level-1)).toFixed(2));
					if( result.eb[i] ) {
						eb.push(result.eb[i].toFixed(2));
						bd.push(((result.data[result.data.length-1].bp[i]-result.data[0].bp[i])/(result.data[result.data.length-1].level-1)).toFixed(2));
					}
				}
				$('#simResult_dpGrow').text(dg.join(' '));
				$('#simResult_ebGrow').text(eb.join(' '));
				$('#simResult_tdGrow').text(td.join(' '));
				$('#simResult_bdGrow').text(bd.join(' '));
			}
		}
	});
	$('#bpForm').on({
		submit: function(e){
			e.preventDefault();
			var form = this;
			var bp = $('[name=bp]',form).val();
			var reg = /^[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+)[^\d]*.*$/;
			if( !bp.match(reg) ) return;
			bp = bp.replace(reg,'$1 $2 $3 $4 $5');
			$('[name=bp]',form).val(bp);
			var result = bp2dp(bp.split(' '),false,true);
			result.forEach(function(dp,i){$('#bpResult .rs-'+i).text(dp)});
		}
	});
	
	$('#random_grow_btn').on('click', function(){for(var i=0,g=[];i<5;i++) g.push((Math.random()*55)|0);$('#simForm input[name=grow]').val(g.join(' '));}).trigger('click');
	$('#random_bp_btn').on('click', function(){for(var i=0,g=[];i<5;i++) g.push((Math.random()*500)|0);$('#bpForm input[name=bp]').val(g.join(' '));}).trigger('click');
	$('#random_random_btn').on('click', function(){for(var i=0,g=[0,0,0,0,0];i<10;i++) g[Math.floor(Math.random()*5)]++;$('#simForm input[name=random]').val(g.join(' '));}).trigger('click');
	$('#bpForm').trigger('submit');
	
	function bp2dp(bp,nobase,dkpfo){
		var d=nobase?[0,0,0,0,0,0,0]:[20,20,20,20,20,100,100],m=[[8,2,3,3,1],[1,2,2,2,10],[0.2,2.7,0.3,0.3,0.2],[0.2,0.3,3,0.3,0.2],[0.1,0.2,0.2,2,0.1],[-0.3,-0.1,0.2,-0.1,0.8],[0.8,-0.1,-0.1,0.2,-0.3]];
		for(var i=0;i<m.length;i++) {for(var j=0;j<m[i].length;j++) d[i]+=m[i][j]*bp[j];if(dkpfo)d[i]=d[i]|0;}
		return d;
	}
	function genG2B(m){var b=g=0,g2b=[];for(var i=0;i<=m;i++) {if(i>1&&(i%5==0||i%5==1) ) b+=0.005;g2b[i]=b;b += 0.04;}return g2b;}
	function calG2D(gs,r,m,ls,le,ap,am){
		var max=Math.max.apply(Math,gs),g2b=genG2B(max),b=[],ds=[],eb=[],remain=0,ba=[];
		for(var i=0;i<5;i++) {eb[i]=g2b[gs[i]];b[i]=(gs[i]+r[i])*m;ba[i]=0;}
		ds.push({level:1,dp:bp2dp(b),bp:b.slice(),ba:ba.slice(),bpremain:remain});
		for(var j=2;j<=le;j++){
			remain++;
			for(var i=0;i<5;i++) b[i]+=eb[i];
			if(ap<5&&am>0&&j%am==0){
				for(var k=0,max=0;k<5;k++) { if(k==ap) continue; max+=b[k]|0;}
				if( b[ap] < max) {
					var dif = max - (b[ap]|0);
					if( dif > remain ) {b[ap]+=remain;ba[ap]+=remain;remain=0;} else {b[ap]+=dif;remain-=dif;ba[ap]+=dif;}
				}
			}
			if(j>=ls) ds.push({level:j,dp:bp2dp(b),bp:b.slice(),ba:ba.slice(),bpremain:remain});
		}
		if( remain > 0 && ap < 5 ) {
			for(var k=0,max=0;k<5;k++) { if(k==ap) continue; max+=Math.floor(b[k]);}
			if( b[ap] < max) {
				var dif = max - Math.floor(b[ap]);
				if( dif > remain ) {b[ap]+=remain;ba[ap]+=remain;remain=0;} else {b[ap]+=dif;remain-=dif;ba[ap]+=dif;}
				ds.push({level:le,dp:bp2dp(b),bp:b.slice(),ba:ba.slice(),bpremain:remain});
			}
		}
		return {data:ds,dg:bp2dp(eb,true),eb:eb,bpremain:remain};
	}
	function genDlist(grow,rand,mult,lv,ap,am){
		var result = {grow:grow,rand:rand,mult:mult,lv:lv,msg:'',error:0,ap:ap};
		var reg = /^[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+)[^\d]*(\d+)[^\d]*.*$/;
		if( !grow.match(reg) ) {result.msg='成長檔格式錯誤';result.error=1;return result;}
		grow = grow.replace(reg,'$1 $2 $3 $4 $5');
		result.grow=grow;
		grow=grow.split(' ');
		for(var i=0;i<grow.length;i++) grow[i]*=1;
		if( !rand.match(reg) ) {result.msg='隨檔格式錯誤';result.error=1;return result;}
		rand = rand.replace(reg,'$1 $2 $3 $4 $5');
		result.rand=rand;
		rand=rand.split(' ');
		for(var i=0;i<rand.length;i++) rand[i]*=1;
		if(isNaN(mult)||mult<=0) {result.msg='倍率必須為大於0的數字';result.error=1;return result;}
		if(mult>1) mult/=100;
		result.mult = mult*100;
		var stlv = 1, edlv = 180;
		if( isNaN(lv) ) {
			if( lv.match(/^[^\d]*(\d+)(\+|-|~)[^\d]*$/)) {
				stlv = lv.replace(/[^\d]/g,'')*1;
			}else if(lv.match(/^[^\d]*~(\d+)[^\d]*$/)) {
				edlv = lv.replace(/[^\d]/g,'')*1;
			}else if( lv.match(/^[^\d]*(\d+)[^\d]+(\d+)[^\d]*.*$/)) {
				lv = lv.replace(/^[^\d]*(\d+)[^\d]+(\d+)[^\d]*.*$/,'$1-$2').split('-');
				stlv=lv[0]*1;
				edlv=lv[1]*1;
			} else {result.msg='等級格式錯誤';result.error=1;return result;}
		} else {lv=Math.abs(lv);stlv=lv;edlv=lv;}
		if(edlv<stlv) {stlv^=edlv;edlv^=stlv;stlv^=edlv;}
		if( stlv==edlv ) result.lv=stlv;
		else result.lv = stlv+'-'+edlv;
		if( isNaN(ap ) ) {result.msg='加點方式格式錯誤';result.error=1;return result;}
		if( isNaN(am ) ) {result.msg='加點間隔格式錯誤';result.error=1;return result;}
		ap*=1;
		am*=1;
		var calD = calG2D(grow,rand,mult,stlv,edlv,ap,am);;
		result.data = calD.data;
		result.dg = calD.dg;
		result.eb = calD.eb;
		return result;
	}
	function moveToSim(mult,grow,random){
		$('#simForm input[name=multiplier]').val(mult);
		$('#simForm input[name=grow]').val(grow);
		$('#simForm input[name=random]').val(random);
		$('#simForm').trigger('submit');
	}
});
$(function(){
	$('<input>',{type:'hidden',name:'act'}).appendTo('#calForm').val('cal');
	$('<input>',{type:'hidden',name:'key'}).appendTo('#calForm').val('0a6ea021fd47f900ca9c340bf8e6dada');
	$('#petSlot_ent').on('click', function(e){$('#petSlot_Con').addClass('show');});
});
$(function(){
	var sources = [], areas = [], pets = [], petsUnid = {};
	$.ajax({url:'',type:'post',dataType:'json',data:{key:'0a6ea021fd47f900ca9c340bf8e6dada',act:'loadPet'},success:function(data){
		data.forEach(function(obj){
			var div = $('<div>').appendTo('#petList').on({
				click: function(e){
					$('#calForm').trigger('lockPet', [$(this)]);
				}
			});
			div.text(obj.name).addClass('type-'+obj.type).data({multiplier:obj.factor,id:obj.id});
			obj.dom = div;
			pets[obj.id] = obj;
			if(!petsUnid[obj.unid]) petsUnid[obj.unid]=[];
			petsUnid[obj.unid].push(obj.id*1);
		});
	}});
	function ImageSource(){
		var source = this;
		this.id = sources.length;
		sources.push(this);
		this.canvas = document.createElement('canvas');
		this.context = this.canvas.getContext('2d');
		this.image = new Image();
		this.imgData = null;
		this.biData = [];
		this.config = {threshold:{r:250,g:255,b:255}};
		this.area = [];
		this.imageLoaded = false;
		this.imageLoadedCB = [];
		this.image.addEventListener('load', function(){
			source.imageLoaded = true;
			source.canvas.width = this.width;
			source.canvas.height = this.height;
			source.context.drawImage(this, 0, 0, this.width, this.height);
			source.imgData = source.context.getImageData(0,0,this.width,this.height);
			source.biData.length = this.width * this.height;
			source.bization();
			source.searchTarget();
			source.imageLoadedCB.forEach(function(cb){cb();});
		});
	}
	ImageSource.prototype = {
		loadImage: function(blob, cb){
			this.image.src = blob;
			this.imageLoadedCB.push(cb);
		},
		bization: function(){
			if( !this.imageLoaded ) return;
			var starttime = new Date();
			//*
			var da=this.imgData.data;
			var i=da.length;
			var th=this.config.threshold.r*1;
			var j=(i>>2)-1;
			if( !da ) return;
			while(i--){
				var a = 1;
				if( this.imgData.data[--i] < th ) {a = 0;i-=2;}
				else if( this.imgData.data[--i] < th ) {a = 0;i--;}
				else if( this.imgData.data[--i] < th ) {a = 0;}
				this.biData[j--] = a;
			}
			//*/
				//this.biData[j] = (this.imgData.data[i] >= th.r && this.imgData.data[i+1] >= th.g && this.imgData.data[i+2] >= th.b)?1:0;
			//for (var da=this.imgData.data,i=0,l=da.length,th=this.config.threshold,j=0;i<l;i+=4,j++)
			//this.biData[j] = (this.imgData.data[i] >= th.r && this.imgData.data[i+1] >= th.g && this.imgData.data[i+2] >= th.b)?1:0;
			//console.log('bization', new Date()-starttime);
		},
		searchTarget: function(){
			this.area = [];
			for (var i=6,w=6,h=0;i<this.biData.length;i++){
				if(this.biData[i]==1){
					var c=true;
					for(j=0;j<193;j+=2) { if( this.biData[i+j]!=1 || this.biData[i+j+1]!=0) {c=false;break;}}
					if(c==true) {
						var start = i+98+(this.imgData.width*(193-15));
						for(j=0;j<99;j+=2,start+=2) { if( this.biData[start]!=1 || this.biData[start+1]!=0) {c=false;break;}}
						if( c== true) this.area.push({x:w-5,y:h-15,w:205,h:208});
					}
				}
				if( ++w>=(this.imgData.width-198) ) {w=6;h++;i=h*this.imgData.width+w;}
			}
			for(var m=0;m<this.area.length;m++) {
				var area = this.area[m];
				area.pet = {multiplier:20,bp:{},corr:{},anit:{}};
				var obj = {'level':[45,19,3],'hp':[67,36,4],'mp':[152,36,4],'atk':[137,168,4],'str':[137,183,4],'spd':[137,198,4],'spr':[187,168,3],'rec':[187,183,3],'bp.remain':[75,69,3],'bp.hp':[75,85,3],'bp.atk':[75,100,3],'bp.str':[75,115,3],'bp.spd':[75,130,3],'bp.mgc':[75,145,3],'corr.crt':[137,68,3],'corr.rea':[137,85,3],'corr.foc':[187,69,3],'corr.dod':[187,84,3],'anit.poi':[137,115,3],'anit.sle':[137,130,3],'anit.sto':[137,145,3],'anit.dru':[187,115,3],'anit.con':[187,130,3],'anit.for':[187,145,3],'skill':[81,183,2]};
				var pair=[254, 255, 195, 2, 12, 1, 126, 384, 238, 241];
				for(var k in obj) {
					var p = area.pet,set=obj[k],kk=k.split('.');
					while(kk.length>1) p=p[kk.shift()];
					var pt = set[0]+area.x+(area.y+set[1])*this.imgData.width;
					for(var i=0,number='';i<set[2];i++) {
						var ptt = pt+i*6, slot = 0;
						for (var j=0;j<9;j++,ptt+=this.imgData.width){slot<<=1;if (this.biData[ptt] == 1) slot |= 1;}
						if (slot == 0 && this.biData[pt+i*6+2] == 1) slot = 255;
						if(pair.indexOf(slot) >= 0) number += pair.indexOf(slot);
					}
					p[kk[0]] = parseInt(number) || 0;
				}
				//corr,anit,skill
				var a = area.pet.corr,b=0;
				//console.log(a);
				'crt,rea,foc,dod'.split(',').forEach(function(item){b = (b << 8) | (a[item] & 0xff);});
				
				var a = area.pet.anit,c=0;//console.log(a);
				'poi,sle,sto'.split(',').forEach(function(item){c = (c << 8) | (a[item] & 0xff);});
				var a = area.pet.anit,d=0;//console.log(a);
				'dru,con,for'.split(',').forEach(function(item){d = (d << 8) | (a[item] & 0xff);});

				area.pet.unid = ''+b+c+d+area.pet.skill;
				//console.log(b,c,d,area.pet.skill,area.pet.unid);
			}
			return this.area.length > 0;
		},
		capArea: function(area){
			var canvas = document.createElement('canvas');
			canvas.width=area.w;
			canvas.height=area.h;
			var context = canvas.getContext('2d');
			context.putImageData(this.context.getImageData(area.x,area.y,area.w,area.h),0,0);
			return canvas.toDataURL();
		},
		drawBi: function(canvas){
			var starttime = new Date();
			var context = canvas.getContext('2d');
			canvas.width = this.image.width;
			canvas.height = this.image.height;
			var imgD = context.getImageData(0,0,canvas.width,canvas.height);
			for(var i=0,l=imgD.data.length,j=0;i<l;i+=4,j++) {imgD.data[i+3]=255;imgD.data[i]=imgD.data[i+1]=imgD.data[i+2]=this.biData[j]==1?255:0;}
			context.putImageData(imgD,0,0);
			for(var m=0;m<this.area.length;m++) {
				var area = this.area[m];
				context.beginPath();
				context.lineWidth=4;
				context.strokeStyle="red";//console.log(area);
				context.rect(area.x,area.y,area.w,area.h);
				context.stroke();
			};
			//console.log('drawBi', new Date()-starttime);
		}
	}
	function waitPasteEvent(){
		if( $('#pasteArea img').length > 0 ){
			var blob = $('#pasteArea img').eq(0).attr('src');
			handleBlob(blob);
		}
		$('#pasteArea').empty();
	}
	function handleBlob(blob){
		var starttime = new Date();
		var source = new ImageSource();
		source.loadImage(blob, function(){
			if( source.area.length == 0 ) $('#adjustArea').trigger('bindSource', [source]);
			else $('#petSlot').trigger('addPet',[source]);
		});
	}
	$(document).on({
        'dragover': function(evt){
            evt.stopPropagation();
            evt.preventDefault();
        }
        ,'drop': function(evt){
            evt.stopPropagation();
            evt.preventDefault();
            var files = evt.originalEvent.dataTransfer.files;
            for (var i = 0, f; f = files[i]; i++) {
				var reader = new FileReader();
				reader.onload = function(event){handleBlob(event.target.result);}
				reader.readAsDataURL(f);
			}
        }
    });
	$('#pasteArea').on('paste', function(event){
		var clipboardData = (event.originalEvent || event).clipboardData;
		if( clipboardData == undefined || clipboardData.items == undefined ) {	//ff,ie
			setTimeout(waitPasteEvent, 200);
			return;
		}
		var items = clipboardData.items, blob = false;
		if( items.length <= 0 ) return;
		if( items[0].kind != 'file') {
			if( items.length < 2 || items[1].kind != 'file' ) {setTimeout(waitPasteEvent, 200);return;}
			blob = items[1].getAsFile();
		}
		if( !blob ) blob = items[0].getAsFile();
		var reader = new FileReader();
		reader.onload = function(event){$('#pasteArea').empty();handleBlob(event.target.result);};
		reader.readAsDataURL(blob);
	});
	$('#adjustArea').on({
		bindSource: function(e, source){
			$(this).css('display','block');
			$(this).data('source', source);
			var canvas = $('canvas', this)[0];
			source.drawBi(canvas);
			$('label input[name=master_number]', this).val(source.config.threshold.r);
			$('label input[name=master_range]', this).val(source.config.threshold.r);
			if( canvas.width/canvas.height > 550/350 ) {
				$(canvas).css({width:550,height:550/canvas.width*canvas.height});
			} else {
				$(canvas).css({width:350/canvas.height*canvas.height,width:350});
			}
		}
	});
	$('#adjustArea label').each(function(){
		var range = $('input[type=range]', this);
		var text = $('input[type=number]', this);
		function onChange(value){
			var parent = $('#adjustArea');
			var source = parent.data('source');
			if( !source ) return;
			source.config.threshold.r=source.config.threshold.g=source.config.threshold.b=value;
			source.bization();
			source.searchTarget();
			var canvas = $('canvas', parent)[0];
			source.drawBi(canvas);
			if( canvas.width/canvas.height > 550/350 ) {
				$(canvas).css({width:550,height:550/canvas.width*canvas.height});
			} else {
				$(canvas).css({width:350/canvas.height*canvas.height,width:350});
			}
			var slot = $('#adjustArea .areaSlot').empty();
			for(var i=0;i<source.area.length;i++){
				var area = source.area[i];
				var div = $('<div>').appendTo(slot);
				var img = $('<img>').attr('src', source.capArea(area)).appendTo(div);
				$('<div>').appendTo(div).text('Lv'+area.pet.level+' '+[area.pet.hp,area.pet.mp,area.pet.atk,area.pet.str,area.pet.spd].join(' '));
			}
		}
		range.on('change', function(){text.val(this.value);onChange(this.value)});
		range.on('input', function(){text.val(this.value);onChange(this.value)});
		text.on('change', function(){range.val(this.value);onChange(this.value)});
	});
	$('#adjustArea_importBtn').on({
		click: function(e){
			var source = $('#adjustArea').data('source');
			$('#petSlot').trigger('addPet', [source]);
			$('#adjustArea .areaSlot').empty();
			$('#adjustArea').css('display','none');
		}
	});
	$('#petSlot').on({
		addPet: function(e, source){
			for(var i=0,area;area=source.area[i];i++){
				area.id = areas.length;
				areas.push(area);
				var div = $('<div>').appendTo(this).data('id', area.id);
				var pet = area.pet;
				var img = $('<img>').attr('src', source.capArea(area)).appendTo(div);
				pet.dom = div;
				$('<div>').addClass('title').appendTo(div).text('Lv'+area.pet.level+' '+[pet.hp,pet.mp,pet.atk,pet.str,pet.spd].join(' '));
				div.on('click', function(e){
					var from_ability = $('#calForm input[name=from_ability]').val();
					$('#calForm').trigger('unbindPet');
					$('#calForm input[name=from_ability]').val(from_ability);
					var area = areas[$(this).data('id')];
					var posiP = petsUnid[area.pet.unid];
					$('#petList>div').addClass('notselected');
					if(posiP) posiP.forEach(function(item){
						pets[item].dom.removeClass('notselected').addClass('selected');
					});
					$('#petList').addClass('filter');
					$('#calForm').trigger('importPet', [area.pet]);
				});
			}
		}
	});
	
	$('#petCate>span').on({
		click: function(){
			var select = !$(this).hasClass('selected');
			var id = $(this).data('id');
			if( select ) {
				$(this).addClass('selected');
				$('#petList').addClass('se-type-'+id);
			}else {
				$(this).removeClass('selected');
				$('#petList').removeClass('se-type-'+id);
			}
			if( $('#petCate>span.selected').length == 0 ) {
				$('#petList').addClass('free');
			} else {
				$('#petList').removeClass('free');
			}
		}
	});
	
	$('#searchPet').on({
		update: function(e){
			var txt = $(this).val().trim();
			if( txt.length == 0 || txt == '＿' ) {$('#petList').removeClass('filter');return;}
			$('#petList').addClass('filter');
			$('#petList>div').removeClass('notselected').removeClass('selected');
			pets.forEach(function(item){
				if( item.dom.text().match(txt) ) item.dom.addClass('selected');
				else item.dom.addClass('notselected');
			});
		},keyup: function(e){
			$(this).trigger('update');
		},paste: function(e){
			$(this).trigger('update');
		}
	});
});
</script>
<style>
input[type=text]{padding:1px;}
input[name=ap]{display:none}
input[name=ap]~span{border:1px solid silver;cursor:pointer;display:inline-block;padding:2px}
input[name=ap]:checked~span{background-color: #604C40;color:white;}

#petSlot{white-space: nowrap;overflow: auto;max-width: 780px;margin: auto;height: 190px;}
#petSlot>div{display:inline-block;width: 160px;margin-right:5px;text-align:center}
#petSlot>div>img{max-width:150px;}
#petSlot>div.binded{background-color:#D8CCA8}

#adjustArea{display:none}
#petCate>span{display: inline-block;padding: 3px;border: 1px #908860 solid;margin: 1px;width: 26px;cursor:pointer}
#petCate>span.selected{background-color: #604C40;color:white;}
#petCate>span:hover{background-color:#D8CCA8}
#petList>div{display:none;text-align:left;padding: 2px;cursor:pointer}
#petList>div:hover{background-color:#D8CCA8}
#petList>div.lock{background-color:#604C40;color:white}
#petList.free>div{display:block}
#petList.filter>div.notselected{display:none}

#petSlot_Con #petSlot_sub{display:none}
#petSlot_Con.show #petSlot_sub{display:block}
#petSlot_Con.show #petSlot_ent{display:none}
#petSlot_ent {padding: 10px;cursor: pointer;margin:0px 10px 10px 10px;border:1px solid silver}
#petSlot_ent:hover{background-color:#D8CCA8}
#petList.se-type-1>div.type-1{display:block}
#petList.filter.se-type-1>div.type-1.selected{display:block}
#petList.filter.se-type-1>div.type-1.notselected{display:none}
#petList.se-type-2>div.type-2{display:block}
#petList.filter.se-type-2>div.type-2.selected{display:block}
#petList.filter.se-type-2>div.type-2.notselected{display:none}
#petList.se-type-3>div.type-3{display:block}
#petList.filter.se-type-3>div.type-3.selected{display:block}
#petList.filter.se-type-3>div.type-3.notselected{display:none}
#petList.se-type-4>div.type-4{display:block}
#petList.filter.se-type-4>div.type-4.selected{display:block}
#petList.filter.se-type-4>div.type-4.notselected{display:none}
#petList.se-type-5>div.type-5{display:block}
#petList.filter.se-type-5>div.type-5.selected{display:block}
#petList.filter.se-type-5>div.type-5.notselected{display:none}
#petList.se-type-6>div.type-6{display:block}
#petList.filter.se-type-6>div.type-6.selected{display:block}
#petList.filter.se-type-6>div.type-6.notselected{display:none}
#petList.se-type-7>div.type-7{display:block}
#petList.filter.se-type-7>div.type-7.selected{display:block}
#petList.filter.se-type-7>div.type-7.notselected{display:none}
#petList.se-type-8>div.type-8{display:block}
#petList.filter.se-type-8>div.type-8.selected{display:block}
#petList.filter.se-type-8>div.type-8.notselected{display:none}
#petList.se-type-9>div.type-9{display:block}
#petList.filter.se-type-9>div.type-9.selected{display:block}
#petList.filter.se-type-9>div.type-9.notselected{display:none}
#petList.se-type-10>div.type-10{display:block}
#petList.filter.se-type-10>div.type-10.selected{display:block}
#petList.filter.se-type-10>div.type-10.notselected{display:none}
#calForm .bindPetEl{display:none}
#calForm.bindPet .bindPetEl{display:initial}
</style>
</head>
<body>
<div id="adjustArea">
<div class="head">調整圖片</div>
<div>
	<div style="float:left;text-align:center;width: 560px">
		<canvas style="max-height:350px"></canvas>
	</div>
	<div class="areaSlot" style="float: right;width: 240px;max-height: 350px;overflow-y: auto;overflow-x: hidden;">
		
	</div>
	<div style="clear:both"></div>
	<div>
		<label><input name="master_range" type="range" min="128" max="255" step="1"><input name="master_number" type="number" min="128" max="255" step="1"></label>
		<button id="adjustArea_importBtn">匯入</button>
	</div>
</div>
</div>

<table class="style">
	<tr>
		<th>寵物能力計算(入手等級只限1等)</th>
	</tr>
	<tr>
		<td>
		<div id="petSlot_Con">
			<div id="petSlot_ent">
				載入圖片
			</div>
			<div id="petSlot_sub">
				<div style="float:left;width:160px">
					<div id="pasteArea" contenteditable="true" style="border-radius: 4px;width:150px;height:165px;border:1px silver solid;overflow:hidden;background-image:url(image/petsim/instruc.png)"></div>
					
				</div>
				<div id="petSlot" style="float:left;width:600px"></div>
			</div>
		</div>
		<div style="float:left;width:185px;margin-right:5px">
			<div id="petCate"><span data-id="1">野獸</span><span data-id="2">不死</span><span data-id="3">飛行</span><span data-id="4">昆蟲</span><span data-id="5">植物</span><span data-id="6">特殊</span><span data-id="7">金屬</span><span data-id="8">龍</span><span data-id="9">人形</span><span data-id="10">邪魔</span></div>
			<div><input id="searchPet" type="text"></div>
			<div id="petList" class="free" style="height:208px;overflow:auto"></div>
		</div>
		<div style="text-align:left;width:370px;float:left">
			<form id="calForm">
				目前等級: <input type="text" name="cur_level" size="4" value="1"> &nbsp;&nbsp;
				倍率: <input type="text" name="multiplier" size="4" value="20" placeholder=""> <span class="data-petname"></span>
				<br>
				目前能力: <input type="text" name="cur_ability" size="40" placeholder="體力 魔力 攻擊 防禦 敏捷">
				<br>
				升級加點:
					<label><input type="radio" name="ap" value="6" checked><span>智</span></label>
					<label><input type="radio" name="ap" value="5"><span>無</span></label>
					<label><input type="radio" name="ap" value="0"><span>體</span></label>
					<label><input type="radio" name="ap" value="1"><span>力</span></label>
					<label><input type="radio" name="ap" value="2"><span>防</span></label>
					<label><input type="radio" name="ap" value="3"><span>敏</span></label>
					<label><input type="radio" name="ap" value="4"><span>魔</span></label>
					剩餘點數: <input type="number" name="rp" value="0" style="width:40px">
					<br>
					<label><input type="checkbox" name="use_level1" value="1">使用1級能力計算</label>: <input type="text" name="from_ability" size="40" placeholder="體力 魔力 攻擊 防禦 敏捷"><br>
					<input type="submit">
					<button class="bindPetEl bindPet-unlockBtn">離開鎖定</button>
			</form>
			<table id="caldata"></table>
		</div>
		</td>
	</tr>
</table>
<div class="head" style="margin-bottom:0px;border:0px none;margin-top:5px">寵物能力模擬(檔次)</div>
<div style="text-align: left;border: 1px solid #908860;padding: 7px;background-color: #F8F4E0;">
	<form id="simForm">
		等級: <input type="text" name="level" size="6" value="175-180">
		倍率: <input type="text" name="multiplier" size="3" value="20">
		檔次: <input type="text" name="grow" size="11"><input type="button" id="random_grow_btn" value="亂數">
		隨機檔: <input type="text" name="random" size="7"><input type="button" id="random_random_btn" value="亂數">
		<input type="submit">
		<br>
		升級加點:
				<label><input type="radio" name="ap" value="5" checked><span>無</span></label>
				<label><input type="radio" name="ap" value="0"><span>體</span></label>
				<label><input type="radio" name="ap" value="1"><span>力</span></label>
				<label><input type="radio" name="ap" value="2"><span>防</span></label>
				<label><input type="radio" name="ap" value="3"><span>敏</span></label>
				<label><input type="radio" name="ap" value="4"><span>魔</span></label>
		每<input type="number" name="am" value="1" size="1" min="0" max="1000" step="1">級加點
		<br>
		<label><input type="checkbox" name="useInt" checked>顯示整數</label>
		<label><input type="checkbox" name="showBA">顯示升級加點狀態</label>
	</form>
</div>
<div id="simResult" style="display:none">
	<table class="style">
		<tr>
			<td>基本成長: </td>
			<td id="simResult_dpGrow"></td>
			<td>BP: </td>
			<td id="simResult_ebGrow"></td>
		</tr>
		<tr>
			<td>綜合成長: </td>
			<td id="simResult_tdGrow"></td>
			<td>BP: </td>
			<td id="simResult_bdGrow"></td>
		</tr>
	</table>
	<table id="simData" class="style"></table>
</div>
<div class="head" style="margin-bottom:0px;border:0px none;margin-top:5px">寵物能力模擬(BP)</div>
<div style="text-align: left;border: 1px solid #908860;padding: 7px;background-color: #F8F4E0;">
	<form id="bpForm">
		BP: <input type="text" name="bp" size="21" placeholder="體力防敏魔"><input type="button" id="random_bp_btn" value="亂數">
		<input type="submit">
		<span id="bpResult">
			體:<span class="rs-0"></span>
			魔:<span class="rs-1"></span>
			攻:<span class="rs-2"></span>
			防:<span class="rs-3"></span>
			敏:<span class="rs-4"></span>
			精:<span class="rs-5"></span>
			回:<span class="rs-6"></span>
		</span>
	</form>
</div>
<div class="head" style="margin-bottom:0px;border:0px none;margin-top:5px">備註</div>
<div style="text-align: left;border: 1px solid #908860;padding: 7px;background-color: #F8F4E0;">
	弄此純為好玩(也研究一番)，要用專業的，請使用魔物觀測者
</div>
</body>
</html>