<?php
/**
 * @package Get Data
 * @version 1.0
 */
/*
 Plugin Name: Get Data
 Plugin URI: http://www.edc.org.kw
 Description: By using Get Data Plugin, you can get content by URL and insert data in header or footer
 Version: 1.0
 Author: EDC Team
 Author URI: http://www.edc.org.kw
 License: It is Free -_-
*/

function getting_data_plugin_install(){
	add_option( 'get_data_view_header', 1, '', 'yes' ); 
    add_option( 'get_data_link_header', '', '', 'yes' ); 
    add_option( 'get_data_view_footer', 1, '', 'yes' ); 
    add_option( 'get_data_link_footer', '', '', 'yes' ); 
    add_option( 'start_element_header', '', '', 'yes' ); 
    add_option( 'end_element_header', '', '', 'yes' ); 
    add_option( 'start_element_footer', '', '', 'yes' ); 
    add_option( 'end_element_footer', '', '', 'yes' ); 
}
register_activation_hook(__FILE__,'getting_data_plugin_install'); 

function getting_data_adminHeader() {
	echo "<style type=\"text/css\" media=\"screen\">\n";
	echo "#gettingdata { margin:0 0 20px 0; border:1px solid #cccccc; padding:5px; background-color:#fff; }\n";
	echo "#gettingdata input { padding:7px; margin:0 0 7px 0; }\n";
	echo ".content_header { padding:10px; margin:0 0 20px 0; border:1px solid #cccccc; padding:5px; background-color:#f2f2f2; }\n";
	do_action('getting_data_css');
	echo "</style>\n";
}

add_action('admin_head','getting_data_adminHeader');

function getting_data_words($k=''){

if ( get_option( 'WPLANG' ) == 'ar'){
$word['title'] = 'جلب البيانات';
$word['start_header'] = 'بداية الكود, تستطيع استخدام العناصر h1,h2,div,p';
$word['end_header'] = 'نهاية العنصر';
$word['start_footer'] = 'بداية الكود, تستطيع استخدام العناصر h1,h2,div,p';
$word['end_footer'] = 'نهاية العنصر في الفوتر';
$word['header'] = 'رابط الموقع الذي يتم منه جلب البيانات في الهيدر';
$word['footer'] = 'رابط الموقع الذي يتم منه جلب البيانات في الفوتر';
$word['show_title_footer'] = 'مشاهدة البيانات في الفوتر';
$word['show_title_header'] = 'مشاهدة البيانات في الهيدر';
$word['update_options'] = 'تحديث';
$word['insertcode'] = 'أدرج الكود التالي في الملف header.php <?php if ( function_exists(\'getting_data\') ){ echo getting_data("header"); } ?><br />وأيضا أدرج الكود التالي في الملف footer.php <?php if ( function_exists(\'getting_data\') ){ echo getting_data("footer"); } ?>';
}else{
$word['title'] = 'Get data';
$word['start_header'] = 'Start element, you can using h1,h2,div,p';
$word['end_header'] = 'End element';
$word['start_footer'] = 'Start element in footer, you can using h1,h2,div,p';
$word['end_footer'] = 'End element in footer';
$word['header'] = 'Header link';
$word['footer'] = 'Footer link';
$word['show_title_footer'] = 'Show data in footer';
$word['show_title_header'] = 'Show data in header';
$word['update_options'] = 'Update options';
$word['insertcode'] = 'Insert code in header.php &lt;?php if ( function_exists(\'getting_data\') ){ echo getting_data("header"); } ?&gt;<br />and insert code in footer.php &lt;?php if ( function_exists(\'getting_data\') ){ echo getting_data("footer"); } ?&gt;';
}
return $word[$k];
}

function getting_data($palce="header", $type=0){
global $post;
$start_element_header = stripslashes(get_option('start_element_header'));
$end_element_header = stripslashes(get_option('end_element_header'));
$start_element_footer = stripslashes(get_option('start_element_footer'));
$end_element_footer = stripslashes(get_option('end_element_footer'));

$code = '';
if( function_exists('file_get_contents') ){ 
	if($palce == "header"){
		if(get_option('get_data_view_header') == 1){
			if(get_option('get_data_link_header') == ""){
				$code .= '';
			}else{
				if($type == 1){
					$code .= '<div style="padding:0 0 10px 0;">Header<br />'.strip_tags(get_option('get_data_link_header')).'</div>';
				}else{
					$code .= '';
				}
				$code .= $start_element_header;
				$code .= file_get_contents(strip_tags(get_option('get_data_link_header')));
				$code .= $end_element_header;
			}
		}else{
			$code .= '';
		}
	}else{
		if(get_option('get_data_view_footer') == 1){
			if(get_option('get_data_link_footer') == ""){
				$code .= '';
			}else{
				if($type == 1){
					$code .= '<div style="padding:0 0 10px 0;">Footer<br />'.strip_tags(get_option('get_data_link_footer')).'</div>';
				}else{
					$code .= '';
				}
				$code .= $start_element_footer;
				$code .= file_get_contents(strip_tags(get_option('get_data_link_footer')));
				$code .= $end_element_footer;
			}
		}else{
			$code .= '';
		}
	}
}else{
	$code = '<div style="padding:7px 0 7px 0;">Function file_get_contents is not allow!</div>';
}
return $code;
} 
add_action( 'get_header', 'getting_data' );
add_action( 'get_footer', 'getting_data' );

add_action( 'admin_menu', 'getting_data_plugin_menu' );

function getting_data_plugin_menu() {
	add_menu_page( ''.getting_data_words('title').'', ''.getting_data_words('title').'', 'manage_options', 'data-edit', 'getting_data_options', ''.trailingslashit(plugins_url(null,__FILE__)).'/i/data.png' );
}

function getting_data_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
if(isset($_POST['get_data_view_header'])){ $get_data_view_header = '1'; }else{ $get_data_view_header = '0'; }
if(isset($_POST['get_data_view_footer'])){ $get_data_view_footer = '1'; }else{ $get_data_view_footer = '0'; }

if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
	if ( get_option( 'get_data_view_header' ) !== false ) {
		update_option( 'get_data_view_header', $get_data_view_header );
		update_option( 'get_data_view_footer', $get_data_view_footer );
		update_option( 'get_data_link_header', addslashes($_POST['get_data_link_header']) );
		update_option( 'get_data_link_footer', addslashes($_POST['get_data_link_footer']) );
		update_option( 'start_element_header', $_POST['start_element_header'] );
		update_option( 'end_element_header', $_POST['end_element_header'] );
		update_option( 'start_element_footer', $_POST['start_element_footer'] );
		update_option( 'end_element_footer', $_POST['end_element_footer'] );
	} else {
		add_option( 'get_data_view_header', 1, null );
		add_option( 'get_data_view_footer', 1, null );
		add_option( 'get_data_link_header', '', null );
		add_option( 'get_data_link_footer', '', null );
		add_option( 'start_element_header', '', null );
		add_option( 'end_element_header', '', null );
		add_option( 'start_element_footer', '', null );
		add_option( 'end_element_footer', '', null );
	}
}

$get_data_link_header = strip_tags(get_option('get_data_link_header'));
$get_data_link_footer = strip_tags(get_option('get_data_link_footer'));
$start_element_header = stripslashes(get_option('start_element_header'));
$end_element_header = stripslashes(get_option('end_element_header'));
$start_element_footer = stripslashes(get_option('start_element_footer'));
$end_element_footer = stripslashes(get_option('end_element_footer'));

if(get_option('get_data_view_header') == '1'){ $get_data_view_headerc = 'checked="checked"'; }else{ $get_data_view_headerc = ''; }
if(get_option('get_data_view_footer') == '1'){ $get_data_view_footerc = 'checked="checked"'; }else{ $get_data_view_footerc = ''; }
?>
	<div id="gettingdata" class="submit">
			<div class="dbx-content">				
				<h2><?php echo getting_data_words('title'); ?></h2>
				<br />
	<?php if( get_option('get_data_view_header') == 1 && $get_data_link_header != ""){ ?>
	<div class="content_header"><?php echo getting_data("header", 1); ?></div>
	<?php } ?>
	<?php if( get_option('get_data_view_footer') == 1 && $get_data_link_footer != ""){ ?>
	<div class="content_header"><?php echo getting_data("footer", 1); ?></div>
	<?php } ?>
	
				<form name="sytform" action="" method="post">
					<input type="hidden" name="submitted" value="1" />

					<div>
						<input style="width:70%;" id="get_data_link_header" type="text" name="get_data_link_header" value="<?php echo htmlentities($get_data_link_header); ?>" />
						<label for="get_data_link_header"><?php echo getting_data_words('header'); ?></label>
					</div>

					<div>
						<input style="width:70%;" id="get_data_link_footer" type="text" name="get_data_link_footer" value="<?php echo htmlentities($get_data_link_footer); ?>" />
						<label for="get_data_link_footer"><?php echo getting_data_words('footer'); ?></label>
					</div>
						
					<div>
						<input id="get_data_view_header" type="checkbox" name="get_data_view_header" <?php echo $get_data_view_headerc; ?> />
						<label for="get_data_view_header"><?php echo getting_data_words('show_title_header'); ?></label>
					</div>
					
					<div>
						<input id="get_data_view_footer" type="checkbox" name="get_data_view_footer" <?php echo $get_data_view_footerc; ?> />
						<label for="get_data_view_footer"><?php echo getting_data_words('show_title_footer'); ?></label>
					</div>
						
					<div>
						<input style="width:70%;" id="start_element_header" type="text" name="start_element_header" value="<?php echo htmlentities($start_element_header); ?>" />
						<label for="start_element_header"><?php echo getting_data_words('start_header'); ?></label>
					</div>
					
					<div>
						<input style="width:70%;" id="end_element_header" type="text" name="end_element_header" value="<?php echo htmlentities($end_element_header); ?>" />
						<label for="end_element_header"><?php echo getting_data_words('end_header'); ?></label>
					</div>
					
					<div>
						<input style="width:70%;" id="start_element_footer" type="text" name="start_element_footer" value="<?php echo htmlentities($start_element_footer); ?>" />
						<label for="start_element_footer"><?php echo getting_data_words('start_footer'); ?></label>
					</div>
					
					<div>
						<input style="width:70%;" id="end_element_footer" type="text" name="end_element_footer" value="<?php echo htmlentities($end_element_footer); ?>" />
						<label for="end_element_footer"><?php echo getting_data_words('end_footer'); ?></label>
					</div>
					
					<div style="padding: 1.5em 0;margin: 5px 0;">
						<input type="submit" name="Submit" value="<?php echo getting_data_words('update_options'); ?>" />
					</div>
				</form>
			</div>   
					
			<div style="border:#666 solid 1px; background-color:#333; color:#fff; padding: 7px; margin: 5px 0;"><?php echo getting_data_words('insertcode'); ?></div>	
						
						
		</div>
<?php
}
