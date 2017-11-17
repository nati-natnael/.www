let $ = (id) => {
	var ele = document.getElementById(id);
	return ele;
};

let $$ = (class_name) => {
	var eles = document.getElementsByClassName(class_name);
	return eles;
};

let remove_error = () => {
	$('form_err').style.display = 'none';
}