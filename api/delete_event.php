<?php
//this is an api to delete an event

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
$event_id=$_REQUEST['event_id'];

if(!($token && $event_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
// +-----------------------------------+
// + STEP 3: perform operations		   +
// +-----------------------------------+
	
	$user_id=GeneralFunctions::getUserId($token);
	if($user_id){
	
	$sth=$conn->prepare("DELETE FROM event where id=:id and user_id=:user_id");
	$sth->bindValue("id",$event_id);
	$sth->bindValue('user_id',$user_id);
	try{$sth->execute();
	$success='1';
	$msg="Event Deleted";
	}
	catch(Exception $e){}
			
	}
	else{
	$success='0';
	$msg="Token Expired";
	}
}
// +-----------------------------------+
// + STEP 4: send json data		    +
// +-----------------------------------+

echo json_encode(array("success"=>$success,"msg"=>$msg));
?>