<?php

// Inialize session
session_start();

// Include database connection settings
include('dbconnect.php');

// Retrieve username and password from database according to user's input
$login = mysql_query("SELECT * FROM User WHERE (username = '" . mysql_real_escape_string($_POST['username']) . "') and (password = '" . mysql_real_escape_string($_POST['password']) . "')");

$result = mysql_query("SELECT * FROM User WHERE (username = '" . mysql_real_escape_string($_POST['username']) . "') and (password = '" . mysql_real_escape_string($_POST['password']) . "')");


if (!$result) 
{
	echo "Could not successfully run query ($sql) from DB: " . mysql_error();
	exit;
}

else if (mysql_num_rows($result) == 0) 
{
	//echo "No Record of such User Found";
	if(isset($_COOKIE['valid']))
		setcookie("valid", "", time()-3600);
	//$str="Password updated successfully";
	header( 'Location: index.php?a=Wrong Credentials' ) ;
	//exit;
}
	
$row2 = mysql_fetch_array($result);
$logged_user=$row2['type'];
if ($logged_user != 3 and $logged_user != 4)
{
	
	if(mysql_num_rows($login)==1){

		// Set username session variable
		$row = mysql_fetch_assoc($login);
		$userid = $row["userid"];
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['userid'] = $userid;
		$_SESSION['userType'] = $row['type'];
		if($_SESSION['userType']==0) {
			$_SESSION['isProfessor'] = true;
			$_SESSION['isAI'] = false;
			$_SESSION['isAdmin'] = true;
		}
		else if($_SESSION['userType']==1) {
			$_SESSION['isProfessor'] = false;
			$_SESSION['isAI'] = true;
			$_SESSION['isAdmin'] = true;
		} else {
			$_SESSION['isProfessor'] = false;
			$_SESSION['isAI'] = false;
			$_SESSION['isAdmin'] = false;
		}
	
	
		$userInfoMap = array();
		$userInfoMap['username'] = $_SESSION['username'];
		$userInfoMap['userid'] = $_SESSION['userid'];
		$userInfoMap['userType'] = $_SESSION['userType'];
		$userInfoMap['isProfessor'] = $_SESSION['isProfessor'];
		$userInfoMap['isAI'] = $_SESSION['isAI'];
		$userInfoMap['isAdmin'] = $_SESSION['isAdmin'];
		$_SESSION['userInfoMap'] = $userInfoMap;
	
	
	 	
	

		//Update lastlogin
		date_default_timezone_set('America/Indianapolis');
		$currDateTime = date('Y-m-d H:i:s');
		$updateQuery = "UPDATE User SET lastlogin = '$currDateTime' WHERE userid = $userid ";
		$updateResult = mysql_query($updateQuery);
		if($updateQuery)
		{
			//update success
		}
		else
		{
			//update fiailed
		}

		
	
		header('Location: categories.php');
	}
}

else {
	// Jump to login page
	if(isset($_COOKIE['valid']))
		setcookie("valid", "", time()-3600);
	//$str="Password updated successfully";
	header( 'Location: index.php?a=You are blocked. Please contact the Instructor' ) ;
	//header('Location: index.php');

}

?>