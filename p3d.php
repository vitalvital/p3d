<?php
/*
	Plugin Name: 3D Model Viewer
	Plugin URI: http://fineonly.com/p3d
	Description: Easily display 3D models from http://p3d.in 
	Version: 0.8
	Author: Vital
	Author URI: http://fineonly.com
*/


add_action( 'wp_head', 'add_styles_js_GCF_meta' );
add_action( 'admin_init', 'add_p3d_buttons' );
add_shortcode( 'p3d', 'insert_p3d_object' );
add_action( 'wp_ajax_p3d_tinymce', 'p3d_tinymce' );

function add_styles_js_GCF_meta() {
	echo '<meta http-equiv="X-UA-Compatible" content="chrome=1">';
}

function p3d_tinymce() {
	// check for rights
    if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
    	die(__("You are not allowed to do that"));
   	
   	require_once('tinymce/mce_p3d.php');
    
    die();
}

function insert_p3d_object( $atts, $content = '' ) {
    extract( shortcode_atts( array(
	'url' => strpos($content, 'in/e/') ? $content : str_replace('p3d.in/', 'p3d.in/e/',$content),
	'width' => '100%',
	'height' => '400px',
	), $atts ) );
	
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
	  
	  <div class="P3D-div" style="z-index: 10;display: inline-block; width:'.$width.'; height:'.$height.'" align="center">
		<iframe src="'.$url.'" frameborder="0" height="100%" width="100%" allowtransparency="true" seamless allowfullscreen="true" webkitallowfullscreen="true"></iframe>
	</div>';
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
