<?php
//this is an api to edit user profile after login

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
$profile_pic=$_FILES['profile_pic'];
$gender=$_REQUEST['gender']?$_REQUEST['gender']:'';
$desired_position=$_REQUEST['desired_position']?$_REQUEST['desired_position']:'';
$second_choice=$_REQUEST['second_choice']?$_REQUEST['second_choice']:'';
$jersey_number=$_REQUEST['jersey_number']?$_REQUEST['jersey_number']:'';
$height=$_REQUEST['height']?$_REQUEST['height']:'';
$address=$_REQUEST['address']?$_REQUEST['address']:'';
$inspiring_players=$_REQUEST['inspiring_players']?$_REQUEST['inspiring_players']:'';
$favorite_nba_team=$_REQUEST['favorite_nba_team']?$_REQUEST['favorite_nba_team']:'';
$emergency_name=$_REQUEST['emergency_name']?$_REQUEST['emergency_name']:'';
$emergency_relation=$_REQUEST['emergency_relation']?$_REQUEST['emergency_relation']:'';
$emergency_phone=$_REQUEST['emergency_phone']?$_REQUEST['emergency_phone']:'';
$emergency_blood_group=$_REQUEST['emergency_blood_group']?$_REQUEST['emergency_blood_group']:'';


if(!($token)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
// +-----------------------------------+
// + STEP 3: perform operations		   +
// +-----------------------------------+

	if($profile_pic){
	$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$profile_pic['name']));
			if(@move_uploaded_file($profile_pic['tmp_name'], "../uploads/$randomFileName")){
				$profile_pic_path=$randomFileName;
		}
	}
	else{
	$profile_pic_path="";
	}
	
	$user_id=Users::getUserId($token);	

	if($user_id){
	$nba_team_id=Users::getFavoriteTeamId($favorite_nba_team);
	
	if($profile_pic_path)
	$sth=$conn->prepare("UPDATE users set desired_position=:desired_position,second_choice=:second_choice,gender=:gender,jersey_number=:jersey_number,height=:height,address=:address,favorite_nba_team=:favorite_nba_team,inspire_desc=:inspiring_players,profile_pic=:profile_pic,emergency_name=:emergency_name,emergency_phone=:emergency_phone,emergency_blood_group=:emergency_blood_group,emergency_relation=:emergency_relation where token=:token");
	else
	$sth=$conn->prepare("UPDATE users set desired_position=:desired_position,second_choice=:second_choice,gender=:gender,jersey_number=:jersey_number,height=:height,address=:address,favorite_nba_team=:favorite_nba_team,inspire_desc=:inspiring_players,emergency_name=:emergency_name,emergency_phone=:emergency_phone,emergency_blood_group=:emergency_blood_group,emergency_relation=:emergency_relation where token=:token");
	
	$sth->bindValue('token',$token);
	$sth->bindValue("desired_position",$desired_position);
	$sth->bindValue("second_choice",$second_choice);
	if($profile_pic_path) $sth->bindValue("profile_pic",$profile_pic_path);
	$sth->bindValue('gender',$gender);
	$sth->bindValue("jersey_number",$jersey_number);
	$sth->bindValue("favorite_nba_team",$nba_team_id);
	$sth->bindValue("height",$height);
	$sth->bindValue("address",$address);
	$sth->bindValue("inspiring_players",$inspiring_players);
	$sth->bindValue("emergency_name",$emergency_name);
	$sth->bindValue("emergency_relation",$emergency_relation);
	$sth->bindValue("emergency_phone",$emergency_phone);
	$sth->bindValue("emergency_blood_group",$emergency_blood_group);
	try{$sth->execute();
	$success="1";
	$msg="User Info Updated";
	$data= Users::getUserProfile($user_id);
	}
	catch(Exception $e){}
	}	
	else{
	$success='0';
	$msg="Invalid Token";
	}
}
// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>