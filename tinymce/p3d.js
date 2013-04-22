function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function is_p3d( objectURL ) {
	var answer=false;
	var filter=/^http:\/\/p3d.in(.+)$/;
	if (filter.test(objectURL)) {
		answer=true;
	}
	return answer;
}


function insertP3dLink() {
	
	var tagtext;
	var add_text = false;
	var error = true;

	var p3d = document.getElementById('p3d_panel');

	// who is active ?
	if(p3d.className.indexOf('current') != -1) {
		var link = document.getElementById('p3dlink').value;
		var type = 'error';

		
		if(is_p3d(link)) {
			type = 'p3d';
			error = false;
		}


		if(error) {
			link = "Not a P3D object URL: " + link;
		}

		tagtext = "[" + type + "]" + link + "[/" + type + "]";
		add_text = true;
	}

	
	if(add_text) {
		window.tinyMCEPopup.execCommand('mceInsertContent', false, tagtext);
	}
	window.tinyMCEPopup.close();
}
