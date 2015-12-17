<?php
//this is an api to add nba team

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

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
$team_name=$_REQUEST['team_name'];//same as nba team name
$team_logo=$_REQUEST['team_logo'];// team logo image

if(!($token && $team_name && $team_logo)){
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
		
	if($team_logo){
	$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$team_logo['name']));
			if(@move_uploaded_file($team_logo['tmp_name'], "../uploads/$randomFileName")){
				$logo=$randomFileName;
		}
	}
	else{
	$logo="";
	}	
		
	$sql="SELECT * FROM nba_team_list WHERE team_name=:team_name";
	$sth=$conn->prepare($sql);
	$sth->bindValue('team_name',$team_name);
	try{$sth->execute();}
	catch(Exception $e){}
	$team=$sth->fetchAll(PDO::FETCH_ASSOC);
	
		
	if(!count($team)){	
	$sql="INSERT into nba_team_list(id,team_name,team_logo,created_on) values(DEFAULT,:team_name,team_logo,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('team_name',$team_name);
	$sth->bindValue('team_logo',$team_logo);
	try{ 
	$sth->execute();
	$success="1";
	$msg="NBA Team Added";	
		
	}
	catch(Exception $e){}
	}
	else{
	
	$success='0';
	$msg="ALready Existing";
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