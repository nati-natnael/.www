<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<Title>My Account</Title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
    <link rel="stylesheet" type="text/css" href="styles/admin.css">
    <script type="text/javascript" src="scripts/admin.js"></script>
		</script>
	</head>
	<body style="background: silver;">
		<div id="main_wrapper">
	    <!-- Heading -->
	    <h2 id="heading">Admin</h2>

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
	    			<a href="#"><div id="admin">Admin</div></a>
	    			<a href="logout.php"><div id="logout">Logout</div></a>
	    		</div>
	    	</nav>

	      <!-- content -->
	      <div id="content_wrapper">
					<div id="status">
						<?php
							include 'util/db/db_credentials.php';
							include 'util/db/database.php';
							include 'util/msg_handlers.php';

							if ($_SERVER['REQUEST_METHOD'] === 'POST') {
								$btnType = $_POST['button'];

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

								// Update info of existing users
								if ($btnType === 'Update') {
									$id        = $_POST['id'];
									$firstName = $_POST['firstname'];
									$lastName  = $_POST['lastname'];
									$login     = $_POST['login'];
									$password  = $_POST['newpassword'];

									$valid = TRUE;
									if (!validate($firstName)) {
										$valid = FALSE;
										echo errMsg("First name should be alpha-numeric");
									}

									if (!validate($lastName)) {
										$valid = FALSE;
										echo errMsg("Last name should be alpha-numeric");
									}

									if (!validate($login)) {
										$valid = FALSE;
										echo errMsg("Login should be alpha-numeric");
									}

									if ($valid) {
										$formattedName = $lastName.", ".$firstName;

										if (empty($password)) {
											$updateQuery  = "UPDATE tbl_accounts
																			 SET acc_name = '$formattedName',
																			 		 acc_login = '$login'
																			 WHERE acc_id = '$id';";

											$status = $database->execQuery($updateQuery);
 											if ($status) {
 												echo successMsg("Account updated successfully");
 											} else {
 												echo errMsg("Account update failed");
 											}
										}

										if (!empty($password)) {
											if (!passwordValidate($password)) {
												$valid = FALSE;
												echo errMsg("Invalid password");
											}

											if ($valid) {
												$updateQuery  = "UPDATE tbl_accounts
																				 SET acc_name = '$formattedName',
																				 		 acc_login = '$login',
																						 acc_password = ' ".sha1($password)." '
																				 WHERE acc_id = '$id';";

												$status = $database->execQuery($updateQuery);
 												if ($status) {
 													echo successMsg("Account updated successfully");
 												} else {
 													echo errMsg("Account update failed");
 												}
										  }
										}
									}
								}

								// Remove users
								if ($btnType === 'Delete') {
									$login = $_POST['login'];
									$status = $database->delete($login);

									if ($status) {
										echo successMsg("Account Deleted");
									} else {
										echo errMsg("Account delete failed");
									}
								}

								// Add new users
								if ($btnType === 'Create') {
									# New user
									$firstName = $_POST['firstname'];
									$lastName  = $_POST['lastname'];
									$userName  = $_POST['username'];
									$password  = $_POST['password'];

									$valid = TRUE;
									if (!validate($firstName)) {
										$valid = FALSE;
										echo errMsg("First name should be alpha-numeric");
									}

									if (!validate($lastName)) {
										$valid = FALSE;
										echo errMsg("Last name should be alpha-numeric");
									}

									if (!validate($userName)) {
										$valid = FALSE;
										echo errMsg("User name should be alpha-numeric");
									}

									if (!passwordValidate($password)) {
										$valid = FALSE;
										echo errMsg("Invalid password");
									}

									if ($valid) {
										$formattedName = $lastName.", ".$firstName;

										$query  = "SELECT * ";
										$query .= "FROM tbl_accounts ";
										$query .= "WHERE acc_login = '$userName'";

										$status = $database->execQuery($query);

										if ($status) {
											$status = $database->insert($formattedName, $userName, $password);

											if ($status) {
												echo successMsg("New User: ".$firstName." created");
											} else {
												echo errMsg("Failed to add new User: ".$firstName);
											}
										} else {
											echo errMsg("This Login is used by another user");
										}
									}
								}
							}
						?>
					</div>
	        <div id="user_lst">
						<h2>Users</h2>
	          <div id="user_lst_view">
	            <?php
	             // error_reporting(E_ALL);
	             // ini_set('display_errors', 1);
	             // ini_set('display_startup_errors', 1);

	             function openTable() {
									# Table headers
									$table  = "<div class='table'>";
									$table .= "<div class='tr'>";
									$table .= "<div class='td head'>ID</div>";
									$table .= "<div class='td head'>Name</div>";
									$table .= "<div class='td head'>Login</div>";
									$table .= "<div class='td head'>New Password</div>";
									$table .= "<div class='td head'>Action</div>";
									$table .= "</div>";

									return $table;
	             }

	             function closeTable() {
									$table = "</div>";

									return $table;
	             }

	             /**
	              * Creates row with a single user info
	              */
	             function userRow ($id, $name, $login) {
	               $user  = "<form id='user_$id' class='tr' method='post' action='admin.php'>";
									$user .= "<div class='td user_id'>$id</div>";

									$user .= "<div id='name_in_$id' class='td user_name'>";
									$user .= $name;
									$user .= "</div>";
									$user .= "<div class='td user_login'>";
									$user .= "<input id='login_in_$id' ";
									$user .= 				"type='text' ";
									$user .= 				"name='login' ";
									$user .= 				"value='$login' readonly>";
									$user .= "</div>";

									$user .= "<div class='td user_newpass'>";
									$user .= "<input id='newpass_$id' name='newpassword' type='password' readonly>";
									$user .= "</div>";

									// Action buttons
									$user .= "<div class='td'>";

									$user .= "<div class='edit_btn'>";
									$user .= "<input id='edit_$id' ";
									$user .=        "name='$id' ";
									$user .= 				"type='button' ";
									$user .= 				"value='Edit' ";
									$user .= 				"onclick='edit(event)'>";
									$user .= "</div>";

									$user .= "<div class='delete_btn'>";
									$user .= "<input id='delete_$id' ";
									$user .= 			 	"name='button' ";
									$user .= 			 	"type='submit' ";
									$user .= 				"value='Delete' ";
									$user .= 				"onclick='cancel(event)'>";
									$user .= "</div>";

									$user .= "<div class='hidden'>";
									$user .= "<input value='$id' name='id' type='hidden'>";
									$user .= "</div>";

									$user .= "</div>";
									$user .= "</form>";

	               return $user;
	             }

	             /**
	              * Create table of users
	              */
	             function lstUsers () {
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
	                 $query = "SELECT * FROM tbl_accounts;";
	                 $results = $database->execQuery($query);

	                 if ($results != NULL) {
	                   $table = openTable();
											$id = 1;
	                   while ($row = $results->fetch_assoc()) {
	                     $id    = $row['acc_id'];
	                     $name  = $row['acc_name'];
	                     $login = $row['acc_login'];

	                     $table .= userRow($id, $name, $login);

												$id++;
	                   }
	                   $table .= closeTable();

											echo $table;
	                 } else {
	                   echo "No Data Found";
	                 }
	               } else {
	               	echo errMsg("DataBase connection failed");
	               }
	             }

	             lstUsers();
	            ?>
	          </div>
	        </div>

	        <!-- Create new user account -->
	        <div id="add_new_user">
						<h2>Add New User</h2>
	          <form method="post" action="admin.php">
	              <div id="new_user_wrapper">
	                  <!-- New user -->
	                  <div id="new_user">
	                      <!-- First name -->
	                      <div id="firstname">
	                          <div class="label_ele">
	                              <label>First name</label>
	                          </div>
	                          <div class="input_ele">
	                              <input id="firstname_in" type="text" name="firstname">
	                          </div>
	                      </div>

	                      <!-- last name -->
	                      <div id="lastname">
	                          <div class="label_ele">
	                              <label>Last name</label>
	                          </div>
	                          <div class="input_ele">
	                              <input id="lastname_in" type="text" name="lastname">
	                          </div>
	                      </div>

	                      <!-- New Login credentials -->
	                      <div id="username">
	                          <div class="label_ele">
	                              <label>Login name</label>
	                          </div>
	                          <div class="input_ele">
	                              <input id="username_in" type="text" name="username">
	                          </div>
	                      </div>
	                      <div id="password">
	                          <div class="label_ele">
	                              <label>Password</label>
	                          </div>
	                          <div class="input_ele">
	                              <input id="password_in" type="password" name="password">
	                          </div>
	                      </div>
	                      <div id="s_id">
	                          <input type="submit" name="button" value="Create">
	                      </div>
	                  </div>
	              </div>
	          </form>
	        </div>
	      </div>
			</div>
	</body>
</html>
