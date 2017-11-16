<?php
	class db {
		var $connection = NULL;

		/**
		 * Connect to data base
		 *
		 * :param $conn_link: connection name
		 * :param $port: connection port
		 * :param $db_name: name of the database
		 * :param $userName:
		 * :param $password:
		 */
		function connect ($serverName, $port, $db_name, $userName, $password) {
			$connection = mysqli_connect($serverName,
																	 $userName,
																	 $password,
																	 $dbName,
																	 $port);

			if (mysqli_connect_errno()) {
				// connection failed
				return FALSE;
			} else {
				// connection successful
				return TRUE;
			}
		}

		/**
		 * executes general mysql query
		 */
		function selectQuery ($queryString) {
			mysqli_query($connection, $query);
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

		}

		function insert($acc_name, $acc_login, $acc_pass) {
			$query  = "INSERT INTO tbl_accounts (acc_name, acc_login, acc_pass)";
			$query .= "VALUES ($acc_name, $acc_login, $acc_pass);";

			mysqli_query($connection, $query);
		}

		function delete ($acc_name, $acc_login, $acc_pass) {

		}
	}
?>
