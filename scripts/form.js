function $(id) {
	var ele = document.getElementById(id);
	return ele;
}

function $$(class_name) {
	var eles = document.getElementsByClassName(class_name);
	return eles;
}

function check_alphanumeric (string) {
	var reg = /^[a-z0-9]+$/;

	return reg.test(string);
}

function form_verify () {
	var inputs = ['event_name_in', 'location_in'];

	for (var i = 0; i < inputs.length; i++) {
		var ele = $(inputs[i]);
		var val = ele.value;
		val = val.toLowerCase().replace(/\s/g, '');

		var res = check_alphanumeric(val);

		if (!res) {
			// Display Error Message
			$('error_dialog').style.display = 'flex';
			return false;
		}
	}

	return true;
}

function remove_error () {
	$('error_dialog').style.display = 'none';
}