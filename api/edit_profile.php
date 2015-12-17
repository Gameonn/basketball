<?php
//this is an api to edit user profile

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
$first_name=$_REQUEST['first_name']?$_REQUEST['first_name']:'';
$last_name=$_REQUEST['last_name']?$_REQUEST['last_name']:'';
$favorite_nba_team=$_REQUEST['favorite_nba_team']?$_REQUEST['favorite_nba_team']:'';
$emergency_contact=$_REQUEST['emergency_contact']?$_REQUEST['emergency_contact']:'';
$gender=$_REQUEST['gender']?$_REQUEST['gender']:'';
$desired_position=$_REQUEST['desired_position']?$_REQUEST['desired_position']:'';
$jersey_number=$_REQUEST['jersey_number']?$_REQUEST['jersey_number']:'';
$height=$_REQUEST['height']?$_REQUEST['height']:'';
$address=$_REQUEST['address']?$_REQUEST['address']:'';
$inspiring_players=$_REQUEST['inspiring_players']?$_REQUEST['inspiring_players']:'';


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
	$fbid=Users::getUserfbId($token);
	
	if($user_id){
	
	if($profile_pic_path)
	$sth=$conn->prepare("UPDATE users set first_name=:first_name,last_name=:last_name,emergency_contact=:emergency_contact,desired_position=:desired_position,gender=:gender,jersey_number=:jersey_number,height=:height,address=:address,favorite_nba_team=:favorite_nba_team,inspire_desc=:inspiring_players,profile_pic=:profile_pic where token=:token");
	else
	$sth=$conn->prepare("UPDATE users set first_name=:first_name,last_name=:last_name,emergency_contact=:emergency_contact,desired_position=:desired_position,gender=:gender,jersey_number=:jersey_number,height=:height,address=:address,favorite_nba_team=:favorite_nba_team,inspire_desc=:inspiring_players where token=:token");
	
	$sth->bindValue('token',$token);
	$sth->bindValue("desired_position",$desired_position);
	$sth->bindValue("first_name",$first_name);
	$sth->bindValue("last_name",$last_name);
	$sth->bindValue("emergency_contact",$emergency_contact);
	if($profile_pic_path) $sth->bindValue("profile_pic",$profile_pic_path);
	$sth->bindValue('gender',$gender);
	$sth->bindValue("jersey_number",$jersey_number);
	$sth->bindValue("favorite_nba_team",$favorite_nba_team);
	$sth->bindValue("height",$height);
	$sth->bindValue("address",$address);
	$sth->bindValue("inspiring_players",$inspiring_players);
	try{$sth->execute();
	$success="1";
	$msg="User Info Updated";
	$data= Users::fbsigninNew($fbid,'facebook');
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