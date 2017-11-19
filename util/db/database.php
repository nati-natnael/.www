<?php
	class DataBase {
		private $conn;

		function __construct() {
			$conn = NULL;
		}

		/**
		 * Connect to data base
		 *
		 * :param $host: connection name
		 * :param $port: connection port
		 * :param $db: name of the database
		 * :param $user: user name
		 * :param $pass: user password
		 */
		function connect ($host, $port, $db, $user, $pass) {
			$this->conn = new mysqli($host, $user, $pass, $db, $port);

			if ($this->conn->connect_errno) {
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
		function execQuery ($queryString) {
			$result = $this->conn->query($query);

			if (!$result) {
				return NULL;
			}

			return $result;
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

		/**
		 * Check loginnn account table.
		 * if user exist, return user's name.
		 * else return NULL;
		 */
		function login($acc_login, $acc_pass) {
			$query  = "SELECT * ";
			$query .= "FROM tbl_accounts ";
			$query .= "WHERE acc_login = '$acc_login' ";
			$query .= "AND acc_pass = '" . sha1($acc_pass) . "';";

			$results = $this->conn->query($query);

			if (!$results) {
				return NULL;
			}

			if ($results->num_rows <= 0) {
				return NULL;
			}

			$name = NULL;
			while ($row = $results->fetch_assoc()) {
				$name = $row['acc_name'];
			}

			return $name;
		}

		function insert($acc_name, $acc_login, $acc_pass) {
			$query  = "INSERT INTO tbl_accounts (acc_name, acc_login, acc_pass)";
			$query .= "VALUES ($acc_name, $acc_login," . sha1($acc_pass) . ");";

			return $this->conn->query($query);
		}

		function delete ($acc_name, $acc_login, $acc_pass) {
			echo "not developed yet";
		}
	}
?>
