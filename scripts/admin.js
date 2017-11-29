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

    loginInput.style = 'border: 1px solid gray';
    newpass.style = 'border: 1px solid gray';

    // Add input tags for first and last name input
    var formattedName = nameInput.innerHTML;
    formattedName = formattedName.replace(',', '');
    var nameSplit = formattedName.split(' ');
    var fNameInput = "<input id = 'firstname_"+obj.name+"' " +
                            "style='border: 1px solid gray;'" +
                            "type = 'text' " +
                            "name = 'firstname' " +
                            "value = '"+nameSplit[1]+"'> ";
    var lNameInput = "<input id = 'lastname_"+obj.name+"' " +
                            "style='border: 1px solid gray;'" +
                            "name = 'lastname' " +
                            "type = 'text' " +
                            "value = '"+nameSplit[0]+"'>";

    nameInput.innerHTML = fNameInput + lNameInput;

    // disable readonly
    loginInput.removeAttribute('readonly');
    newpass.removeAttribute('readonly');

    // remove old buttons and add new ones
    var parent = obj.parentElement;
    var objID = obj.name;
    parent.removeChild(obj);
    parent.innerHTML = "<input id    = 'update_"+objID+"' " +
                              "name  = 'button' " +
                              "type  = 'submit' " +
                              "value = 'Update'>";

    // Change delete button to cancel
    var deleteBtn = $('delete_'+objID);
    var dParent = deleteBtn.parentElement;
    dParent.removeChild(deleteBtn);
    dParent.innerHTML = "<input id      = 'cancel_"+objID+"' " +
                               "name    = '"+objID+"' " +
                               "type    = 'button' " +
                               "value   = 'Cancel'" +
                               "onclick = 'cancel(event)'>";
  }
};

let cancel = (event) => {
  var obj = event.srcElement || event.target;

  if (obj.value === 'Cancel') {
    var nameInput = $('name_in_' + obj.name);
    var loginInput = $('login_in_' + obj.name);
    var newpass = $('newpass_' + obj.name);

    // hide borders
    loginInput.style = 'border: none';
    newpass.style = 'border: none';

    // get values of inputs and write to div
    var fName = $('firstname_' + obj.name);
    var lName = $('lastname_' + obj.name);
    nameInput.innerHTML = lName.value + ", " + fName.value;

    // make readonly
    loginInput.setAttribute('readonly', 'readonly');
    newpass.setAttribute('readonly', 'readonly');

    // Remove and add new buttons
    var cParent = obj.parentElement;
    var objID = obj.name;
    cParent.removeChild(obj);
    cParent.innerHTML = "<input id='delete_"+objID+"' " +
                              "name='button' " +
                              "type='submit' " +
                              "value='Delete'>";

    // Change delete button to cancel
    var updateBtn = $('update_'+objID);
    var uParent = updateBtn.parentElement;
    uParent.removeChild(updateBtn);
    uParent.innerHTML = "<input id='edit_"+objID+"' " +
                               "name='"+objID+"'" +
                               "type='button' " +
                               "value='Edit' " +
                               "onclick='edit(event)'>";
  }
};
