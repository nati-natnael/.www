let $ = (id) => {
  var ele = document.getElementById(id);
  return ele;
}

var name_img  = null;
var name_form = null;

var login_img  = null;
var login_form = null;

var pass_img  = null;
var pass_form = null;

let showUpdateName = () => {
  var name_img  = $('name_img');
  var name_form = $('name_form');
  /*
  if 'show form false' means click wants to show form
  else hide img
   */
  if (name_img.alt === 'show form false') {
    name_img.src = 'imgs/up.png';
    name_img.alt = 'show form true';
    name_form.style.display = 'block';

    hideOthers('name');
  } else {
    name_img.src = 'imgs/down.png';
    name_img.alt = 'show form false';
    name_form.style.display = 'none';
  }
};

let showUpdateLogin = () => {
  var login_img  = $('login_img');
  var login_form = $('login_form');

  /*
  if 'show form false' means click wants to show form
  else hide img
   */
  if (login_img.alt === 'show form false') {
    login_img.src = 'imgs/up.png';
    login_img.alt = 'show form true';
    login_form.style.display = 'block';

    hideOthers('login');
  } else {
    login_img.src = 'imgs/down.png';
    login_img.alt = 'show form false';
    login_form.style.display = 'none';
  }
};

let showUpdatePass = () => {
  var pass_img  = $('password_img');
  var pass_form = $('password_form');

  /*
  if 'show form false' means click wants to show form
  else hide img
   */
  if (pass_img.alt === 'show form false') {
    pass_img.src = 'imgs/up.png';
    pass_img.alt = 'show form true';
    pass_form.style.display = 'block';

    hideOthers('pass');
  } else {
    pass_img.src = 'imgs/down.png';
    pass_img.alt = 'show form false';
    pass_form.style.display = 'none';
  }
};

let hideOthers = (name) => {
  var name_img  = $('name_img');
  var name_form = $('name_form');

  var login_img  = $('login_img');
  var login_form = $('login_form');

  var pass_img  = $('password_img');
  var pass_form = $('password_form');
  
  if (name === 'name') {
    // hide login
    login_img.src = 'imgs/down.png';
    login_img.alt = 'show form false';
    login_form.style.display = 'none';

    // hide password
    pass_img.src = 'imgs/down.png';
    pass_img.alt = 'show form false';
    pass_form.style.display = 'none';
  } else if (name === 'login') {
    // hide name
    name_img.src = 'imgs/down.png';
    name_img.alt = 'show form false';
    name_form.style.display = 'none';

    // hide password
    pass_img.src = 'imgs/down.png';
    pass_img.alt = 'show form false';
    pass_form.style.display = 'none';
  } else if (name === 'pass') {
    // hide name
    name_img.src = 'imgs/down.png';
    name_img.alt = 'show form false';
    name_form.style.display = 'none';

    // hide login
    login_img.src = 'imgs/down.png';
    login_img.alt = 'show form false';
    login_form.style.display = 'none';
  }
}
