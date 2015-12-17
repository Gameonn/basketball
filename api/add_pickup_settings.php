<?php
//this is an api to add pickup settings for games

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
$pickup_game_id=$_REQUEST['pickup_game_id'];

//pickup options
$conversation_status=$_REQUEST['conversation_status']?$_REQUEST['conversation_status']:1;
$auto_cancel=$_REQUEST['auto_cancel']?$_REQUEST['auto_cancel']:0;
$cancel_type=$_REQUEST['cancel_type']?$_REQUEST['cancel_type']:'';
$high_temp=$_REQUEST['high_temp'];
$low_temp=$_REQUEST['low_temp'];



if(!($token && $pickup_game_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
// +-----------------------------------+
// + STEP 3: perform operations		   +
// +-----------------------------------+

	$user_id=Users::getUserId($token);
	
	if($user_id){
	
	$sql="SELECT * FROM event_pickup_game_settings WHERE game_id=:pickup_game_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('pickup_game_id',$pickup_game_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$tour=$sth->fetchAll(PDO::FETCH_ASSOC);
	
		
	if(!count($tour)){	
	$sql="INSERT into event_pickup_game_settings(id,game_id,conversation_status,auto_cancel,cancel_type,high_temp,low_temp,created_on) 
		VALUES(DEFAULT,:pickup_game_id,:conversation_status,:auto_cancel,:cancel_type,:high_temp,:low_temp,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('pickup_game_id',$pickup_game_id);
	$sth->bindValue('conversation_status',$conversation_status);
	$sth->bindValue('auto_cancel',$auto_cancel);
	$sth->bindValue('cancel_type',$cancel_type);
	$sth->bindValue('high_temp',$high_temp);
	$sth->bindValue('low_temp',$low_temp);
	try{ 
	$sth->execute();
	$tour_id=$conn->lastInsertId();
	$success="1";
	$msg="Pickup Game Settings Added";	
	}
	catch(Exception $e){}
	}
	else{
	$success='0';
	$msg="ALready Created";
	}
	}
	else{
	$success='0';
	$msg="Token Expired";
	}	
}
// +-----------------------------------+
// + STEP 4: send json data		    +
// +-----------------------------------+
/*if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"court_id"=>$court_id));
}
else*/
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>