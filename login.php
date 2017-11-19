<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<Title>Login</Title>
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

                function handle_login() {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $userName = $_POST['username'];
                        $password = $_POST['password'];
                        
                        $valid = TRUE;
                        $errMsgs = "";
                        if (!validate($userName)) {
                            $errMsgs .= errMsg("User Not Found");
                            $valid = FALSE;
                        }

                        if ($valid) {
                            $status = TRUE;
                            //global $db_servername;
                            //global $db_port;
                            //global $db_name;
                            //global $db_username;
                            //global $db_password;

                            //$database = new DataBase();
                            //var_dump($database);
                            //$status = $database->connect($db_servername,
                            //                       $db_port,
                            //                       $db_name,
                            //                       $db_username,
                            //                       $db_password);

                            if ($status) {
                                if (/*login($userName, $password)*/TRUE) {
                                    // store name of current user
                                    session_start();
                                    $_SESSION['username'] = $userName;
                                    // redirect to calendar page
                                    header('Location: calendar.php', true, 301);
                                    die();
                                } else {
                                    $errDiv  = "<div id='form_err'>";
                                    $errDiv .= "<div id='form_err_header'>";
                                    $errDiv .= "<span style='font-size: 1.5em;'>Error</span>";
                                    $errDiv .= "<input id='form_err_input'
                                                       type='button'
                                                       value='X'
                                                       onclick='remove_error()'>";
                                    $errDiv .= "</div>";
                                    $errDiv .= errMsg("User not Found");
                                    $errDiv .= "</div>";

                                    echo $errDiv;
                                }
                            } else {
                                echo "failed to connect";
                            }
                        } else {
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
                }

                handle_login();
            ?>

            <form method="post" action="">
                <div id="login_elements">
                    <h1>Login Page</h1>
                    <div id="username">
                        <div class="label_ele">
                            <label>User Name:</label>
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
                        <input type="submit" name="submit" value="Login">
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
