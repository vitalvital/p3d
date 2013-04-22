<?php
/*
	Plugin Name: 3D Model Viewer
	Plugin URI: http://fineonly.com/p3d
	Description: Easily display 3D models from http://p3d.in 
	Version: 0.8
	Author: Vital
	Author URI: http://fineonly.com
*/


add_action('wp_head', 'add_styles_js_GCF_meta');
add_action('admin_init', 'add_p3d_buttons');
add_action('admin_menu', 'p3d_add_pages');
add_filter('the_content', 'p3d');
add_filter('the_excerpt', 'p3d');
add_action('wp_ajax_p3d_tinymce', 'p3d_tinymce');
register_activation_hook(__FILE__, 'p3d_install');
add_action('wp_enqueue_scripts', 'insert_jquery');

function insert_jquery() {
    wp_enqueue_script( 'jquery' );
}

function add_styles_js_GCF_meta() {
	$options = get_option('p3d_options');
	echo '<meta http-equiv="X-UA-Compatible" content="chrome=1">
	<style>
	div.P3D-div {position: relative;z-index: 999999;}
	div.P3D-div div.maximize{position: absolute; right: 2px; top: 2px; z-index:100; font-weight: bold; z-index: 99999; cursor: pointer;}
	div.minimize {display: none; position: fixed; top: 2px; right: 2px; font-weight: bold; z-index: 99999; cursor: pointer;}
	div.minimize, div.P3D-div div.maximize {-moz-transition: background-color 150ms ease 0s, box-shadow 150ms ease 0s; background-color: rgba(0, 0, 0, 0.25); border-radius: 3px; box-shadow: 0 0 8px rgba(0, 0, 0, 0.6); padding: 1px 6px;}
	div.minimize:hover, div.P3D-div div.maximize:hover {color: white; background-color: rgba(0, 0, 0, 0.35); box-shadow: 0 0 5px rgba(0, 0, 0, 0.75);}
	div.minimize {background-color: rgba(200, 200, 200, 1) !important;}
	</style>
	<script type="text/javascript">
	function maximize(){
		jQuery("div.P3D-div").css({position: "fixed", background: "url('.plugin_dir_url(__FILE__).'/background.gif)"}).animate({top : 0, right: 0, width: jQuery(window).width(), height: jQuery(window).height()}, 1000,
			function(){
				jQuery("div.minimize").css({display: "block"});
				//jQuery("div.P3D-div big").css({background: "yellow", margin : "0 0 12px 0"});
				jQuery(this).css({width: "100%", height: "100%"});
				jQuery("div.P3D-div div.maximize").hide();
				}
	);
	}
	jQuery(document).ready(function() {
		jQuery("div.P3D-div div.maximize").click(maximize);
		jQuery("div.minimize").click(function(){
			jQuery("div.P3D-div").css({position: "relative", width: "100%", background: "none"}).animate({height: '.$options['height'].'}, 1000);
			jQuery("div.minimize").hide();
			jQuery("div.P3D-div div.maximize").show();
		});
	});
	</script>
	';
}

function p3d_install() {
	$newoptions = get_option('p3d_options');
	$newoptions['width'] = '100%';
	$newoptions['height'] = '600';
	$newoptions['spin'] = 'on'; 
	add_option('p3d_options', $newoptions);
}


function p3d_add_pages() {
	add_options_page('3D Options', '3D Options', 'manage_options', __FILE__, 'p3d_options');
}

function p3d_tinymce() {
	// check for rights
    if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
    	die(__("You are not allowed to do that"));
   	
   	require_once('tinymce/mce_p3d.php');
    
    die();
}

function p3d_embed($id) { 
	$options = get_option('p3d_options');
	$width = strrpos($options['width'], "%") == false ? $options['width']."px" : $options['width'];
	$height = strrpos($options['height'], "%") == false ? $options['height']."px" : $options['height'];
	return '<!--[if IE]>    <script type="text/javascript"      src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
    <style>
     .chromeFrameInstallDefaultStyle {
       width: 95%; /* default is 800px */
       border: 5px solid blue;
       position: absolute; left: 400px; z-index: 9999;
     }
    </style>

    <div id="prompt"></div>
     <script>
     // The conditional ensures that this code will only execute in IE,
     // Therefore we can use the IE-specific attachEvent without worry
     window.attachEvent("onload", function() {       CFInstall.check({         mode: "inline", // the default
         node: "prompt"       });
     });    </script>
  <![endif]-->
  
  <div class="P3D-div" style="width:'.$width.'; height:'.$height.'" align="center">
  <div class="maximize" title="Maximize">Maximize</div><div class="minimize" title="Minimize">Minimize</div>
	<iframe src="http://p3d.in/e/'.$id.$options['spin'].'/" frameborder="0" height="100%" width="100%" allowtransparency="true"></iframe>
	</div>';
}


function p3d($content){
	$options = get_option('p3d_options');

	$search = "@\s*\[p3d\]\s*http:\/\/(|www.)p3d.in\/([^\/^\[]+)(.*)\s*\[/p3d\]\s*@i";
	if(preg_match_all($search, $content, $matches)) {
		if(is_array($matches)) {
			foreach($matches[1] as $key =>$id) {
				// Get the data from the tag
				$id = $matches[2][$key];
				$search = $matches[0][$key];
				
				$replace = p3d_embed($id);
				$content = str_replace ($search, $replace, $content);
			}
		}
	}
		
	return $content;
}

function p3d_options() {
	global $table_prefix, $wpdb;
	// get options
	$options = $newoptions = get_option('p3d_options');
	// if submitted, process results
	if ( isset($_POST["p3d_submit"]) && $_POST["p3d_submit"]) {
		$newoptions['width'] = strip_tags(stripslashes($_POST["width"]));
		$newoptions['height'] = strip_tags(stripslashes($_POST["height"]));
		if ($_POST["spin"]=="on") {
			$newoptions['spin'] = "/spin";
		} else {
			$newoptions['spin'] = "";
		}
	}
	// any changes? save!
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('p3d_options', $options);
	}
	// check if installed (hook is not called if used as mu-plugin)
	$wtemp = get_option('p3d_width');
	if( empty($wtemp) ){ p3d_install(); }
	// if options form was sent, process those...
	if( isset($_GET['action']) && $_GET['action'] == "updateoptions" ){
		update_option('p3d_width', $_POST['p3d_width']);
		update_option('p3d_height', $_POST['p3d_height']);
		update_option('p3d_spin', $_POST['p3d_spin']);
	}
	// options form
	echo'<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=p3d/p3d.php">';
	echo '<div class="wrap"><p><h2>3D Object Display Options</h2><p>';
	// settings
	echo '<table class="form-table">';
	// width
	echo '<tr valign="top"><th scope="row">Width</th>';
	echo '<td><input type="text" name="width" value="'.$options['width'].'" size="5"></input> &nbsp; Width in pixels. Use \'%\' only when specifying width in percents. (Default is 100%)</td></tr>';
	// height
	echo '<tr valign="top"><th scope="row">Height</th>';
	echo '<td><input type="text" name="height" value="'.$options['height'].'" size="5"></input> &nbsp; (Default is 600px)</td></tr>';
	// spin
	echo '<tr valign="top"><th scope="row">Spin</th>';
	if (isset($options['spin']) && !empty($options['spin'])) {
		$checked = " checked=\"checked\"";
	} else {
		$checked = "";
	}
	echo '<td><input type="checkbox" name="spin" value="on"'.$checked.' /> &nbsp; Spin 3D object</td></tr>';
	echo '</table>';
	echo '<input type="hidden" name="p3d_submit" value="true"></input>';
	echo '<p class="submit"><input type="submit" value="Update Options &raquo;"></input></p>';
	echo '</form>';
	echo "</div>";
	echo '<div class="wrap"><p><h2>Using P3D</h2><p>To embed a 3D object, follow these steps:<ul><li>Find/upload the 3D object on http://p3d.in.</li><li>Copy the 3D object page url. These look something like: <i>http://p3d.in/DtaO3</i> <li>Type <strong>[p3d]http://p3d.in/DtaO3[/p3d]</strong> anywhere in your post.</li><li>Save and publish the post.</li></ul>';
	echo '</p><p>';
	
	$dir_name = '/wp-content/plugins/p3d';
	$url = get_bloginfo('wpurl');
	$iconURL = $url.$dir_name.'/tinymce/p3d.gif';
	
	printf('You can also use the button %s in the Post/Page Editor. Just paste in the URL of the 3D object.', 'p3d', "<img src='".$iconURL."'>");
	echo '</p></div>';
}

function add_p3d_buttons() {
	if( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;
	if( get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'add_pthreed_script');
		add_filter('mce_buttons', 'add_p3d_button');
	}
}

function add_p3d_button($buttons) {
	array_push($buttons, 'p3d');
	return $buttons;
}

function add_pthreed_script($plugins) {
	$dir_name = '/wp-content/plugins/p3d';
	$url = get_bloginfo('wpurl');
	$pluginURL = $url.$dir_name.'/tinymce/editor_plugin.js';
	$plugins['p3d'] = $pluginURL;
	return $plugins;
}

?>
