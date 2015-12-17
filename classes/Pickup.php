<?php
class Pickup
{
	public static function getImagePath($file_name){
		if(!empty($file_name)){
			return BASE_PATH."uploads/".$file_name;//original path
				//return BASE_PATH."timthumb.php?src=uploads/".$file_name; //timthumb path
		}
		else{
				return BASE_PATH."uploads/no_image.png";
				//return BASE_PATH."timthumb.php?src=uploads/default_256.png"; 	//timthumb path
				
		}
	}
	
	public static function getBasePath(){
	return BASE_PATH."/timthumb.php?src=uploads/";
	}
	
	public static function getUserId($token){
	global $conn;
	
	$sql="select * from users where users.token=:token";
	$sth=$conn->prepare($sql);
	$sth->bindValue('token',$token);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_id=$result[0]['id'];
	
	return $user_id;
	}
	
		
	public static function get_push_ids($user_id){
	global $conn;
	
	$sql="select users.* from users where users.id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
		
	return $result;
	}
	
	public static function setGameSettings($tablename,$game_id,$conversation_status,$auto_cancel,$cancel_type,$high_temp,$low_temp){
	
	global $conn;
	
	$sql="SELECT * FROM $tablename WHERE game_id=:pickup_game_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('game_id',$game_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$tour=$sth->fetchAll(PDO::FETCH_ASSOC);
	
		
	if(!count($tour)){	
	$sql="INSERT into $tablename(id,game_id,conversation_status,auto_cancel,cancel_type,high_temp,low_temp,created_on) 
		VALUES(DEFAULT,:game_id,:conversation_status,:auto_cancel,:cancel_type,:high_temp,:low_temp,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('game_id',$game_id);
	$sth->bindValue('conversation_status',$conversation_status);
	$sth->bindValue('auto_cancel',$auto_cancel);
	$sth->bindValue('cancel_type',$cancel_type);
	$sth->bindValue('high_temp',$high_temp);
	$sth->bindValue('low_temp',$low_temp);
	try{ 
	$sth->execute();
	$game_settings_id=$conn->lastInsertId();
	}
	catch(Exception $e){}
	}
	
	return true;
	}
	
	
	public static function generateRandomString($length = 10){
		$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++){
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
}
?>
