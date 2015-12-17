<?php
//setting the reg id for Android device for push notifications

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

	$token=$_REQUEST['token']; 
	$reg_id = $_REQUEST['reg_id'];
	
	if (!empty($token) && !empty($reg_id)) {
		$user_id=Users::getUserId($token);
		if (!empty($user_id)){
			
			$sql="update users set reg_id='' where reg_id=:reg_id";
			$sth=$conn->prepare($sql);
			$sth->bindValue('reg_id',$reg_id);
			try{$sth->execute();}
			catch(Exception $e){}
			
			$sql="UPDATE `users` SET `reg_id`=:reg_id WHERE token=:token";
			$stmt=$conn->prepare($sql);
			$stmt->bindValue('reg_id', $reg_id);
			$stmt->bindValue('token', $token);
			try{$stmt->execute();
			$success="1";
			$msg="reg_id is updated successfully!";
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