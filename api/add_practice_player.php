<?php
//this is an api to add team players for practice

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
$practice_id=$_REQUEST['practice_id'];
$player_id=$_REQUEST['player_id'];
$player_profile=$_REQUEST['player_profile']?$_REQUEST['player_profile']:""; 
$player_status=$_REQUEST['player_status']?$_REQUEST['player_status']:0;
$arr_size = sizeof($player_id);

//practice advanced setting options
$conversation_status=$_REQUEST['conversation_status']?$_REQUEST['conversation_status']:1;
$auto_cancel=$_REQUEST['auto_cancel']?$_REQUEST['auto_cancel']:0;
$cancel_type=$_REQUEST['cancel_type']?$_REQUEST['cancel_type']:'';
$high_temp=$_REQUEST['high_temp']?$_REQUEST['high_temp']:"";
$low_temp=$_REQUEST['low_temp']?$_REQUEST['low_temp']:"";
$tablename="event_practice_settings";

if(!($token && $practice_id && $player_id)){
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
	
	GeneralFunctions::setGameSettings($tablename,$practice_id,$conversation_status,$auto_cancel,$cancel_type,$high_temp,$low_temp);
	
	for($i=0; $i<$arr_size;$i++){
	
	$sql="SELECT * FROM event_practice_players WHERE practice_id=:practice_id and user_id=:player_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('practice_id',$practice_id);
	$sth->bindValue('player_id',$player_id[$i]);
	try{$sth->execute();}
	catch(Exception $e){}
	$tour[$i]=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(!count($tour[$i])){	

	$sql="INSERT into event_practice_players(id,practice_id,user_id,player_profile,player_status,accept_status,created_on) 
		  VALUES(DEFAULT,:practice_id,:player_id,:player_profile,0,0,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('practice_id',$practice_id);
	$sth->bindValue('player_id',$player_id[$i]);
	$sth->bindValue('player_profile',$player_profile);
	//$sth->bindValue('player_status',$player_status);
	try{ 
	$sth->execute();
	$success="1";
	$msg="Practice Game Players Added";	
	}
	catch(Exception $e){}
	
	}
	else{
	$success='0';
	$msg="Already Added";
	}
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