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
		function connect ($conn_link, $port, $db_name, $userName, $password) {
			
		}
		
		/**
		 * executes general mysql query
		 */
		function execQuery ($queryString) {
			
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
			
		}
		
		function delete ($acc_name, $acc_login, $acc_pass) {
			
		}
	}
?> 
