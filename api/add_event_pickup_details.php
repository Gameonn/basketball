<?php
//this is an api to add event pickup details for games

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

//event_type 1-practice 2-pickup_game 3-tournament


$token=$_REQUEST['token'];
$event_id=$_REQUEST['event_id'];
$event_type=$_REQUEST['event_type'];// practice,tournament or pickup
$event_name=$_REQUEST['event_name'];// same as tournament name or pickup game name or practice description

//pickup game details
$location_id=$_REQUEST['location_id'];
$start_date=$_REQUEST['start_date'];
$start_time=$_REQUEST['start_time'];
$repeat_game=$_REQUEST['repeat_game'];// weekly,by-weekly,monthly
$repeat_count=$_REQUEST['repeat_count'];


if(!($token && $event_id && $event_name && $start_date && $location_id && $start_time)){
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
	
	$sql="SELECT * FROM event_pickup_game_details WHERE user_id=:user_id and event_id=:event_id and pickup_game_name=:pickup_game_name";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('event_id',$event_id);
	$sth->bindValue('pickup_game_name',$event_name);
	try{$sth->execute();}
	catch(Exception $e){}
	$tour=$sth->fetchAll(PDO::FETCH_ASSOC);
	
		
	if(!count($tour)){	
	$sql="INSERT into event_pickup_game_details(id,event_id,user_id,pickup_game_name,repeat_game,repeat_count,created_on) 
		   VALUES(DEFAULT,:event_id,:user_id,:event_name,:repeat_game,:repeat_count,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('event_id',$event_id);
	$sth->bindValue('event_name',$event_name);
	$sth->bindValue('repeat_game',$repeat_game);
	$sth->bindValue('repeat_count',$repeat_count);
	try{ 
	$sth->execute();
	$pickup_game_id=$conn->lastInsertId();
	$success="1";
	$msg="Pickup Match Added";	
	}
	catch(Exception $e){}
	
	if($repeat_count>=2){
	
	for($i=0;$i<$repeat_count;$i++){
	$start_date[0]=$start_date;
	if($i>0){
	if($repeat_game=='W')
	$start_date = gmdate('Y-m-d',strtotime(date("Y-m-d", strtotime($start_date)) . " +7 days"));
	elseif($repeat_game=='B')
	$start_date = gmdate('Y-m-d',strtotime(date("Y-m-d", strtotime($start_date)) . " +15 days"));
	else	
	$start_date = gmdate('Y-m-d',strtotime(date("Y-m-d", strtotime($start_date)) . " +30 days"));
	}

	$sql="INSERT into event_pickup_game_scheduling(id,pickup_game_id,location_id,pickup_game_date,pickup_game_time,created_on) 
		   VALUES(DEFAULT,:pickup_game_id,:location_id,:start_date,:start_time,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('pickup_game_id',$pickup_game_id);
	$sth->bindValue('location_id',$location_id);
	$sth->bindValue('start_date',$start_date);
	$sth->bindValue('start_time',$start_time);
	try{ $sth->execute();}
	catch(Exception $e){}
	}
	
	}
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