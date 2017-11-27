<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<Title>My Account</Title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
    <link rel="stylesheet" type="text/css" href="styles/admin.css">
    <script type="text/javascript" src="scripts/acc_settings.js"></script>
		</script>
	</head>
	<body style="background: silver;">
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
          // header('Location: login.php', true, 301);
          // die();
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
        <div id="user_lst">
          <div id="user_lst_view">
            <?php
              // error_reporting(E_ALL);
              // ini_set('display_errors', 1);
              // ini_set('display_startup_errors', 1);

              include 'util/db/params.php';
              include 'util/db/database.php';
              include 'util/msg_handlers.php';

              function openTable() {
								# Table headers
								$table  = "<table>";
								$table .= "<thead>";
								$table .= "<tr>";
								$table .= "<th>ID</th>";
								$table .= "<th>Login</th>";
								$table .= "<th>New Password</th>";
								$table .= "<th>Action</th>";
								$table .= "</tr>";
								$table .= "</thead>";

								echo $table;
              }

              function closeTable() {
								$table  = "</tbody>";
								$table .= "</table>";

								echo $table;
              }

              /**
               * Creates row with a single user info
               */
              function userRow ($id, $name, $login) {
                $user  = "<tr>";
                $user .= "<div class='row'>";
								$user .= "<div id='user_" . $id . "_id'>$id</div>";
								$user .= "<div id='user_" . $id . "_name'>$name</div>";
								$user .= "<div id='user_" . $id . "_login'>$login</div>";
								$user .= "<div id='user_" . $id . "_newpass'></div>";
								$user .= "<div id='user_" . $id . "_btns'></div>";
                $user .= "</div>";
                $user .= "</tr>";

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
                    openTable();
										$id = 1;
                    while ($row = $results->fetch_assoc()) {
                      // $id    = $row['rownum'];
                      $name  = $row['acc_name'];
                      $login = $row['acc_login'];

                      echo userRow($id, $name, $login);

											$id++;
                    }
                    closeTable();
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
          <form method="post" action="create_acc.php">
              <div id="login_elements">
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
                          <input type="submit" name="submit" value="create">
                      </div>
                  </div>
              </div>
          </form>
        </div>
      </div>
	</body>
</html>
