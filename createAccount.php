<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<Title>Create New User</Title>
		<link rel="stylesheet" type="text/css" href="styles/common_style.css">
		<link rel="stylesheet" type="text/css" href="styles/login_style.css">
        <script type="text/javascript" src="scripts/login.js"></script>
	</head>
    <body>
        <div id="main_content">
            <?php
                include 'util/db/params.php';
                include 'util/db/database.php';
                include 'util/string_utils.php';
                include 'util/err_handlers.php';
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $adminUser = $_POST['adminuser'];
                    $adminPass = $_POST['adminpass'];
                    
                    # New user
                    $firstName = $_POST['firstname'];
                    $lastName = $_POST['lastname'];
                    $userName = $_POST['username'];
                    $password = $_POST['password'];
                    
                    # Error triggers
                    $err = FALSE;
                    $errMsgs = "";
                    
                    if (!validate($adminUser)) {
                        $errMsgs .= errMsg("Admin user name should alpha-numeric");
                        $err = TRUE;
                    }
                    
                    if (!validate($firstName)) {
                        $errMsgs .= errMsg("first name should alpha-numeric");
                        $err = TRUE;
                    }
                    
                    if (!validate($lastName)) {
                        $errMsgs .= errMsg("Last name should alpha-numeric");
                        $valid = FALSE;
                    }
                    
                    if (!validate($userName)) {
                        $errMsgs .= errMsg("User name should alpha-numeric");
                        $err = TRUE;
                    }

                    if (!$err) {
                        $status = TRUE;
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
                            # check admin credentials
                            if ($database->login($adminUser, $adminPass)) {
                                $name = $lastName . ", " . $firstName;
                                
                                if ($database->insert($name, $userName, $password)) {
                                    echo "New User Added";
                                } else {
                                    $errMsgs = errMsg("New user creation failed");
                                    $err = TRUE;
                                }
                            } else {
                                $errMsgs = errMsg("Only Admin can add new user");
                                $err = TRUE;
                            }                            
                        } else {
                            $errMsgs = errMsg("Mysql connection failed");
                            $err = TRUE;
                        }
                        
                    }
                    
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

            <form method="post" action="createAccount.php">
                <div id="login_elements">
                    <!-- Admin user check -->
                    <div id="admin">
                        <div id="username">
                            <div class="label_ele">
                                <label>Admin user name:</label>
                            </div>
                            <div class="input_ele">
                                <input id="username_in" type="text" name="adminuser">
                            </div>
                        </div>
                        <div id="password">
                            <div class="label_ele">
                                <label>Admin Password:</label>
                            </div>
                            <div class="input_ele">
                                <input id="password_in" type="password" name="adminpass">
                            </div>
                        </div>
                    </div>
                    
                    <!-- New user -->
                    <div id="new_user">
                        <!-- First name -->
                        <div id="firstname">
                            <div class="label_ele">
                                <label>First name:</label>
                            </div>
                            <div class="input_ele">
                                <input id="firstname_in" type="text" name="firstname">
                            </div>
                        </div>
                        
                        <!-- last name -->
                        <div id="lastname">
                            <div class="label_ele">
                                <label>Last name:</label>
                            </div>
                            <div class="input_ele">
                                <input id="lastname_in" type="text" name="lastname">
                            </div>
                        </div>
                        
                        <!-- New Login credentials -->
                        <div id="username">
                            <div class="label_ele">
                                <label>Login name:</label>
                            </div>
                            <div class="input_ele">
                                <input id="username_in" type="text" name="username">
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
                        <div id="s_id">
                            <input type="submit" name="submit" value="Create Account">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>