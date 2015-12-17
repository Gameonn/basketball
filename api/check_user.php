<?php
//this is an api to check user registration status

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");
error_reporting(0);

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$fbid=$_REQUEST['fbid'];
$type=$_REQUEST['type'];//type --1 facebook else google

global $conn;
// +-----------------------------------+
// +  Mandatory Parameters				   +
// +-----------------------------------+

if(!($fbid )){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 	
		
		if($type==1)
		$user_exists=Users::checkfb($fbid);//checking the existence of fbid entered
		else
		$user_exists=Users::checkgoogle($fbid);//checking the existence of googleid entered
		
	//updating user details for existing email
		if($user_exists){
			$success="1";
			$msg="User Exists";
					
		}
		else{
		$success='0';
		$msg="User Not Registered";
		
		}
	
	
}

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+

echo json_encode(array("success"=>$success,"msg"=>$msg));
?>