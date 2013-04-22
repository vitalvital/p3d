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
	<script language="javascript" type="text/javascript">
		var old=false;
	</script>
	<base target="_self" />
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';document.getElementById('p3d_tab').focus();" style="display: none">
<!-- <form onsubmit="insertLink();return false;" action="#"> -->
	<form name="p3d" action="#">
	<div class="tabs">
		<ul>			
			<li id="p3d_tab" class="current"><span><a href="javascript:mcTabs.displayTab('p3d_tab','p3d_panel');" onmousedown="return false;">3D Object</a></span></li>
		</ul>
	</div>
	
	<div class="panel_wrapper" style="height: 100px;">
		<div id="p3d_panel" class="panel current">
		<br />
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr>
					<td nowrap="nowrap" valign="top">
						<label>Enter the URL of the 3D object from P3D.in</label>
					</td>
				</tr>
				<tr>
					<td  nowrap="nowrap" valign="top">
						<input type="text" id="p3dlink" name="p3dlink" style="width: 100%" value="URL" onclick="if(!old) { this.value=''; old=true; }"/>
					</td>
				</tr>
				<tr>
					<td nowrap="nowrap" valign="top">
						<label>It should look like this:
							<ul>
								<li>http://p3d.in/DtaO3/</li>
							</ul>
						</label>
					</td>
				</tr>
			</table>
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
