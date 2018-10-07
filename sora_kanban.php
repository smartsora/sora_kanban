<?php
/*
Plugin Name: Sora_看板娘
Plugin URI: #
Description: 小PinnPinn的看板娘
Version: 1.2
Author: Sora
Author URI: http://www.pinnpinn.com/sora/index.html
*/
error_reporting(E_ALL^E_NOTICE^E_WARNING);//隐藏错误提示
function print_sora_kanban_style() {
	wp_enqueue_style('sora_kanban',plugins_url('',__FILE__).'/css/kanban.css',false,'1.0');
	wp_enqueue_style('sora_nitification',plugins_url('',__FILE__).'/css/nitification.css',false,'1.0');
	
}
add_action('wp_enqueue_scripts','print_sora_kanban_style');

function print_sora_kanban_js() {
	wp_enqueue_script('jquery_new',plugins_url('',__FILE__).'/js/jquery-3.3.1.min.js');
}
add_action('wp_enqueue_scripts','print_sora_kanban_js');

function update_sora_notification_list_add_data($new_status,$old_status,$post){//最新发布文章
	if($post==null){
		return false;
	}
	global $wpdb;
	/*$var = $wpdb->get_var('SELECT ID FROM `'.$wpdb->prefix.'posts` WHERE ID='.$post_ID);
	if($var!=null){
		return false;
	}*/
	$post_ID=strval($post->ID);
	if($new_status=='publish'&&($old_status=='new'||$old_status=='draft'||$old_status=='pending'||$old_status=='auto-draft')){//发布文章
		$sora_notification_list_new_post_number=get_option('sora_notification_list_new_post_number');//为空返回'error'，不存在返回false
		if($sora_notification_list_new_post_number==''||$sora_notification_list_new_post_number=='8'){
			$sora_notification_list_new_post_number=0;
		}elseif($sora_notification_list_new_post_number=='error'||$sora_notification_list_new_post_number==false){//不存在设置项
			$table=$wpdb->prefix.'options';
			$data_arr=array(
			'sora_notification_list_new_post_number'=>'0'
			);
			$wpdb->insert($table,$data_arr);
			$sora_notification_list_new_post_number=0;
		}else{
			$sora_notification_list_new_post_number=intval($sora_notification_list_new_post_number)%8;
		}
		$sora_notification_list_new_post_date_arr=get_option('sora_notification_list_new_post_date');
		if($sora_notification_list_new_post_date_arr==''){//初次写库
			$sora_notification_list_new_post_date_arr=array($post_ID);
		}elseif($sora_notification_list_new_post_date_arr=='error'||$sora_notification_list_new_post_date_arr==false){
			$table=$wpdb->prefix.'options';
			$data_arr=array(
			'sora_notification_list_new_post_date'=>'0'
			);
			$wpdb->insert($table,$data_arr);
			$sora_notification_list_new_post_date_arr=array($post_ID);
		}else{
			$sora_notification_list_new_post_date_arr_count=count($sora_notification_list_new_post_date_arr);
			if(($sora_notification_list_new_post_number+1)!=$sora_notification_list_new_post_date_arr_count){
				$sora_notification_list_new_post_number=$sora_notification_list_new_post_date_arr_count%8;
			}
			if($sora_notification_list_new_post_date_arr_count<8){
				$sora_notification_list_new_post_date_arr[]=$post_ID;//添加最新更新列表数组
			}else{
				$sora_notification_list_new_post_date_arr[$sora_notification_list_post_updated_number]=$post_ID;//重组最新更新列表数组
			}
		}
		update_option('sora_notification_list_new_post_number',strval($sora_notification_list_new_post_number+1));//写入下一次发布文章的编号
		update_option('sora_notification_list_new_post_date',$sora_notification_list_new_post_date_arr);//写入最新发布文章
	}elseif($new_status=='publish'&&$old_status=='publish'){//更新文章
		$sora_notification_list_post_updated_number=get_option('sora_notification_list_post_updated_number');//为空返回'error'，不存在返回false
		if($sora_notification_list_post_updated_number==''||$sora_notification_list_post_updated_number=='8'){
			$sora_notification_list_post_updated_number=0;
		}elseif($sora_notification_list_post_updated_number=='error'||$sora_notification_list_post_updated_number==false){//不存在设置项
			$table=$wpdb->prefix.'options';
			$data_arr=array(
			'sora_notification_list_post_updated_number'=>'0'
			);
			$wpdb->insert($table,$data_arr);
			$sora_notification_list_post_updated_number=0;
		}else{
			$sora_notification_list_post_updated_number=intval($sora_notification_list_post_updated_number)%8;
		}
		$sora_notification_list_post_updated_date_arr=get_option('sora_notification_list_post_updated_date');
		if($sora_notification_list_post_updated_date_arr==''){//初次写库
			$sora_notification_list_post_updated_date_arr=array($post_ID);
			$sora_notification_list_post_updated_number_save=1;//下次更新文章编号+1
		}elseif($sora_notification_list_post_updated_date_arr=='error'||$sora_notification_list_post_updated_date_arr==false){
			$table=$wpdb->prefix.'options';
			$data_arr=array(
			'sora_notification_list_post_updated_date'=>''
			);
			$wpdb->insert($table,$data_arr);
			$sora_notification_list_post_updated_date_arr=array($post_ID);
		}else{
			$sora_notification_list_post_updated_date_arr_count=count($sora_notification_list_post_updated_date_arr);
			if(($sora_notification_list_post_updated_number+1)!=$sora_notification_list_post_updated_date_arr_count){
				$sora_notification_list_post_updated_number=$sora_notification_list_post_updated_date_arr_count%8;
			}
			if($sora_notification_list_post_updated_date_arr_count<8){
				if(in_array($post_ID,$sora_notification_list_post_updated_date_arr)){
					$post_ID_position_cache=array_search($post_ID,$sora_notification_list_post_updated_date_arr);//返回已存在的ID在数组中的位置
					$i=0;
					for($i=$post_ID_position_cache;$i<($sora_notification_list_post_updated_number-1);$i++){
						$sora_notification_list_post_updated_date_arr[$i]=$sora_notification_list_post_updated_date_arr[$i+1];//将重复ID之后的所有ID前移一位
					}
					$sora_notification_list_post_updated_date_arr[$sora_notification_list_post_updated_number-1]=$post_ID;//将重复ID写入最后位置
					$sora_notification_list_post_updated_number_save=$sora_notification_list_post_updated_number;//下次更新文章编号不变
				}else{
					$sora_notification_list_post_updated_date_arr[]=$post_ID;//添加最新更新列表数组
					$sora_notification_list_post_updated_number_save=$sora_notification_list_post_updated_number+1;//下次更新文章编号+1
				}
			}else{
				$sora_notification_list_post_updated_date_arr[$sora_notification_list_post_updated_number]=$post_ID;//重组最新更新列表数组
				$sora_notification_list_post_updated_number_save=$sora_notification_list_post_updated_number+1;//下次更新文章编号+1
			}
		}
		update_option('sora_notification_list_post_updated_number',strval($sora_notification_list_post_updated_number_save));//写入下一次更新文章的编号
		update_option('sora_notification_list_post_updated_date',$sora_notification_list_post_updated_date_arr);//写入最新更新文章
	}else{
		return false;
	}
}
add_action('transition_post_status','update_sora_notification_list_add_data',10,3);


function update_sora_notification_list_post_updated($post_ID){//最新更新文章
	if($post_ID==0||$post_ID==''){
		return false;
	}
	global $wpdb;
	$post_ID=strval($post_ID);
	$sora_notification_list_post_updated_number=get_option('sora_notification_list_post_updated_number');//为空返回'error'，不存在返回false
	if($sora_notification_list_post_updated_number==''||$sora_notification_list_post_updated_number=='8'){
		$sora_notification_list_post_updated_number=0;
	}elseif($sora_notification_list_post_updated_number=='error'||$sora_notification_list_post_updated_number==false){//不存在设置项
		$table=$wpdb->prefix.'options';
		$data_arr=array(
		'sora_notification_list_post_updated_number'=>'0'
		);
		$wpdb->insert($table,$data_arr);
		$sora_notification_list_post_updated_number=0;
	}else{
		$sora_notification_list_post_updated_number=intval($sora_notification_list_post_updated_number)%8;
	}
	$sora_notification_list_post_updated_date_arr=get_option('sora_notification_list_post_updated_date');
	if($sora_notification_list_post_updated_date_arr==''){//初次写库
		$sora_notification_list_post_updated_date_arr=array($post_ID);
		$sora_notification_list_post_updated_number_save=1;//下次更新文章编号+1
	}elseif($sora_notification_list_post_updated_date_arr=='error'||$sora_notification_list_post_updated_date_arr==false){
		$table=$wpdb->prefix.'options';
		$data_arr=array(
		'sora_notification_list_post_updated_date'=>''
		);
		$wpdb->insert($table,$data_arr);
		$sora_notification_list_post_updated_date_arr=array($post_ID);
	}else{
		$sora_notification_list_post_updated_date_arr_count=count($sora_notification_list_post_updated_date_arr);
		if(($sora_notification_list_post_updated_number+1)!=$sora_notification_list_post_updated_date_arr_count){
			$sora_notification_list_post_updated_number=$sora_notification_list_post_updated_date_arr_count%8;
		}
		if($sora_notification_list_post_updated_date_arr_count<8){
			if(in_array($post_ID,$sora_notification_list_post_updated_date_arr)){
				$post_ID_position_cache=array_search($post_ID,$sora_notification_list_post_updated_date_arr);//返回已存在的ID在数组中的位置
				$i=0;
				for($i=$post_ID_position_cache;$i<($sora_notification_list_post_updated_number-1);$i++){
					$sora_notification_list_post_updated_date_arr[$i]=$sora_notification_list_post_updated_date_arr[$i+1];//将重复ID之后的所有ID前移一位
				}
				$sora_notification_list_post_updated_date_arr[$sora_notification_list_post_updated_number-1]=$post_ID;//将重复ID写入最后位置
				$sora_notification_list_post_updated_number_save=$sora_notification_list_post_updated_number;//下次更新文章编号不变
			}else{
				$sora_notification_list_post_updated_date_arr[]=$post_ID;//添加最新更新列表数组
				$sora_notification_list_post_updated_number_save=$sora_notification_list_post_updated_number+1;//下次更新文章编号+1
			}
		}else{
			$sora_notification_list_post_updated_date_arr[$sora_notification_list_post_updated_number]=$post_ID;//重组最新更新列表数组
			$sora_notification_list_post_updated_number_save=$sora_notification_list_post_updated_number+1;//下次更新文章编号+1
		}
	}
	update_option('sora_notification_list_post_updated_number',strval($sora_notification_list_post_updated_number_save));//写入下一次更新文章的编号
	update_option('sora_notification_list_post_updated_date',$sora_notification_list_post_updated_date_arr);//写入最新更新文章
}
//add_action('post_updated','update_sora_notification_list_post_updated');


function create_sora_notification_data(){
	$sora_notification_list_new_post_date_arr=get_option('sora_notification_list_new_post_date');
	if($sora_notification_list_new_post_date_arr==''||$sora_notification_list_new_post_date_arr=='error'||$sora_notification_list_new_post_date_arr==false){//内容被手动清空
		$result='null';
	}
	$sora_notification_list_new_post_date_arr_count=count($sora_notification_list_new_post_date_arr);
	$sora_notification_list_post_updated_date_arr=get_option('sora_notification_list_post_updated_date');
	if($sora_notification_list_post_updated_date_arr==''||$sora_notification_list_post_updated_date_arr=='error'||$sora_notification_list_post_updated_date_arr==false){//内容被手动清空
		$result='null';
	}
	$sora_notification_list_post_updated_date_arr_count=count($sora_notification_list_post_updated_date_arr);
	$i=0;
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
				<ul id="faith-sub-list" class="faith-sub-list">
					'.$html_text_new_post_cache.'
				</ul>
				<ul id="faith-sub-list" class="faith-sub-list" style="display:none">
					'.$html_text_post_updated_cache.'
				</ul>
			</div>
		</div>
	</div>';
	return $result;
}

function add_sora_kanban_character(){
	$url=home_url().'/';
	$message_Path='/wp-content/plugins/sora_live2d/';
	$live2d_js_main_path=plugins_url('',__FILE__).'/js/live2d.js';
	$live2d_js_message_path=plugins_url('',__FILE__).'/js/message.js';
	$live2d_json_model_path=plugins_url('',__FILE__).'/model/Terisa/model.json';
	$sora_notification_js_path=plugins_url('',__FILE__).'/js/sora-notification.js';
	$html_resule=create_sora_notification_data();
	echo <<<EOF
	<div id="sora-landlord">
		<div class="sora-message" style="opacity:0"></div>
		<canvas id="sora-live2d" width="280" height="360" class="live2d"></canvas>
		<div class="sora-hide-button"><i class="fa fa-eye-slash" aria-hidden="true"></i>隐藏</div>
		<div class="sora-nightmode-button">
			<i class="fa fa-adjust" aria-hidden="true" style="float: left;position: relative;left: 4px;line-height: 20px;"></i>
			<p class="sora-nightmode-p">夜间模式</p>
		</div>
		<div class="sora-kanban-notification-button"><i class="fa fa-commenting-o" aria-hidden="true"></i>小站动态</div>
		$html_resule
	</div>
	<script type="text/javascript">
		var message_Path = '$message_Path';
		var home_Path = '$url';  //此处为你的域名，必须带斜杠
	</script>
	<script type="text/javascript" src="$live2d_js_main_path"></script>
	<script type="text/javascript" src="$live2d_js_message_path"></script>
	<script type="text/javascript" src="$sora_notification_js_path"></script>
	<script type="text/javascript">
		loadlive2d("sora-live2d", "$live2d_json_model_path");
	</script>
EOF;
}
add_action('wp_footer','add_sora_kanban_character');
?>