<?php
class Users{

	public static function user_after_signup($email,$code){
	
	global $conn;
	$data=array();
	$sql="select * from rc_users where rc_users.email=:email and token=:token and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	$sth->bindValue('token',$code);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
		
	 $data=array(
        "user_id"=>$result[0]['id'],
        "fbid"=>$result[0]['fbid']?$result[0]['fbid']:'',
        "twitter_id"=>$result[0]['twid']?$result[0]['twid']:'',
        "name"=>$result[0]['name']?$result[0]['name']:"",
        "email"=>$result[0]['email']?$result[0]['email']:"",
        "city"=>$result[0]['city']?$result[0]['city']:"",
        "gender"=>$result[0]['gender']?$result[0]['gender']:"",
        "latitude"=>$result[0]['latitude']?$result[0]['latitude']:"",
        "longitude"=>$result[0]['longitude']?$result[0]['longitude']:"",
        "profile_pic"=>$result[0]['image']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
        "access_token"=>$result[0]['token']
        );
	return $data;
	}

	public static function user_login($email){
	
	global $conn;
	$data=array();
	$sql="select * from rc_users where rc_users.email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$token=Users::generateRandomString(12);
	
	$sql="update rc_users set token=:token where rc_users.email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	$sth->bindValue('token',md5($token));
	try{$sth->execute();}
	catch(Exception $e){}
	
	 $data=array(

        "user_id"=>$result[0]['id'],
        "fbid"=>$result[0]['fbid']?$result[0]['fbid']:'',
        "twitter_id"=>$result[0]['twid']?$result[0]['twid']:'',
        "name"=>$result[0]['name']?$result[0]['name']:"",
        "email"=>$result[0]['email']?$result[0]['email']:"",
        "city"=>$result[0]['city']?$result[0]['city']:"",
         "gender"=>$result[0]['gender']?$result[0]['gender']:"",
        "latitude"=>$result[0]['latitude']?$result[0]['latitude']:"",
        "longitude"=>$result[0]['longitude']?$result[0]['longitude']:"",
        "profile_pic"=>$result[0]['image']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
        "access_token"=>md5($token)
			  );

	return $data;
	}

	public static function get_favorites($user_id){
	
	global $conn;
	$data=array();
	$sql="select * from rc_favorites where rc_favorites.user_id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	return $result;
	}

	public static function getUserId($token){
	
	global $conn;
	$data=array();
	$sql="select * from rc_users where rc_users.token=:token";
	$sth=$conn->prepare($sql);
	$sth->bindValue('token',$token);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_id=$result[0]['id'];
	return $user_id;
	}
	
	public static function checkEmail($email){
	
	global $conn;
	$data=array();
	$sql="select * from rc_users where rc_users.email=:email";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_email=$result[0]['email'];
	return $user_email;
	}


	public static function generateRandomString($length = 10){
		$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++){
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	public static function fbsignin($fbid,$email){
	
	global $conn;
	$data=array();
	$sql="select * from rc_users where rc_users.fbid=:fbid and rc_users.email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('fbid',$fbid);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	$token=Users::generateRandomString(12);
	
	$sql="update rc_users set token=:token where rc_users.fbid=:fbid and rc_users.email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('fbid',$fbid);
	$sth->bindValue('email',$email);
	$sth->bindValue('token',md5($token));
	try{$sth->execute();}
	catch(Exception $e){}
	
	 $data=array(
        "user_id"=>$result[0]['id'],
        "fbid"=>$result[0]['fbid'],
        "name"=>$result[0]['name']?$result[0]['name']:"",
        "email"=>$result[0]['email']?$result[0]['email']:"",
        "city"=>$result[0]['city']?$result[0]['city']:"",
        "latitude"=>$result[0]['latitude']?$result[0]['latitude']:"",
        "longitude"=>$result[0]['longitude']?$result[0]['longitude']:"",
        "profile_pic"=>$result[0]['image']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
         "access_token"=>md5($token)
        );
	return $data;
	}

	public static function twittersignin($twid,$email){
	
	global $conn;
	$data=array();
	$sql="select * from rc_users where rc_users.twid=:twid and rc_users.email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('twid',$twid);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	$token=Users::generateRandomString(12);
	
	$sql="update rc_users set token=:token where rc_users.twid=:twid and rc_users.email=:email and is_deleted=0";
	$sth=$conn->prepare($sql);
	$sth->bindValue('twid',$twid);
	$sth->bindValue('email',$email);
	$sth->bindValue('token',md5($token));
	try{$sth->execute();}
	catch(Exception $e){}
	
	 $data=array(
        "user_id"=>$result[0]['id'],
        "twitter_id"=>$result[0]['twid'],
        "name"=>$result[0]['name']?$result[0]['name']:"",
        "email"=>$result[0]['email']?$result[0]['email']:"",
        "city"=>$result[0]['city']?$result[0]['city']:"",
        "latitude"=>$result[0]['latitude']?$result[0]['latitude']:"",
        "longitude"=>$result[0]['longitude']?$result[0]['longitude']:"",
        "profile_pic"=>$result[0]['image']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
         "access_token"=>md5($token)
        );
	return $data;
	}

	public static function get_user_details($user_id){
	
	
	global $conn;
	$sth=$conn->prepare("select * from rc_users where id=:id and is_deleted=0");
	$sth->bindValue("id",$user_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	 $data=array(
        "user_id"=>$result[0]['id'],
        "fbid"=>$result[0]['fbid']?$result[0]['fbid']:'',
        "twid"=>$result[0]['twid']?$result[0]['twid']:'',
        "name"=>$result[0]['name']?$result[0]['name']:"",
        "email"=>$result[0]['email']?$result[0]['email']:"",
        "city"=>$result[0]['city']?$result[0]['city']:"",
         "gender"=>$result[0]['gender']?$result[0]['gender']:"",
        "latitude"=>$result[0]['latitude']?$result[0]['latitude']:"",
        "longitude"=>$result[0]['longitude']?$result[0]['longitude']:"",
        "profile_pic"=>$result[0]['image']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png"
        );
	return $data;
	
	}

public static function get_user_ids($post_id){
	global $conn;
	
	$sql="select rc_users.*,rc_posts.title from rc_users join rc_posts on rc_posts.user_id=rc_users.id where rc_posts.id=:post_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('post_id',$post_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
		
	return $result;
	}

}
?>
