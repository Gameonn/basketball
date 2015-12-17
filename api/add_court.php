<?php
//this is an api to add court for games

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
$location_name=$_REQUEST['location_name'];//same as court name
$location_address=$_REQUEST['location_address'];// court address
$zipcode=$_REQUEST['zipcode'];
$city=$_REQUEST['city'];
$latitude=$_REQUEST['latitude'];
$longitude=$_REQUEST['longitude'];


if(!($token && $location_name && $zipcode && $city && $latitude && $longitude)){
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
		
	$sql="SELECT * FROM event_location WHERE location_name=:location_name and city=:city and zipcode=:zipcode";
	$sth=$conn->prepare($sql);
	$sth->bindValue('location_name',$location_name);
	$sth->bindValue('zipcode',$zipcode);
	$sth->bindValue('city',$city);
	try{$sth->execute();}
	catch(Exception $e){}
	$court=$sth->fetchAll(PDO::FETCH_ASSOC);
	
		
	if(!count($court)){	
	$sql="INSERT into event_location(id,location_name,location_address,zipcode,city,latitude,longitude,created_on) values(DEFAULT,:location_name,:location_address,:zipcode,:city,:latitude,:longitude,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('location_name',$location_name);
	$sth->bindValue('location_address',$location_address);
	$sth->bindValue('zipcode',$zipcode);
	$sth->bindValue('city',$city);
	$sth->bindValue('latitude',$latitude);
	$sth->bindValue('longitude',$longitude);
	try{ 
	$sth->execute();
	$court_id=$conn->lastInsertId();
	$success="1";
	$msg="Court Added";	
		
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

if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"court_id"=>$court_id));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>