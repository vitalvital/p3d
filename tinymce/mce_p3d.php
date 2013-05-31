<?php

if ( !defined('ABSPATH') )
    die('You are not allowed to call this page directly.');
    
global $wpdb;

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Insert 3D Object</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-content/plugins/p3d/tinymce/p3d.js"></script>

	<base target="_self" />
</head>
<body>
	<form name="p3d" action="#">
		<div style="border-bottom: 1px solid #DFDFDF; padding: 0 0 10px 0; margin: 0 0 10px 0;">
				<p style="font-style: italic;">Enter the URL of the 3D object from P3D.in</p>
				<p>It should look like this:<br /><strong>http://p3d.in/e/N7XTv+load+shading,subd,-hidden</strong></p>
				<div style="margin-bottom: 10px;">
				<label><span>P3D URL</span><input style="border-radius: 4px; padding: 3px; margin: 0 5px; width: 80%;" type="text" name="p3dlink" id="p3dlink" value="" ></label>
			</div>
			<div style="margin-bottom: 10px;">
				<label><span>Width (default - 100%)</span><input style="border-radius: 4px; padding: 3px; margin: 0 5px; width: 50px;" type="text" name="p3dwidth" id="p3dwidth" value="" ></label>
				<label><span>Height (default - 500px)</span><input style="border-radius: 4px; padding: 3px; margin: 0 5px;  width: 50px;" type="text" name="p3dheight" id="p3dheight" value="" ></label>
			</div>
		</div>
		
	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="Insert" onclick="insertP3dLink();" />
		</div>
	</div>
</form>
</body>
</html>
