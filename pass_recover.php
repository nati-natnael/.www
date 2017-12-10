<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <Title>Create New User</Title>
    <link rel="stylesheet" type="text/css" href="styles/common.css">
    <link rel="stylesheet" type="text/css" href="styles/login.css">
    <script type="text/javascript" src="scripts/login.js"></script>
    <?php
      header('Location: login.php', true, 301);
      die();
    ?>
	</head>
  <body>
      <div id="main_content">
          <h2>Password Recovery</h2>
          <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);

              include 'util/db/db_credentials.php';
              include 'util/db/database.php';
              include 'util/string_utils.php';
              include 'util/msg_handlers.php';

              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  # old credentials
                  $userName = $_POST['username'];
                  $fullname = $_POST['fullname'];

                  # proposed new credentials
                  $newPass     = $_POST['newpassword'];
                  $confirmPass = $_POST['confirmnewpassword'];

                  # if error at any point
                  if ($err) {
                      $errDiv  = "<div id='form_err'>";
                      $errDiv .= "<div id='form_err_header'>";
                      $errDiv .= "<span style='font-size: 1.5em;'>Error</span>";
                      $errDiv .= "<input id='form_err_input'
                                         type='button'
                                         value='X'
                                         onclick='remove_error()'>";
                      $errDiv .= "</div>";
                      $errDiv .= $errMsgs;
                      $errDiv .= "</div>";

                      echo $errDiv;
                  }
              }
          ?>

          <form method="post" action="pass_recover.php">
              <div id="login_elements">
                  <!-- Confirm the user is the user -->
                  <!-- For Security purposes -->
                  <div id="confirm_user">
                      <div id="username">
                          <div class="label_ele">
                              <label>Login</label>
                          </div>
                          <div class="input_ele">
                              <input id="username_in" type="text" name="username">
                          </div>
                      </div>
                      <div id="fullname">
                          <div class="label_ele">
                              <label>Full name</label>
                          </div>
                          <div class="input_ele">
                              <input id="username_in" type="text" name="fullname">
                          </div>
                      </div>
                  </div>

                  <!-- new pass input -->
                  <div id="password">
                      <div class="label_ele">
                          <label>New Password</label>
                      </div>
                      <div class="input_ele">
                          <input id="username_in" type="password" name="newpassword">
                      </div>
                  </div>
                  <div id="password">
                      <div class="label_ele">
                          <label>Confirm New Password</label>
                      </div>
                      <div class="input_ele">
                          <input id="password_in" type="password" name="password">
                      </div>
                  </div>
                  <div id="s_id">
                      <input type="submit" name="submit" value="Change">
                  </div>
              </div>
          </form>
      </div>
  </body>
</html>
