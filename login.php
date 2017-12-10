<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<Title>Login</Title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
		<link rel="stylesheet" type="text/css" href="styles/login.css">
        <script type="text/javascript" src="scripts/login.js"></script>
	</head>
    <body>
        <div id="main_content">
            <h2>Login Page</h2>
            <div id="status">
              <?php
                  error_reporting(E_ALL);
                  ini_set('display_errors', 1);
                  ini_set('display_startup_errors', 1);

                  include 'util/db/db_credentials.php';
                  include 'util/db/database.php';
                  include 'util/string_utils.php';
                  include 'util/msg_handlers.php';

                  function handle_login() {
                      $err = FALSE;
                      $errMsgs = "";

                      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                          $userName = $_POST['username'];
                          $password = $_POST['password'];

                          if (!validate($userName)) {
                              echo errMsg("User name should alpha-numeric");
                              $err = TRUE;
                          }

                          if (!passwordValidate($password)) {
                              echo errMsg("Incorrect password");
                              $err = TRUE;
                          }

                          if (!$err) {
                              global $db_servername;
                              global $db_port;
                              global $db_name;
                              global $db_username;
                              global $db_password;

                              $database = new DataBase();
                              $status = $database->connect($db_servername,
                                                           $db_port,
                                                           $db_name,
                                                           $db_username,
                                                           $db_password);

                              if ($status) {
                                  $nameOfUser = $database->login($userName, $password);
                                  if ($nameOfUser != NULL) {
                                      // store name of current user
                                      session_start();
                                      $_SESSION['username'] = $nameOfUser;
                                      // redirect to calendar page
                                      header('Location: calendar.php', true, 301);
                                      die();
                                  } else {
                                      echo errMsg("The username or password is incorrect");
                                  }
                              } else {
                                  // echo errMsg("Mysql connection failed");
                              }
                          }
                      }
                  }

                  handle_login();
              ?>
            </div>

            <form method="post" action="">
                <div id="login_elements">
                    <div id="username">
                        <div class="label_ele" style="width: 80px;">
                            <label>User Name</label>
                        </div>
                        <div class="input_ele">
                            <input id="username_in" type="text" name="username">
                        </div>
                    </div>
                    <div id="password">
                        <div class="label_ele" style="width: 80px;">
                            <label>Password</label>
                        </div>
                        <div class="input_ele">
                            <input id="password_in" type="password" name="password">
                            <!-- <a id="forgot" href="pass_recover.php">forgot password</a> -->
                        </div>
                    </div>
                    <div id="s_id">
                        <input type="submit" name="submit" value="Login">
                        <!-- <a href="create_acc.php">Create account</a> -->
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
