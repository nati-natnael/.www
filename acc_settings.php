<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<Title>My Account</Title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
    <link rel="stylesheet" type="text/css" href="styles/acc_settings.css">
    <script type="text/javascript" src="scripts/acc_settings.js"></script>
		</script>
	</head>
	<body>
		<div id="main_wrapper">
			<!-- Heading -->
			<h2 id="heading">Account Setting</h2>

			<!-- Check if logged in -->
			<div id="wel_logout">
				<?php
					error_reporting(E_ALL);
					ini_set('display_errors', 1);
					ini_set('display_startup_errors', 1);

					include 'util/string_utils.php';
					session_start();
					if (isset($_SESSION['username'])) {
						$fullName   = $_SESSION['username'];
						$namePieces = explode(",", $fullName);

						// welcome message
						$welcomeMsg  = '<div id="welcome">Welcome ';
						$welcomeMsg .= capitalizeWords($namePieces[1]);
						$welcomeMsg .= '</div>';

						echo $welcomeMsg;
					} else {
						header('Location: login.php', true, 301);
						die();
					}
				?>
			</div>

			<!-- navigation -->
			<nav id="main_nav">
				<a href="calendar.php"><div id="nav_cal_id">MyCalendar</div></a>
				<a href="form.php"><div id="nav_form_id">Form Input</div></a>

				<div id="right_menu">
					<a href="#"><div id="acc_setting">Account Setting</div></a>
					<a href="logout.php"><div id="logout">Logout</div></a>
				</div>
			</nav>

      <!-- Handle post requests -->
      <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);

        include 'util/db/params.php';
        include 'util/db/database.php';
        include 'util/msg_handlers.php';

        function changeName($database) {
          $firstName = $_POST['firstname'];
          $lastName  = $_POST['lastname'];

          $loginName = $_POST['loginname'];
          $password  = $_POST['password'];

          $formattedName = $lastName . ", " . $firstName;

          if ($database->login($loginName, $password)) {
            // update name
            $status = $database->updateName($formattedName, $loginName, $password);
						if ($status) {
							echo successMsg("User name updated");
						} else {
							echo errMsg("update failed");
						}
          } else {
            echo errMsg("The username or password is incorrect");
          }
        }

        function changeLogin($database) {
          $oldLogin = $_POST['oldlogin'];
          $newLogin = $_POST['newlogin'];
          $password = $_POST['password'];

					if ($database->login($oldLogin, $password)) {
            // update login
            $status = $database->updateLogin($oldLogin, $newLogin, $password);
						if ($status) {
							echo successMsg("Login name updated");
						} else {
							echo errMsg("Login update failed");
						}
          } else {
            echo errMsg("The username or password is incorrect");
          }
        }

        function changePassword($database) {
          $loginName = $_POST['loginname'];
          $oldPass   = $_POST['oldpassword'];
          $newPass   = $_POST['newpassword'];

					if ($database->login($loginName, $oldPass)) {
            // update password
            $status = $database->updatePass($loginName, $oldPass, $newPass);
						if ($status) {
							echo successMsg("Password updated");
						} else {
							echo errMsg("Password update failed");
						}
          } else {
            echo errMsg("The username or password is incorrect");
          }
        }

        function handlePost() {
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $submitType = $_POST['submit'];

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
	            switch ($submitType) {

	              case 'Change Name':
	                changeName($database);
	                break;

	              case 'Change Login':
	                changeLogin($database);
	                break;

	              case 'Change Password':
	                changePassword($database);
	                break;

	              default:
	                echo "Default hit";

	            }
	          } else {
							echo errMsg("DataBase connection failed");
						}
					}
        }

        handlePost();
      ?>

			<div id="content_wrapper">
        <!-- Change users full name -->
        <div id="update_acc">
          <div id="chng_name">
            <div style="margin-bottom: 1em;
										    border-bottom: 1px solid black;">
              Change user name
              <img id="name_img"
                   class="show_form_img"
                   src="imgs/down.png"
                   onclick="showUpdateName()"
                   alt="show form false">
            </div>
            <form id="name_form" method="post" action="acc_settings.php">
              <!-- Enter password for Security reasons -->
              <div id="login">
                  <div class="label_ele">
                      <label>Login name:</label>
                  </div>
                  <div class="input_ele">
                      <input id="login_in" type="text" name="loginname">
                  </div>
              </div>
              <div id="password">
                  <div class="label_ele">
                      <label>Password:</label>
                  </div>
                  <div class="input_ele">
                      <input id="password_in" type="password" name="password">
                  </div>
              </div>
              <div id="first_name">
                  <div class="label_ele">
                      <label>First name:</label>
                  </div>
                  <div class="input_ele">
                      <input id="first_name_in" type="text" name="firstname">
                  </div>
              </div>
              <div id="last_name">
                  <div class="label_ele">
                      <label>Last name:</label>
                  </div>
                  <div class="input_ele">
                      <input id="last_name_in" type="text" name="lastname">
                  </div>
              </div>
              <div class="submit_ele">
                  <input type="submit" name="submit" value="Change Name">
              </div>
            </form>
          </div>

          <!-- change users login name -->
          <div id="chng_login">
						<div style="margin-bottom: 1em;
										    border-bottom: 1px solid black;">
              Update login
              <img id="login_img"
                   class="show_form_img"
                   src="imgs/down.png"
                   onclick="showUpdateLogin()"
                   alt="show form false">
            </div>
            <form id="login_form" method="post" action="acc_settings.php">
              <!-- Enter password for Security reasons -->
              <div id="old_login">
                  <div class="label_ele">
                      <label>Old Login:</label>
                  </div>
                  <div class="input_ele">
                      <input id="login_in" type="text" name="oldlogin">
                  </div>
              </div>
              <div id="password">
                  <div class="label_ele">
                      <label>Password:</label>
                  </div>
                  <div class="input_ele">
                      <input id="password_in" type="password" name="password">
                  </div>
              </div>

              <div id="new_login">
                  <div class="label_ele">
                      <label>New Login:</label>
                  </div>
                  <div class="input_ele">
                      <input id="login_in" type="text" name="newlogin">
                  </div>
              </div>
              <div class="submit_ele">
                  <input type="submit" name="submit" value="Change Login">
              </div>
            </form>
          </div>

          <!-- change users password -->
          <div id="chng_pass">
						<div style="margin-bottom: 1em;
										    border-bottom: 1px solid black;">
              Update password
              <img id="password_img"
                   class="show_form_img"
                   src="imgs/down.png"
                   onclick="showUpdatePass()"
                   alt="show form false">
            </div>
            <form id="password_form" method="post" action="acc_settings.php">
              <div id="login_name">
                  <div class="label_ele">
                      <label>Login name:</label>
                  </div>
                  <div class="input_ele">
                      <input id="login_in" type="text" name="loginname">
                  </div>
              </div>
              <div id="old_password">
                  <div class="label_ele">
                      <label>Old password:</label>
                  </div>
                  <div class="input_ele">
                      <input id="login_in" type="password" name="oldpassword">
                  </div>
              </div>

              <div id="new_password">
                  <div class="label_ele">
                      <label>New password:</label>
                  </div>
                  <div class="input_ele">
                      <input id="password_in" type="password" name="newpassword">
                  </div>
              </div>
              <div class="submit_ele">
                  <input type="submit" name="submit" value="Change Password">
              </div>
            </form>
          </div>
        </div>

			</div>
		</div>
	</body>
</html>
