<?php
//this is an api to add tournament description for games

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

//league_level 1-practice 2-pickup_game 3-tournament
//tournament_type 1-single_elimination, 2-double_elimination, 3-round_robin


$token=$_REQUEST['token'];
$competition_level=$_REQUEST['competition_level'];
$league_level=$_REQUEST['league_level'];
$event_status=$_REQUEST['event_status']?$_REQUEST['event_status']:'0';
$event_name=$_REQUEST['event_name'];	// same as tournament name or pickup game name or practice description

//tournament options
$tournament_type=$_REQUEST['tournament_type']; //single elimination,double elimination or round robin
$team_count=$_REQUEST['team_count'];
$court_count=$_REQUEST['court_count'];
$start_date=$_REQUEST['start_date'];
$end_date=$_REQUEST['end_date'];


if(!($token && $event_name && $start_date && $end_date && $tournament_type && $competition_level && $league_level && $team_count && $court_count )){
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
	
	$event_id=Tournament::StartEvent($user_id,$competition_level,$league_level,$event_status);
	
	$sql="SELECT * FROM event_tournament WHERE user_id=:user_id and event_id=:event_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('event_id',$event_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$tour=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(!count($tour)){	
	$sql="INSERT into event_tournament(id,user_id,event_id,tournament_name,tournament_type,team_count,court_count,start_date,end_date,created_on) 
		VALUES(DEFAULT,:user_id,:event_id,:tournament_name,:tournament_type,:team_count,:court_count,:start_date,:end_date,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('event_id',$event_id);
	$sth->bindValue('tournament_name',$event_name);
	$sth->bindValue('tournament_type',$tournament_type);
	$sth->bindValue('team_count',$team_count);
	$sth->bindValue('court_count',$court_count);
	$sth->bindValue('start_date',$start_date);
	$sth->bindValue('end_date',$end_date);
	try{ 
	$sth->execute();
	$tour_id=$conn->lastInsertId();
	$success="1";
	$msg="Tournament Added";	
	}
	catch(Exception $e){}
	}
	else{
	$success='0';
	$msg="Already Created";
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
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"tour_id"=>$tour_id,"event_id"=>$event_id));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>