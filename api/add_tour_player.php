<?php
//this is an api to add team players for tournament

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
$tour_id=$_REQUEST['tour_id'];
$team_id=$_REQUEST['team_id'];
$player_id=$_REQUEST['player_id'];
$player_profile=$_REQUEST['player_profile']?$_REQUEST['player_profile']:""; 
$player_status=$_REQUEST['player_status']?$_REQUEST['player_status']:0;



if(!($token && $tour_id && $team_id && $player_id)){
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
	
	$sql="SELECT * FROM event_tournament_team_players WHERE tour_id=:tour_id and team_id=:team_id and user_id=:player_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('team_id',$team_id);
	$sth->bindValue('player_id',$player_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$tour=$sth->fetchAll(PDO::FETCH_ASSOC);
	
		
	if(!count($tour)){	
	$sql="INSERT into event_tournament_team_players(id,tour_id,team_id,user_id,player_profile,accept_status,player_status,created_on) 
		  VALUES(DEFAULT,:tour_id,:team_id,:player_id,:player_profile,0,:player_status,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('team_id',$team_id);
	$sth->bindValue('player_id',$player_id);
	$sth->bindValue('player_profile',$player_profile);
	$sth->bindValue('player_status',$player_status);
	try{ 
	$sth->execute();
	$tour_id=$conn->lastInsertId();
	$success="1";
	$msg="Tournament Team Player Added";	
	}
	catch(Exception $e){}
	}
	else{
	$success='0';
	$msg="Already Added";
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