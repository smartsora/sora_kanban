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

var re = /x/;
console.log(re);
re.toString = function() {
    showMessage('哈哈，主人打開了控制臺，是想要看看我的秘密嗎？', 5000);
    return '';
};

$(document).on('copy', function (){
    showMessage('主人都復制了些什麽呀，轉載要記得加上出處哦~~', 5000);
});

function initTips(){
    $.ajax({
        cache: true,
        url: `${message_Path}message.json`,
        dataType: "json",
        success: function (result){
            $.each(result.mouseover, function (index, tips){
                $(tips.selector).mouseover(function (){
                    var text = tips.text;
                    if(Array.isArray(tips.text)) text = tips.text[Math.floor(Math.random() * tips.text.length + 1)-1];
                    text = text.renderTip({text: $(this).text()});
                    showMessage(text, 3000);
                });
            });
            $.each(result.click, function (index, tips){
                $(tips.selector).click(function (){
                    var text = tips.text;
                    if(Array.isArray(tips.text)) text = tips.text[Math.floor(Math.random() * tips.text.length + 1)-1];
                    text = text.renderTip({text: $(this).text()});
                    showMessage(text, 3000);
                });
            });
        }
    });
}
initTips();

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

function initLive2d (){
	var body_width=$(document.body).width();
	var body_height=$(document.body).height();
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
		/*var nightmode_key = jQuery.Event('keydown');
		nightmode_key.keyCode = 113;
		nightmode_key.which = 113;
		$(document).trigger(nightmode_key);*/
    })
	if(body_width>=1200){
		$('div.sora-kanban-notification-button').fadeOut(0).on('click', () => {
			$('ul.faith-sub-list').find('li.sora-notification-item').eq(0).find('h3.sora-notification-h3').hide();
			$('ul.faith-sub-list').find('li.sora-notification-item').eq(0).find('div.sora-notification-item-content').show();
			$('div.sora-notification-board-main').css({
					'position':'relative',
					'left':'300px',
					'bottom':'510px'
				})
			$('div.sora-notification-board-main').toggle();
		})
	}
    $('.sora-hide-button').fadeOut(0).on('click', () => {
        $('#sora-landlord').css('display', 'none');
    })
    $('#sora-landlord').hover(() => {
		$('.sora-nightmode-button').fadeIn(600);
        $('.sora-hide-button').fadeIn(600);
		if(body_width>=1200){
			$('.sora-kanban-notification-button').fadeIn(600);
		}
    }, () => {
        $('.sora-nightmode-button').fadeOut(600);
		$('.sora-hide-button').fadeOut(600);
		if(body_width>=1200){
			$('.sora-kanban-notification-button').fadeOut(600);
		}
    })
}
initLive2d ();
