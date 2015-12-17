<?php
//this is an api to register users using google on the server

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

//date conversion function
function convertDate($date) {
	$date = preg_replace('/\D/','/',$date);
	return date('Y-m-d',strtotime($date));
}


$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+
$googleid=$_REQUEST['google_id'];
$email=$_REQUEST['email']?$_REQUEST['email']:"";
$first_name=$_REQUEST['first_name']?$_REQUEST['first_name']:"";
$last_name=$_REQUEST['last_name']?$_REQUEST['last_name']:"";
$company_name=$_REQUEST['company_name']?$_REQUEST['company_name']:"";
$address=$_REQUEST['address']?$_REQUEST['address']:"";
$account_type=$_REQUEST['account_type'];
$favorite_nba_team=$_REQUEST['favorite_nba_team']?$_REQUEST['favorite_nba_team']:"";
$type='google';
$signup_parameter=$_REQUEST['signup_parameter'];

global $conn;
// +-----------------------------------+
// +  Mandatory Parameters				   +
// +-----------------------------------+
if(!($googleid )){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 	
	
	//checking the existence of googleid entered
	$googleid_exists=Users::checkgoogle($googleid);
	
	//updating user details for existing email
	if($googleid_exists){
	
	$user_id=Users::checkuserid($googleid);
	//Users::updateFavTeam($user_id,$favorite_nba_team);
	
	$data= Users::fbsignin($googleid,$type);
		
		if($signup_parameter==1){
		$user_account_type=$data['user_type'];
			if($user_account_type==$account_type){
				$success='1';
				$msg="Login Successful";
			}
			else{
				$success='0';
				$msg="You are registered with different account type";
			}
		}
		else{
		$success="1";
		$msg="Login Successful";
		
		}
	$login_parameter='2';
	}
	//New User Entry
	else{	
		
		if(!$signup_parameter){
			$success='0';
			$msg="User not Registered";
		
		}
		else{
		$profile_pic_name="default_user.png";
					
		$nba_team_id=Users::getFavoriteTeamId($favorite_nba_team);
					
		//generating a new random token for that user 
		$code= GeneralFunctions::generateRandomString(12);
		
		$sql="INSERT into users(id,apn_id,googleid,fbid,first_name,last_name,email,password,token,user_type,desired_position,gender,jersey_number,height,weight,address,inspire_desc,profile_pic,favorite_nba_team,age,created_on) 
		  VALUES(DEFAULT,0,:googleid,'',:first_name,:last_name,:email,'',:token,:account_type,'','','','','','','',:profile_pic_name,:favorite_nba_team,'',UTC_TIMESTAMP())";
		$sth=$conn->prepare($sql);
		$sth->bindValue("email",$email);
		$sth->bindValue("googleid",$googleid);
		$sth->bindValue("first_name",$first_name);
		$sth->bindValue("last_name",$last_name);
		$sth->bindValue("account_type",$account_type);
		$sth->bindValue("profile_pic_name",$profile_pic_name);
		$sth->bindValue("favorite_nba_team",$nba_team_id);
		$sth->bindValue("token",md5($code));
		try{$sth->execute();
		$user_id=$conn->lastInsertId();
		$success='1';
		$msg="User Successfully Registered";
		$data=Users::fbsigninNew($googleid,$type);
		$login_parameter='1';
		}
		catch(Exception $e){}	
		}
		
		}	
}

// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data,'login_parameter'=>$login_parameter));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>