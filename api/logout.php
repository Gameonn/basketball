<?php
//this is an api to logout users

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+

require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

$success=$msg="0";$data=array();

// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
$token=$_REQUEST['token'];

if(!($token){
	$success="0";
	$msg="Incomplete Parameters";
}
else{
	$uid=Users::getUserId($token);

	if($uid){
		$sql="UPDATE users set token='' where id=:id";
		$sth=$conn->prepare($sql);
		$sth->bindValue("id",$uid);
		try{
		$sth->execute();
		$success="1";
		$msg="Logout Successful";
		}
		catch(Exception $e){}
		
	}
	else{
		$success="0";
		$msg="Token Expired";
	}
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
/*if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else*/
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>