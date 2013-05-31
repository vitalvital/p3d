function insertP3dLink() {
	var url = document.getElementById('p3dlink').value;
	var width = document.getElementById('p3dwidth').value == '' ? '' : ' width="' + document.getElementById('p3dwidth').value + '"' ;
	var height = document.getElementById('p3dheight').value == '' ? '' : ' height="' + document.getElementById('p3dheight').value + '"' ;

	window.tinyMCEPopup.execCommand('mceInsertContent', false, '[p3d' + ' url="' + url + '"'  + width + height + ']');
	window.tinyMCEPopup.close();
}
