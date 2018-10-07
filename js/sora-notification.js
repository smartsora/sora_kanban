$(document).ready(function(){
	$('div.sora-notification-menu').eq(0).click(function(){
		$(this).parent().parent().find('ul.faith-sub-list').eq(0).show();
		$(this).parent().parent().find('ul.faith-sub-list').eq(1).hide();
	})
	$('div.sora-notification-menu').eq(1).click(function(){
		$(this).parent().parent().find('ul.faith-sub-list').eq(0).hide();
		$(this).parent().parent().find('ul.faith-sub-list').eq(1).show();
	})
	$('div.sora-notification-menu').eq(2).click(function(){
		$(this).parent().parent().find('ul.faith-sub-list').eq(0).hide();
		$(this).parent().parent().find('ul.faith-sub-list').eq(1).hide();
	})
	$('div.sora-notification-board').on('mouseenter','h3.sora-notification-h3',function(e){
		$(document).find('h3.sora-notification-h3').show();
		$(this).parent().find('h3.sora-notification-h3').hide();
		$(document).find('div.sora-notification-item-content').hide();
		$(this).parent().find('div.sora-notification-item-content').show();
	})
})
function killErrors() { 
 return true; 
} 
//window.onerror = killErrors;

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
	return "null";
}