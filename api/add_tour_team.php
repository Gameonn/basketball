<?php
//this is an api to add tournament team for games

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");
error_reporting(0);
//random file name generator
function randomFileNameGenerator($prefix){
	$r=substr(str_replace(".","",uniqid($prefix,true)),0,20);
	if(file_exists("../uploads/$r")) randomFileNameGenerator($prefix);
	else return $r;
}


$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+

$token=$_REQUEST['token'];
$tour_id=$_REQUEST['tournament_id'];
$team_name=$_REQUEST['team_name'];
$team_logo=$_FILES['team_logo'];
$team_color_home=$_REQUEST['team_color_home'];
$team_color_away=$_REQUEST['team_color_away'];
$player_count=$_REQUEST['player_count'];


if(!($token && $tour_id && $team_name && $team_logo && $player_count)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
// +-----------------------------------+
// + STEP 3: perform operations		   +
// +-----------------------------------+

	$user_id=Users::getUserId($token);
	
	if($team_logo){
	$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$team_logo['name']));
			if(@move_uploaded_file($team_logo['tmp_name'], "../uploads/$randomFileName")){
				$logo=$randomFileName;
		}
	}
	else{
	$logo="";
	}
	
	if($user_id){
	
	$sql="SELECT * FROM event_tournament_team WHERE tour_id=:tour_id and team_name=:team_name";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('team_name',$team_name);
	try{$sth->execute();}
	catch(Exception $e){}
	$tour=$sth->fetchAll(PDO::FETCH_ASSOC);
	
		
	if(!count($tour)){	
	$sql="INSERT into event_tournament_team(id,tour_id,team_name,team_logo,team_color_home,team_color_away,player_count,created_on) 
		VALUES(DEFAULT,:tour_id,:team_name,:team_logo,:team_color_home,:team_color_away,:player_count,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('team_name',$team_name);
	$sth->bindValue('team_logo',$logo);
	$sth->bindValue('team_color_away',$team_color_away);
	$sth->bindValue('team_color_home',$team_color_home);
	$sth->bindValue('player_count',$player_count);
	try{ 
	$sth->execute();
	$tour_id=$conn->lastInsertId();
	$success="1";
	$msg="Tournament Team Added";	
		
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
echo json_encode(array("success"=>$success,"msg"=>$msg,"team_id"=>$team_id));
}
else*/
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>