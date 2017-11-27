let $ = (id) => {
  var ele = document.getElementById(id);
  return ele;
}

let edit = (event) => {
  var obj = event.srcElement || event.target;

  if (obj.value === 'Edit') {
    var nameInput = $('name_in_' + obj.name);
    var loginInput = $('login_in_' + obj.name);
    var newpass = $('newpass_' + obj.name);

    nameInput.style = 'border: 1px solid gray';
    loginInput.style = 'border: 1px solid gray';
    newpass.style = 'border: 1px solid gray';

    // disable readonly
    nameInput.removeAttribute('readonly');
    loginInput.removeAttribute('readonly');
    newpass.removeAttribute('readonly');

    // remove old buttons and add new ones
    var parent = obj.parentElement;
    var objID = obj.name;
    parent.removeChild(obj);
    parent.innerHTML = "<input id='update_"+objID+"' " +
                              "name='update' " +
                              "type='submit' " +
                              "value='Update'>";

    // Change delete button to cancel
    var deleteBtn = $('delete_'+objID);
    var dParent = deleteBtn.parentElement;
    dParent.removeChild(deleteBtn);
    dParent.innerHTML = "<input id='cancel_"+objID+"' " +
                               "name='"+objID+"'" +
                               "type='button' " +
                               "value='Cancel' " +
                               "onclick='cancel(event)'>";
  }
};

let cancel = (event) => {
  var obj = event.srcElement || event.target;

  if (obj.value === 'Cancel') {
    var nameInput = $('name_in_' + obj.name);
    var loginInput = $('login_in_' + obj.name);
    var newpass = $('newpass_' + obj.name);

    // hide borders
    nameInput.style = 'border: none';
    loginInput.style = 'border: none';
    newpass.style = 'border: none';

    // make readonly
    nameInput.setAttribute('readonly', 'readonly');
    loginInput.setAttribute('readonly', 'readonly');
    newpass.setAttribute('readonly', 'readonly');

    // Remove and add new buttons
    var parent = obj.parentElement;
    var objID = obj.name;
    parent.removeChild(obj);
    parent.innerHTML = "<input id='delete_"+objID+"' " +
                              "name='delete' " +
                              "type='submit' " +
                              "value='Delete'>";

    // Change delete button to cancel
    var updateBtn = $('update_'+objID);
    var uParent = updateBtn.parentElement;
    uParent.removeChild(updateBtn);
    uParent.innerHTML = "<input id='delete_"+objID+"' " +
                               "name='"+objID+"'" +
                               "type='button' " +
                               "value='Edit' " +
                               "onclick='edit(event)'>";
  }
};
