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
$team_id=$_REQUEST['team_id'];
$tour_id=$_REQUEST['tour_id'];

if(!($token && $team_id && $tour_id)){
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
	
	$sth=$conn->prepare("DELETE FROM event_tournament_team where id=:id and tour_id=:tour_id");
	$sth->bindValue("id",$team_id);
	$sth->bindValue('tour_id',$tour_id);
	try{$sth->execute();
	$success='1';
	$msg="Tournament Team Deleted";
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