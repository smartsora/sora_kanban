var model_arr=new Array();
model_arr[0]='Pio/default.model.json';
model_arr[1]='Tia/default.model.json';
model_arr[2]='sagiri/sagiri.model.json';
model_arr[3]='kesshouban/model.json';
model_arr[4]='histoire/model.json';
model_arr[5]='Terisa/model.json';
model_arr[6]='katou_01/katou_01.model.json';
model_arr[7]='Kobayaxi/Kobayaxi.model.json';
model_arr[8]='nep/model.json';
model_arr[9]='rem/rem.json';

var model_count=model_arr.length;


	
function renderTip(template, context) {
    var tokenReg = /(\\)?\{([^\{\}\\]+)(\\)?\}/g;
    return template.replace(tokenReg, function (word, slash1, token, slash2) {
        if (slash1 || slash2) {
            return word.replace('\\', '');
        }
        var variables = token.replace(/\s/g, '').split('.');
        var currentObject = context;
        var i, length, variable;
        for (i = 0, length = variables.length; i < length; ++i) {
            variable = variables[i];
            currentObject = currentObject[variable];
            if (currentObject === undefined || currentObject === null) return '';
        }
        return currentObject;
    });
}

String.prototype.renderTip = function (context) {
    return renderTip(this, context);
};

$(document).on('copy', function (){
    showMessage('主人都復制了些什麽呀，轉載要記得加上出處哦~~', 5000);
});

(function (){
    var text;
    if(document.referrer !== ''){
        var referrer = document.createElement('a');
        referrer.href = document.referrer;
        text = '歡迎來自 <span style="color:#0099cc;">' + referrer.hostname + '</span> 的主人φ(≧ω≦*)♪！';
        var domain = referrer.hostname.split('.')[1];
        if (domain == 'baidu') {
            text = '歡迎來自 百度搜索 的主人φ(≧ω≦*)♪！<br>欢迎访问<span style="color:#0099cc;">「 ' + document.title.split(' - ')[0] + ' 」</span>';
        }else if (domain == 'so') {
            text = '歡迎來自 360搜索 的主人φ(≧ω≦*)♪！<br>欢迎访问<span style="color:#0099cc;">「 ' + document.title.split(' - ')[0] + ' 」</span>';
        }else if (domain == 'google') {
            text = '歡迎來自 谷歌搜索 的主人φ(≧ω≦*)♪！<br>欢迎访问<span style="color:#0099cc;">「 ' + document.title.split(' - ')[0] + ' 」</span>';
        }
    }else {
        if (window.location.href == `${home_Path}`) { //主页URL判断，需要斜杠结尾
            var now = (new Date()).getHours();
            if (now > 23 || now <= 5) {
                text = '主人是夜貓子呀？這麽晚還不睡覺，明天起的來嘛？';
            } else if (now > 5 && now <= 7) {
                text = '早上好！壹日之計在於晨，美好的壹天就要開始了！';
            } else if (now > 7 && now <= 11) {
                text = '上午好主人！工作順利嘛，不要久坐，多起來走動走動哦！';
            } else if (now > 11 && now <= 14) {
                text = '中午了，工作了壹個上午，現在是午餐時間！';
            } else if (now > 14 && now <= 17) {
                text = '午後很容易犯困呢主人，今天的運動目標完成了嗎？';
            } else if (now > 17 && now <= 19) {
                text = '傍晚了！窗外夕陽的景色很美麗呢，最美不過夕陽紅~~';
            } else if (now > 19 && now <= 21) {
                text = '晚上好主人，今天過得怎麽樣？';
            } else if (now > 21 && now <= 23) {
                text = '已經這麽晚了呀，早點休息吧主人，晚安~~';
            } else {
                text = '主人主人~ 快來和我玩嘛！';
            }
        }else {
            text = '歡迎閱讀<span style="color:#0099cc;">「 ' + document.title.split(' - ')[0] + ' 」</span>';
        }
    }
    showMessage(text, 12000);
})();

window.setInterval(showHitokoto,30000);

function showHitokoto(){
    $.getJSON('https://v1.hitokoto.cn/',function(result){
        showMessage(result.hitokoto, 5000);
    });
}

function showMessage(text, timeout){
    if(Array.isArray(text)) text = text[Math.floor(Math.random() * text.length + 1)-1];
    //console.log('showMessage', text);
    $('.sora-message').stop();
    $('.sora-message').html(text).fadeTo(200, 1);
    if (timeout === null) timeout = 5000;
    hideMessage(timeout);
}

function hideMessage(timeout){
    $('.sora-message').stop().css('opacity',1);
    if (timeout === null) timeout = 5000;
    $('.sora-message').delay(timeout).fadeTo(200, 0);
}

function load_model(type,select_id){
	
	var model_id=get_cookie('model_id');
	if(model_id==''||model_id==undefined||model_id=='null'){
		model_id=0;
	}
	model_id=parseInt(model_id);
	if(select_id==''||select_id==0){
		select_id=model_id;
	}
	if(type==0){
		loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/'+model_arr[model_id%model_count]);
		set_cookie('model_id',model_id%model_count,30);
	}else if(type==1){
		loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/'+model_arr[select_id]);
		set_cookie('model_id',select_id,30);
	}
	else{
		loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/'+model_arr[(model_id+1)%model_count]);
		set_cookie('model_id',(model_id+1)%model_count,30);
	}
}

function load_model_path(json_path){

	if(json_path==''||json_path==undefined||json_path==null){
		return false;
	}
	loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/'+json_path);
}

function toggle_clothes(character_id){
	if(character_id==0||character_id==1){//判断人物有无其他衣服
		var clothes_id=get_cookie('clothes_id');
		if(clothes_id==''||clothes_id==undefined||clothes_id=='null'){
			clothes_id=0;
		}
		clothes_id=parseInt(clothes_id);
		if(character_id==0){
			if(clothes_id==0){
				loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/Pio/pajamas.model.json');
			}else if(clothes_id==1){
				loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/Pio/school.model.json');
			}else{
				loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/Pio/default.model.json');
			}
			set_cookie('clothes_id',(clothes_id+1)%3,30);
		}else if(character_id==1){
			if(clothes_id==0){
				loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/Tia/pajamas.model.json');
			}else if(clothes_id==1){
				loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/Tia/jersey.model.json');
			}else{
				loadlive2d('sora-live2d','http://'+document.domain+'/wp-content/plugins/sora_kanban/model/Tia/default.model.json');
			}
			set_cookie('clothes_id',(clothes_id+1)%3,30);
		}
	}else{
		showMessage('我没有其他衣服了哟~', 3000);
	}
}

function set_cookie(c_name,value,expiredays){
	var exdate=new Date()
	exdate.setDate(exdate.getDate()+expiredays)
	document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}

function get_cookie(c_name) {//获取cookies
	var c_start='';
	var c_end='';
	if (document.cookie.length>0) {
		c_start=document.cookie.indexOf(c_name + "=");
		if (c_start!=-1) { 
			c_start=c_start + c_name.length+1;
			c_end=document.cookie.indexOf(";",c_start);
			if (c_end==-1) {
				c_end=document.cookie.length;
			}
		return decodeURIComponent(document.cookie.substring(c_start,c_end));
		} 
	}
	return 'null';
}

function random_num(min_num,max_num){
	switch(arguments.length){
		case 1: 
			return parseInt(Math.random()*min_num+1,10);
		break;
		case 2:
			return parseInt(Math.random()*(max_num-min_num+1)+min_num,10);
		break;
			default:
				return 0;
			break;
	}
}

function initLive2d (){
	var body_width=$(document.body).width();
	var body_height=$(document.body).height();
	
	if(body_width>=1200){
		load_model(0,0);
		$('div#sora-landlord').show();
	}/*else(
		$('div#sora-landlord').remove();
	)*/
	
	$('.sora-nightmode-button').fadeOut(0).on('click', () => {
		var nightmode_mark = document.getElementById('sora_nightmode_mark');
		if (nightmode_mark == null){
			nightmode_mark = document.createElement('nightmode_mark');
			document.body.appendChild(nightmode_mark);
			nightmode_mark.id = 'sora_nightmode_mark';
			nightmode_mark.style = 'position:fixed; left:0; top:0; opacity:.4; width:100%; height:100%; background:#000; z-index:2000; pointer-events:none;';
			console.log('夜间模式开启');
			$('p.sora-nightmode-p').text('正常模式');
		}else{
			document.body.removeChild(nightmode_mark);
			console.log('夜间模式关闭');
			$('p.sora-nightmode-p').text('夜间模式');
		}
    })
	$('div.sora-kanban-notification-button').fadeOut(0).on('click', () => {
		$('ul.notification-faith-sub-list').find('li.sora-notification-item').eq(0).find('h3.sora-notification-h3').hide();
		$('ul.notification-faith-sub-list').find('li.sora-notification-item').eq(0).find('div.sora-notification-item-content').show();
		$('div.sora-notification-board-main').css({
			'position':'relative',
			'left':'280px',
			'bottom':'510px'
		})
		$('div.sora-kanban-selector-board-main').hide();
		$('div.sora-notification-board-main').toggle();
	})
    $('div.sora-hide-button').fadeOut(0).on('click', () => {
        $('#sora-landlord').css('display', 'none');
    })
	$('div.sora-kanban-othermodel-button').fadeOut(0).on('click', () => {//更多人物按钮
		//load_model(1);
		$('div.sora-notification-board-main').hide();
		$('div.sora-kanban-selector-board-main').toggle();
    })
	$('button.sora-kanban-select-button1').on('click',function() {//和她玩按钮
		var character_id=$(this).parent().parent().parent().parent().attr('data-id');
		load_model(1,parseInt(character_id));
	})
	$('button.sora-kanban-select-button2').on('click',function() {//换衣服按钮
		var character_id=$(this).parent().parent().parent().parent().attr('data-id');
		toggle_clothes(character_id);
	})
	
	$('#sora-live2d').on('click', () => {
		var text_arr=["不要動手動腳的！快把手拿開~~", "真…真的是不知羞恥！","Hentai！", "再摸的話我可要報警了！⌇●﹏●⌇", "110嗎，這裏有個變態壹直在摸我(ó﹏ò｡)"];
		showMessage(text_arr[random_num(0,4)], 5000);
    })
	
	/*****************************监控页面元素************************************/
	$('div#header-account-container').hover(() => {
		showMessage('登录后就能发现更多神奇的东西哟~', 5000);
	})
	
	$('div#search-bar-btn-container').hover(() => {
		showMessage('主人要搜索什么呀？', 5000);
	})
	$('div.textwidget.custom-html-widget').eq(0).hover(() => {
		showMessage('可怜可怜小站吧，施舍一点饭吃嘛~', 5000);
	})
	$('div.card-bg').hover(function(){
		var info=$(this).children('a.post-title').eq(0).attr('title');
		showMessage('要看看 '+info+' 吗？', 5000);
	},function(){
		hideMessage(50);
	})
	
    $('#sora-landlord').hover(() => {
		$('.sora-nightmode-button').fadeIn(600);
        $('.sora-hide-button').fadeIn(600);
		$('.sora-kanban-othermodel-button').fadeIn(600);
		$('.sora-kanban-notification-button').fadeIn(600);
    }, () => {
        $('.sora-nightmode-button').fadeOut(600);
		$('.sora-hide-button').fadeOut(600);
		$('.sora-kanban-othermodel-button').fadeOut(600);
		$('.sora-kanban-notification-button').fadeOut(600);
    })
}
initLive2d ();