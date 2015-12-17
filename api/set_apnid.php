<?php
//setting the apn id for IOS device for push notifications

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

	$token=$_REQUEST['token']; 
	$apn_id = $_REQUEST['apn_id'];
	
	if (!empty($token) && !empty($apn_id)) {
		$user_id=Users::getUserId($token);
		if (!empty($user_id)){
			
			$sql="update users set apn_id='' where apn_id=:apn_id";
			$sth=$conn->prepare($sql);
			$sth->bindValue('apn_id',$apn_id);
			try{$sth->execute();}
			catch(Exception $e){}
			
			$sql="UPDATE `users` SET `apn_id`=:apn_id WHERE token=:token";
			$stmt=$conn->prepare($sql);
			$stmt->bindValue('apn_id', $apn_id);
			$stmt->bindValue('token', $token);
			try{$stmt->execute();
			$success="1";
			$msg="apn_id is updated successfully!";
			}
			catch(PDOException $e){}
			
		}
		else{
			$success="0";
			$msg="Token Expired!";
		}
	}
	else{
		$success="0";
		$msg="Incomplete Parameters!";
	}
		echo json_encode(array("success" => $success, "msg" => $msg));
?>