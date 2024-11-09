$(function(){
    if (!window.location.origin) {
        window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
    }
    $.ajax({url:window.location.origin+'/mission/item',success:function(data){
        if( data == '' ) return;
        var boxpair = [];
        var itempair = {};
        $('<link>').attr({'href':window.location.origin+'/css/itembox.css','rel':'stylesheet','type':'text/css'}).appendTo('head');
        $.each($(data).insertAfter('table.mission').find('td:first-child').on({
            mouseenter: function(e){
                var pos= $(this).offset();
                var box = boxpair[$(this).data('id')].box;
                box.removeClass('flip').css({display:'block', left:pos.left+$(this).width()+50,top:pos.top+$(this).height()-box.height()});
            },mouseleave: function(e){
                boxpair[$(this).data('id')].box.css({display:'none'});
            },contextmenu: function(e){
                e.preventDefault();
                var box = boxpair[$(this).data('id')].box;
                if(box.hasClass('flip')) box.removeClass('flip'); else box.addClass('flip');
            }
        }), function(){
            var id = boxpair.length, name = $(this).find('.name').text(), img=$(this).find('img').eq(0);
            $(this).data('id', id);
            boxpair.push({id: id,name:name,img:img,box:$(this).find('.itembox')});
            itempair[name] = itempair[name] ? -1 : id;
        });
        var itemEffect = {
            mouseenter: function(e){
                if( $(this).data('id') == -1 ) return;
                var pos= $(this).offset();
                var box = boxpair[$(this).data('id')].box;
                box.removeClass('flip').css({display:'block', left:pos.left+$(this).width()+50,top:pos.top-box.height()});
            },mouseleave: function(e){
                if( $(this).data('id') == -1 ) return;
                boxpair[$(this).data('id')].box.css({display:'none'});
            },contextmenu: function(e){
                if( $(this).data('id') == -1 ) return;
                e.preventDefault();
                var box = boxpair[$(this).data('id')].box;
                if(box.hasClass('flip')) box.removeClass('flip'); else box.addClass('flip');
            }
        };
        $.each($('table.mission span.item'), function(){
            var text = $.trim($(this).text()), id = itempair[text];
            if( !id || id < 0 ) return;
            $(this).data('id', id).on(itemEffect);
			$(this).empty().append(boxpair[id].img.clone()).append($('<span>').text(text));
        });
    }});
	$.ajax({url:window.location.origin+'/mission/npc',dataType:'json',success:function(data){
		var npc = {};
		$.each(data, function(){npc[this.name+this.coor] = this;});
		$.each($('span.npc'), function(){
			var els = $(this).parent().contents();
			for(var i=0;i<els.length;i++) {
				if( els.get(i) == this ) {
					var text = $.trim($(this).text());
					var coor = els.eq(i+1).text().match(/^\(([0-9]{1,3}\.[0-9]{1,3})\)/);
					var selfNPC = coor ? npc[text+coor[1]] : false;
					if( selfNPC ) {
						var src = (selfNPC.image.match(/\.png$/)?'/image/npc/':'/image/anim/')+selfNPC.image;
						$('<img>').attr('src', window.location.origin+src).prependTo(this);
					}
					return;
				}
			}
		});
	}})
    
    $('#logspan').on('click', function(e){
        if( $('table.mission').hasClass('logspan') ) $('table.mission').removeClass('logspan');
        else $('table.mission').addClass('logspan');
    });
});