<?php
//this is an api to start a new event

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

//competition_level 1-non-competitive 2-competitive 3-pro_league
//league_level 1-practice 2-pickup_game 3-tournament 4-season_comming_soon 5- season_playoffs
//event_status 1-public 2-private 3-open on request

$token=$_REQUEST['token'];
$competition_level=$_REQUEST['competition_level'];
$league_level=$_REQUEST['league_level'];
$event_status=$_REQUEST['event_status'];


if(!($token && $competition_level && $league_level && $event_status)){
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
		
	
	$sql="INSERT into event(id,user_id,competition_level,league_level,public_status,created_on) values(DEFAULT,:user_id,:competition_level,:league_level,:public_status,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('competition_level',$competition_level);
	$sth->bindValue('league_level',$league_level);
	$sth->bindValue('public_status',$event_status);
	try{ 
	$sth->execute();
	$event_id=$conn->lastInsertId();
	$success="1";
	$msg="Event Added";	
		
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
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"event_id"=>$event_id));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>