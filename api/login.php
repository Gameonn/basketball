<?php
//this is an api to login users

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");
$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$email=$_REQUEST['email'];
$password=$_REQUEST['password'];


// +-----------------------------------+
// +  Mandatory Parameters				   +
// +-----------------------------------+
if(!($email && $password)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
// +-----------------------------------+
// + STEP 3: perform operations		   +
// +-----------------------------------+

	global $conn; //connection object
	

	//checking validity of the user	
	$sth=$conn->prepare("SELECT * FROM users WHERE email=:email and password=:password");
	$sth->bindValue("email",$email);	
	$sth->bindValue("password",md5($password));
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_id=$result[0]['id'];
	if(count($result)){
	
	//Users::updateFavTeam($user_id,$favorite_nba_team);
		
	//fetching user data
	$data= Users::user_login($email);
	
	
	$success="1";
	$msg="Login Successful";
	//$login_parameter='2';
	}
	else{
		$success='0';
		$msg="Invalid Email or Password";
	}	
}

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>