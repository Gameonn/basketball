<?php
//this is an api to register users on the server

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once('../classes/AllClasses.php');

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

$email=$_REQUEST['email'];
$first_name=$_REQUEST['first_name'];
$last_name=$_REQUEST['last_name']?$_REQUEST['last_name']:"";
$company_name=$_REQUEST['company_name']?$_REQUEST['company_name']:"";
$address=$_REQUEST['address']?$_REQUEST['address']:"";
$password=isset($_REQUEST['password']) && $_REQUEST['password'] ? $_REQUEST['password'] : null;
$favorite_nba_team=$_REQUEST['favorite_nba_team']?$_REQUEST['favorite_nba_team']:"";
$account_type=$_REQUEST['account_type'];

global $conn; //connection object


/* 		******MANDATORY PARAMETERS******** 		*/
if(!($email && $password && $first_name && $account_type)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 

	/*if($profile_pic){
	$randomFileName=randomFileNameGenerator("Img_").".".end(explode(".",$profile_pic['name']));
			if(@move_uploaded_file($profile_pic['tmp_name'], "../uploads/$randomFileName")){
				$profile_pic_name=$randomFileName;
		}
	}
	else{
	$profile_pic_name="no_image.png";
	}*/
	

	/*	***** CHECK WHETHER EMAIL ALREADY EXISTS ******			*/		
	$sth=$conn->prepare("SELECT * from users where email=:email");
	$sth->bindValue("email",$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($result)){
		$success="0";
		$u=strcasecmp($email,$result[0]['email']);
		if(!$u)
		$msg="Email is already taken";
		}
		
	/* 	******* NEW USER ENTRY ****		*/	
	else{	
	
	$nba_team_id=Users::getFavoriteTeamId($favorite_nba_team)?Users::getFavoriteTeamId($favorite_nba_team):1;
	
	
	$code= GeneralFunctions::generateRandomString(12);
	$sql="INSERT into users(id,apn_id,googleid,fbid,first_name,last_name,email,password,token,user_type,desired_position,second_choice,company_name,gender,jersey_number,height,weight,address,inspire_desc,profile_pic,favorite_nba_team,age,created_on) 
		  VALUES(DEFAULT,0,'','',:first_name,:last_name,:email,:password,:token,:account_type,'','',:company_name,'','','','',:address,'','default_user.png',:favorite_nba_team,'',UTC_TIMESTAMP())";
		$sth=$conn->prepare($sql);
		$sth->bindValue("email",$email);
		$sth->bindValue("first_name",$first_name);
		$sth->bindValue("last_name",$last_name);
		$sth->bindValue("company_name",$company_name);
		$sth->bindValue("address",$address);
		$sth->bindValue("account_type",$account_type);
		$sth->bindValue("favorite_nba_team",$nba_team_id);
		$sth->bindValue("token",md5($code));
		$sth->bindValue("password",md5($password));
		try{$sth->execute();
		$user_id=$conn->lastInsertId();
		$success='1';
		$msg="User Successfully Registered";
		$data=Users::user_after_signup($email,md5($code));
		}
		catch(Exception $e){}
		}
	
	}	


// +-----------------------------------+
// + STEP 4: send json data		   +
// +-----------------------------------+


if($success=='1'){
echo json_encode(array("success"=>$success,"msg"=>$msg,"data"=>$data));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>