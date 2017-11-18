<?php
	class DataBase {
		var $connection = NULL;

		function __construct() {
			echo "Object created";
		}

		/**
		 * Connect to data base
		 *
		 * :param $conn_link: connection name
		 * :param $port: connection port
		 * :param $db_name: name of the database
		 * :param $userName:
		 * :param $password:
		 */
		function connect ($serverName, $port, $dbName, $userName, $password) {
			$this->connection = mysqli_connect($serverName,
										 $userName,
										 $password,
										 $dbName,
										 $port);

			if (mysqli_connect_errno()) {
				// connection failed
				echo "failed";
				var_dump(mysqli_connect_error());
				return FALSE;
			} else {
				echo "success";
				// connection successful
				return TRUE;
			}
		}

		/**
		 * executes general mysql query
		 */
		function execQuery ($queryString) {
			mysqli_query($this->connection, $query);
		}

		/**
		 * update values
		 *
		 * Note:
		 * 	will use $acc_login to find account
		 *
		 * :param $acc_name:
		 * :param $acc_login:
		 * :param $acc_pass:
		 */
		function update($acc_name, $acc_login, $acc_pass) {
			echo "not developed yet";
		}

		function login($acc_login, $acc_pass) {
			$query  = "SELECT * ";
			$query .= "FROM tbl_accounts ";
			$query .= "WHERE acc_login = '$acc_login' AND acc_pass = '$acc_pass'";

			$results = mysqli_query($this->connection, $query);

			if ($results->num_rows > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		}

		function insert($acc_name, $acc_login, $acc_pass) {
			$query  = "INSERT INTO tbl_accounts (acc_name, acc_login, acc_pass)";
			$query .= "VALUES ($acc_name, $acc_login, $acc_pass);";

			mysqli_query($this->connection, $query);
		}

		function delete ($acc_name, $acc_login, $acc_pass) {
			echo "not developed yet";
		}
	}
?>
