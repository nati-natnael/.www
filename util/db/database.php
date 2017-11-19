<?php
	class DataBase {
		var $conn;

		function __construct() {
			echo "Object created";
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
			echo "connect called";
			
			$this->conn = new mysqli($host, $user, $pass, $db, $port);

			if ($this->conn->connect_errno) {
				// connection failed
				echo "mysql connection failed";
				var_dump($this->conn->connect_errno);
				return FALSE;
			} else {
				echo "mysql connection successful";
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
				echo "exec query failed";
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

		function login($acc_login, $acc_pass) {
			echo "checking login ...";
			
			$query  = "SELECT * ";
			$query .= "FROM tbl_accounts ";
			$query .= "WHERE acc_login = '$acc_login' ";
			$query .= "AND acc_pass = '" . sha1($acc_pass) . "';";

			$results = $this->conn->query($query);

			if (!$results) {
				echo "login info not found";
				return FALSE;
			}
			
			if ($results->num_rows <= 0) {
				echo "login info not found";
				return FALSE;
			}
			
			echo "login successful";
			return TRUE;
		}

		function insert($acc_name, $acc_login, $acc_pass) {
			$query  = "INSERT INTO tbl_accounts (acc_name, acc_login, acc_pass)";
			$query .= "VALUES ($acc_name, $acc_login, $acc_pass);";

			$this->conn->query($query);
		}

		function delete ($acc_name, $acc_login, $acc_pass) {
			echo "not developed yet";
		}
	}
?>
