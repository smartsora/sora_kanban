<?php
/*
Plugin Name: Sora_看板娘
Plugin URI: #
Description: 小PinnPinn的看板娘
Version: 1.6
Author: Sora
Author URI: http://www.pinnpinn.com/sora/index.html
*/
error_reporting(E_ALL^E_NOTICE^E_WARNING);//隐藏错误提示
require_once('kanban.class.php');

function print_sora_kanban_style() {
	wp_enqueue_style('sora_kanban',plugins_url('',__FILE__).'/css/kanban.css',false,'1.0');
	wp_enqueue_style('sora_nitification',plugins_url('',__FILE__).'/css/nitification.css',false,'1.0');
	
}
add_action('wp_enqueue_scripts','print_sora_kanban_style');

function print_sora_kanban_js() {
	wp_enqueue_script('jquery_new',plugins_url('',__FILE__).'/js/jquery-3.3.1.min.js');
}
add_action('wp_enqueue_scripts','print_sora_kanban_js');

function create_sora_notification_data(){
	global $wpdb;
	$db_results = $wpdb->get_results('SELECT ID FROM `'.$wpdb->prefix.'posts` where post_status = "publish" order by ID desc limit 0,8');
	if($db_results==null){
		return false;
	}
	foreach($db_results as $db_result){
		$sora_notification_list_new_post_date_arr[]=$db_result->ID;
	}
	$sora_notification_list_new_post_date_arr_count=count($sora_notification_list_new_post_date_arr);
	unset($db_results,$db_result);
	
	//SELECT * FROM wp_sora_posts where post_status = "publish" order by post_modified desc limit 0,8;
	$db_results = $wpdb->get_results('SELECT ID FROM `'.$wpdb->prefix.'posts` where post_status = "publish" order by post_modified desc limit 0,18');
	if($db_results==null){
		return false;
	}
	foreach($db_results as $db_result){
		$sora_notification_list_post_updated_date_arr_raw[]=$db_result->ID;
	}
	$sora_notification_list_post_updated_date_arr_raw_count=count($sora_notification_list_post_updated_date_arr_raw);
	$i=0;
	$j=0;
	for($i=0;$i<$sora_notification_list_post_updated_date_arr_raw_count;$i++){
		if($j==8){
			break;
		}
		if(!in_array($sora_notification_list_new_post_date_arr[$i], $sora_notification_list_post_updated_date_arr_raw)){
			$sora_notification_list_post_updated_date_arr[]=$sora_notification_list_post_updated_date_arr_raw[$i];
			$j++;
		}
	}
	$sora_notification_list_post_updated_date_arr_count=count($sora_notification_list_post_updated_date_arr);
	unset($db_results,$db_result);
	$user_img_url=plugins_url('',__FILE__).'/img/user.png';
	$comment_img_url=plugins_url('',__FILE__).'/img/comment.png';
	$html_text_new_post_cache='';
	$html_text_post_updated_cache='';
	ob_start();
	ob_end_clean();
	for($i=0;$i<$sora_notification_list_new_post_date_arr_count;$i++){
		$post_cache=get_post($sora_notification_list_new_post_date_arr[$i],ARRAY_A);
		$post_title_cache=$post_cache['post_title'];
		$post_url_cache=$post_cache['guid'];
		$post_author_name_cache=get_the_author_meta('display_name',$post_cache['post_author']);
		$post_comment_count_cache=$post_cache['comment_count'];
		preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post_cache['post_content'],$matches);
		$post_first_img_cache=$matches [1][0];
		if(empty($post_first_img_cache)){
		$post_first_img_cache='';
		}

		$html_text_part1='<a href="'.$post_url_cache.'" target="_blank"><span class="sora-notification-index">'.($i+1).'</span>';
		$html_text_part2='<h3 class="sora-notification-h3">'.$post_title_cache.'</h3>';
		$html_text_part3='<div class="sora-notification-item-content" style="display:none"><img src="'.$post_first_img_cache.'" alt="" style="max-width: 80px; max-height: 63px;float: left;position: relative;border: 1px solid #d074ff;"><div class="sora-notification-content-wrapper"><p class="sora-notification-p">'.$post_title_cache.'</p><div class="sora-notification-scontent" style="width: 100%;"><span style="span: margin-top: 2px;margin-right: 2px;display: block;float: left;width: 12px;height: 12px;background: url('.$user_img_url.') no-repeat;margin-top: 2px;"></span><span style="float: left;font-size: 12px">'.$post_author_name_cache.'</span><span style="float: right;font-size: 12px">'.$post_comment_count_cache.'</span><span style="margin-top: 3px;display: block;float: right;width: 13px;height: 10px;background: url('.$comment_img_url.') no-repeat;right: 4px;position: relative;"></span></div></div></div>';
		$html_text_new_post_cache.='<li class="sora-notification-item">'.$html_text_part1.$html_text_part2.$html_text_part3.'</a></li>';
	}
	$post_cache='';
	$post_title_cache='';
	$post_url_cache='';
	$post_author_name_cache='';
	$post_comment_count_cache='';
	$matches='';
	$post_first_img_cache='';
	//unset($post_cache,$post_title_cache, $foo3);
	for($i=0;$i<$sora_notification_list_post_updated_date_arr_count;$i++){
		$post_cache=get_post($sora_notification_list_post_updated_date_arr[$i],ARRAY_A);
		$post_title_cache=$post_cache['post_title'];
		$post_url_cache=$post_cache['guid'];
		$post_author_name_cache=get_the_author_meta('display_name',$post_cache['post_author']);
		$post_comment_count_cache=$post_cache['comment_count'];
		preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post_cache['post_content'],$matches);
		$post_first_img_cache=$matches [1][0];
		if(empty($post_first_img_cache)){
		$post_first_img_cache='';
		}

		$html_text_part1='<a href="'.$post_url_cache.'" target="_blank"><span class="sora-notification-index">'.($i+1).'</span>';
		$html_text_part2='<h3 class="sora-notification-h3">'.$post_title_cache.'</h3>';
		$html_text_part3='<div class="sora-notification-item-content" style="display:none"><img src="'.$post_first_img_cache.'" alt="" style="max-width: 80px; max-height: 63px;float: left;position: relative;border: 1px solid #d074ff;"><div class="sora-notification-content-wrapper"><p class="sora-notification-p">'.$post_title_cache.'</p><div class="sora-notification-scontent" style="width: 100%;"><span style="span: margin-top: 2px;margin-right: 2px;display: block;float: left;width: 12px;height: 12px;background: url('.$user_img_url.') no-repeat;margin-top: 2px;"></span><span style="float: left;font-size: 12px">'.$post_author_name_cache.'</span><span style="float: right;font-size: 12px">'.$post_comment_count_cache.'</span><span style="margin-top: 3px;display: block;float: right;width: 13px;height: 10px;background: url('.$comment_img_url.') no-repeat;right: 4px;position: relative;"></span></div></div></div>';
		$html_text_post_updated_cache.='<li class="sora-notification-item">'.$html_text_part1.$html_text_part2.$html_text_part3.'</a></li>';
	}
	
	$result='<div id="sora-notification-board-main" class="sora-notification-board-main" style="display:none">
		<div id="sora-notification-close" class="sora-notification-close">
			
		</div>
		<div id="sira-notification-main" class="sora-notification-main sora-notification-background-img">
			<div id="sora-notification-menu-main" class="sora-notification-menu-main">
				<div id="sora-notification-menu" class="sora-notification-menu">
					<li class="sora-notification-menu-item">
						<div class="sora-notification-button1">
							<i class="fa fa-fw fa-gamepad"></i><span class="sora-notification-menu-span">最新发布</span>
						</div>
					</li>
				</div>
				<div id="sora-notification-menu" class="sora-notification-menu">
					<li class="sora-notification-menu-item">
						<div class="sora-notification-button2">
							<i class="fa fa-fw fa-gamepad"></i><span class="sora-notification-menu-span">最新更新</span>
						</div>
					</li>
				</div>
				<div id="sora-notification-menu" class="sora-notification-menu">
					<li class="sora-notification-menu-item">
						<div class="sora-notification-button3">
							<i class="fa fa-fw fa-gamepad"></i><span class="sora-notification-menu-span">小站公告</span>
						</div>
					</li>
				</div>
			</div>
			<div id="sora-notification-board" class="sora-notification-board">
				<ul id="notification-faith-sub-list" class="notification-faith-sub-list">
					'.$html_text_new_post_cache.'
				</ul>
				<ul id="notification-faith-sub-list" class="notification-faith-sub-list" style="display:none">
					'.$html_text_post_updated_cache.'
				</ul>
			</div>
		</div>
	</div>';
	return $result;
}

function create_sora_kanban_selector_data(){
	$url=home_url().'/';
	$img_url_base=plugins_url('',__FILE__).'/img/characters/';
	$characters=new kanban_characters();
	$name_arr=$characters->name;
	$name_arr_count=count($name_arr);
	$img_url_arr=$characters->img_url;
	$img_url_arr_count=count($img_url_arr);
	if($name_arr_count!=$img_url_arr_count){
		return false;
	}
	$i=0;
	$result_main_part='';
	for($i=0;$i<$name_arr_count;$i++){
		$result_main_part.='<li class="sora-kanban-selector-item" data-id="'.strval($i).'"><div class="sora-kanban-selector-item-content"><img src="'.$img_url_base.$img_url_arr[$i].'" class="sora-kanban-selector-img"><div class="sora-kanban-selector-content-wrapper">	<p class="sora-kanban-selector-p">'.$name_arr[$i].'</p><div class="sora-kanban-selector-buttons" style="width: 100%;"><button class="sora-kanban-select-button1">和她玩</button><button class="sora-kanban-select-button2">换衣服</button></div></div></div></li>';
	}
	$result='<div id="sora-kanban-selector-board-main" class="sora-kanban-selector-board-main" style="position: relative; left: 280px; bottom: 450px; display: none;"><div id="sora-kanban-selector-close" class="sora-kanban-selector-close"></div><div id="sira-kanban-selector-main" class="sora-kanban-selector-main sora-kanban-selector-background-img"><div id="sora-kanban-selector-board" class="sora-kanban-selector-board"><ul id="kanban-selector-faith-sub-list" class="kanban-selector-faith-sub-list">'.$result_main_part.'</ul></div></div></div>';
	return $result;
}

function add_sora_kanban_character(){
	$url=home_url().'/';
	$message_Path='/wp-content/plugins/sora_kanban/';
	$live2d_js_main_path=plugins_url('',__FILE__).'/js/live2d.js';
	$live2d_js_message_path=plugins_url('',__FILE__).'/js/message.js';
	$sora_notification_js_path=plugins_url('',__FILE__).'/js/sora-notification.js';
	$html_resule_notification=create_sora_notification_data();
	$html_resule_kanban_selector=create_sora_kanban_selector_data();
	echo <<<EOF
	<div id="sora-landlord" style="display:none;">
		<div class="sora-message" style="opacity:0"></div>
		<canvas id="sora-live2d" width="280" height="360" class="live2d"></canvas>
		<div class="sora-hide-button"><i class="fa fa-eye-slash" aria-hidden="true"></i>隐藏</div>
		<div class="sora-nightmode-button">
			<i class="fa fa-adjust" aria-hidden="true" style="float: left;position: relative;left: 4px;line-height: 20px;"></i>
			<p class="sora-nightmode-p">夜间模式</p>
		</div>
		<div class="sora-kanban-othermodel-button"><i class="fa fa-users" aria-hidden="true"></i>更多人物</div>
		<div class="sora-kanban-notification-button"><i class="fa fa-commenting-o" aria-hidden="true"></i>小站动态</div>
		$html_resule_notification
		$html_resule_kanban_selector
	</div>
	<script type="text/javascript">
		var message_Path = '$message_Path';
		var home_Path = '$url';
	</script>
	<script type="text/javascript" src="$live2d_js_main_path"></script>
	<script type="text/javascript" src="$live2d_js_message_path"></script>
	<script type="text/javascript" src="$sora_notification_js_path"></script>
EOF;
}
add_action('wp_footer','add_sora_kanban_character');
?>