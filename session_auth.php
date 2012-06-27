<?php

	require("db_functions.php");

	/* authentication function: this is called by
	   each page to ensure that there is an authenticated user
	*/
	function auth($reqlevel, $logged_in_user='', $password='') {
		
		//start/continue the session		
		session_start();
		if (!empty($_SESSION['logged_in_user']))
				return true;

		$check = !empty($logged_in_user);
		
		if ($check) {
			//echo " not empty!";
			$dbh = connect_db();
			list($qh,$num) = query_db("select password , nom , id , level ".
			                         "from users where loggin='$logged_in_user'");
			$data = result_db($qh);
			//echo "db->".$num." (".$data[password].") ".$data[nom]."  pour ".$password."=".md5($password);
			//is the password correct 
			if ($data[password] != md5($password))
				//pas le bon ppasswd
				return 0;//false;
			else if ($reqlevel > $data['level'])
				//pas le niveau d'autorisation requis
				return 0;//false;
			
			else {	///tout ok
				//set session variables
				$_SESSION['logged_in_user'] = $logged_in_user;
     				$_SESSION['user_id'] = $data['id'];
				$_SESSION['level'] = $data['level'];
				return $data['level'];//true
			}
		}
		else {
			//unset all the variables
			session_unset();

			//destroy the session
			session_destroy();
						
			return 0; ///false;
		}
	}
////////////////////////////////////////////////////////////////////////////

 function logout(){	
	//continue the session
	session_start();
	//unset all the variables
	session_unset();

	//destroy the session
	session_destroy();

}

?>