<?php
class Users{

	//fetching user id using token
	public static function getUserId($token){
	
	global $conn;
	$data=array();
	$sql="SELECT * from users WHERE users.token=:token";
	$sth=$conn->prepare($sql);
	$sth->bindValue('token',$token);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_id=$result[0]['id'];
	
	return $user_id;
	}
	
	//fetching user profile
	public static function user_after_signup($email,$code){
	
	global $conn;
	$data=array();
	$sql="SELECT users.*,nba_team_list.team_name as nba_team,nba_team_list.team_logo,nba_team_list.team_bkwd_logo FROM users LEFT JOIN nba_team_list ON nba_team_list.id=users.favorite_nba_team where users.email=:email and token=:token";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	$sth->bindValue('token',$code);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
		
	if($result){
	 $data=array(
	        "user_id"=>$result[0]['id'],
	        "fbid"=>$result[0]['fbid']?$result[0]['fbid']:'',
			"googleid"=>$result[0]['googleid']?$result[0]['googleid']:'',
	        "first_name"=>$result[0]['first_name']?$result[0]['first_name']:"",
	        "last_name"=>$result[0]['last_name']?$result[0]['last_name']:"",
	        "company_name"=>$result[0]['company_name']?$result[0]['company_name']:"",
	        "email"=>$result[0]['email']?$result[0]['email']:"",
	        "desired_position"=>$result[0]['desired_position']?$result[0]['desired_position']:"",
	        "second_choice"=>$result[0]['second_choice']?$result[0]['second_choice']:"",
	        "gender"=>$result[0]['gender']?$result[0]['gender']:"",
	        "user_type"=>$result[0]['user_type']?$result[0]['user_type']:"",
			"jersey_number"=>$result[0]['jersey_number']?$result[0]['jersey_number']:"",
			"height"=>$result[0]['height']?$result[0]['height']:"",
			"weight"=>$result[0]['weight']?$result[0]['weight']:"",
			"address"=>$result[0]['address']?$result[0]['address']:"",
			"inspire_desc"=>$result[0]['inspire_desc']?$result[0]['inspire_desc']:"",
			"favorite_nba_team"=>$result[0]['nba_team']?$result[0]['nba_team']:"",
			"team_logo"=>$result[0]['team_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_logo']:"",
			"team_bkwd_logo"=>$result[0]['team_bkwd_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_bkwd_logo']:"",
			"age"=>$result[0]['age']?$result[0]['age']:"0",
			"emergency_name"=>$result[0]['emergency_name']?$result[0]['emergency_name']:"",
			"emergency_relation"=>$result[0]['emergency_relation']?$result[0]['emergency_relation']:"",
			"emergency_phone"=>$result[0]['emergency_phone']?$result[0]['emergency_phone']:"",
			"emergency_blood_group"=>$result[0]['emergency_blood_group']?$result[0]['emergency_blood_group']:"",
			"profile_pic"=>$result[0]['profile_pic']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['profile_pic']:BASE_PATH."timthumb.php?src=uploads/default_user.png",
        	"token"=>$result[0]['token']
        );
		}
	return $data;
	}
	
	//fetching user fbid using token
	public static function getUserfbId($token){
	
	global $conn;
	$data=array();
	$sql="SELECT * from users WHERE users.token=:token";
	$sth=$conn->prepare($sql);
	$sth->bindValue('token',$token);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_fbid=$result[0]['fbid'];
	
	return $user_fbid;
	}
	
	//used in login api
	public static function user_login($email){
	
	global $conn;
	$data=array();
	$sql="SELECT users.*,nba_team_list.team_name as nba_team,nba_team_list.team_logo,nba_team_list.team_bkwd_logo FROM users LEFT JOIN nba_team_list ON nba_team_list.id=users.favorite_nba_team where users.email=:email";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$token=Users::generateRandomString(12);
	
	$sql="UPDATE users set token=:token where users.email=:email";
	$sth=$conn->prepare($sql);
	$sth->bindValue('email',$email);
	$sth->bindValue('token',md5($token));
	try{$sth->execute();}
	catch(Exception $e){}
	
		if($result){
			$data=array(
	        "user_id"=>$result[0]['id'],
	        "fbid"=>$result[0]['fbid']?$result[0]['fbid']:'',
			"googleid"=>$result[0]['googleid']?$result[0]['googleid']:'',
	        "first_name"=>$result[0]['first_name']?$result[0]['first_name']:"",
	        "last_name"=>$result[0]['last_name']?$result[0]['last_name']:"",
	        "company_name"=>$result[0]['company_name']?$result[0]['company_name']:"",
	        "email"=>$result[0]['email']?$result[0]['email']:"",
	        "user_type"=>$result[0]['user_type']?$result[0]['user_type']:"",
	        "desired_position"=>$result[0]['desired_position']?$result[0]['desired_position']:"",
	        "second_choice"=>$result[0]['second_choice']?$result[0]['second_choice']:"",
	        "gender"=>$result[0]['gender']?$result[0]['gender']:"",
			"jersey_number"=>$result[0]['jersey_number']?$result[0]['jersey_number']:"",
			"height"=>$result[0]['height']?$result[0]['height']:"",
			"weight"=>$result[0]['weight']?$result[0]['weight']:"",
			"address"=>$result[0]['address']?$result[0]['address']:"",
			"inspire_desc"=>$result[0]['inspire_desc']?$result[0]['inspire_desc']:"",
			"favorite_nba_team"=>$result[0]['nba_team']?$result[0]['nba_team']:"",
			"team_logo"=>$result[0]['team_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_logo']:"",
			"team_bkwd_logo"=>$result[0]['team_bkwd_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_bkwd_logo']:"",
			"age"=>$result[0]['age']?$result[0]['age']:"0",
			"emergency_name"=>$result[0]['emergency_name']?$result[0]['emergency_name']:"",
			"emergency_relation"=>$result[0]['emergency_relation']?$result[0]['emergency_relation']:"",
			"emergency_phone"=>$result[0]['emergency_phone']?$result[0]['emergency_phone']:"",
			"emergency_blood_group"=>$result[0]['emergency_blood_group']?$result[0]['emergency_blood_group']:"",
			"profile_pic"=>$result[0]['profile_pic']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['profile_pic']:BASE_PATH."timthumb.php?src=uploads/default_user.png",
        	"token"=>md5($token)
			);
		}

	return $data;
	}
	
	
	//checking whether the given fbid exists or not
	public static function checkfb($fbid){
	
	global $conn;
	$data=array();
	$sql="SELECT * from users WHERE users.fbid=:fbid";
	$sth=$conn->prepare($sql);
	$sth->bindValue('fbid',$fbid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_fbid=$result[0]['fbid'];
	
	return $user_fbid;
	}
	
	
	public static function getFavoriteTeamId($favorite_nba_team){
	
	global $conn;
	$sql="SELECT id from nba_team_list WHERE team_name LIKE '%$favorite_nba_team%'";
	$sth=$conn->prepare($sql);
	$sth->bindValue('team_name',$favorite_nba_team);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$nba_team_id=$result[0]['id'];
	
	return $nba_team_id;
	}
	
	//checking whether the given googleid exists or not
	public static function checkgoogle($googleid){
	
	global $conn;
	$data=array();
	$sql="SELECT * from users WHERE users.googleid=:googleid";
	$sth=$conn->prepare($sql);
	$sth->bindValue('googleid',$googleid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_gid=$result[0]['googleid'];
	
	return $user_gid;
	}
	
	
	//fetching user id using fb id
	public static function checkuserid($fbid){
		
	global $conn;
	$data=array();
	$sql="SELECT * from users where users.fbid=:fbid";
	$sth=$conn->prepare($sql);
	$sth->bindValue('fbid',$fbid);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$user_id=$result[0]['id'];
	
	return $user_id;	
		
	}
	
	
	//old user
	public static function fbsignin($id,$type){
	
	global $conn;
	$data=array();
	//$path=BASE_PATH."uploads/";
	//$uid=self::checkuserid($fbid);	
		
	if($type=='google')
		$where_condition="WHERE users.googleid=:id";
	else
		$where_condition="WHERE users.fbid=:id";
		
	$sql="SELECT users.*,nba_team_list.team_name as nba_team,nba_team_list.team_logo,nba_team_list.team_bkwd_logo FROM users LEFT JOIN nba_team_list ON nba_team_list.id=users.favorite_nba_team $where_condition ";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	$token=Users::generateRandomString(12);
	
	$sql="UPDATE users set token=:token $where_condition";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$id);
	$sth->bindValue('token',md5($token));
	try{$sth->execute();}
	catch(Exception $e){}
	
		$data=array(
	        "user_id"=>$result[0]['id'],
	        "fbid"=>$result[0]['fbid']?$result[0]['fbid']:'',
			"googleid"=>$result[0]['googleid']?$result[0]['googleid']:'',
	        "first_name"=>$result[0]['first_name']?$result[0]['first_name']:"",
	        "last_name"=>$result[0]['last_name']?$result[0]['last_name']:"",
	        "company_name"=>$result[0]['company_name']?$result[0]['company_name']:"",
	        "email"=>$result[0]['email']?$result[0]['email']:"",
	        "user_type"=>$result[0]['user_type']?$result[0]['user_type']:"",
	        "desired_position"=>$result[0]['desired_position']?$result[0]['desired_position']:"",
	        "second_choice"=>$result[0]['second_choice']?$result[0]['second_choice']:"",
	        "gender"=>$result[0]['gender']?$result[0]['gender']:"",
			"jersey_number"=>$result[0]['jersey_number']?$result[0]['jersey_number']:"",
			"height"=>$result[0]['height']?$result[0]['height']:"",
			"weight"=>$result[0]['weight']?$result[0]['weight']:"",
			"address"=>$result[0]['address']?$result[0]['address']:"",
			"inspire_desc"=>$result[0]['inspire_desc']?$result[0]['inspire_desc']:"",
			"favorite_nba_team"=>$result[0]['nba_team']?$result[0]['nba_team']:"",
			"team_logo"=>$result[0]['team_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_logo']:"",
			"team_bkwd_logo"=>$result[0]['team_bkwd_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_bkwd_logo']:"",
			"age"=>$result[0]['age']?$result[0]['age']:"0",
			"emergency_name"=>$result[0]['emergency_name']?$result[0]['emergency_name']:"",
			"emergency_relation"=>$result[0]['emergency_relation']?$result[0]['emergency_relation']:"",
			"emergency_phone"=>$result[0]['emergency_phone']?$result[0]['emergency_phone']:"",
			"emergency_blood_group"=>$result[0]['emergency_blood_group']?$result[0]['emergency_blood_group']:"",
			"profile_pic"=>$result[0]['profile_pic']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['profile_pic']:BASE_PATH."timthumb.php?src=uploads/default_user.png",
        	"token"=>md5($token)
        );
		
	return $data;
	}
	
	
	public static function getUserProfile($id){
	
	global $conn;
	$data=array();

	$sql="SELECT users.*,nba_team_list.team_name as nba_team,nba_team_list.team_logo,nba_team_list.team_bkwd_logo FROM users LEFT JOIN nba_team_list ON nba_team_list.id=users.favorite_nba_team WHERE users.id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();

	
		$data=array(
	        "user_id"=>$result[0]['id'],
	        "fbid"=>$result[0]['fbid']?$result[0]['fbid']:'',
			"googleid"=>$result[0]['googleid']?$result[0]['googleid']:'',
	        "first_name"=>$result[0]['first_name']?$result[0]['first_name']:"",
	        "last_name"=>$result[0]['last_name']?$result[0]['last_name']:"",
	        "company_name"=>$result[0]['company_name']?$result[0]['company_name']:"",
	        "email"=>$result[0]['email']?$result[0]['email']:"",
	        "user_type"=>$result[0]['user_type']?$result[0]['user_type']:"",
	        "desired_position"=>$result[0]['desired_position']?$result[0]['desired_position']:"",
	        "second_choice"=>$result[0]['second_choice']?$result[0]['second_choice']:"",
	        "gender"=>$result[0]['gender']?$result[0]['gender']:"",
			"jersey_number"=>$result[0]['jersey_number']?$result[0]['jersey_number']:"",
			"height"=>$result[0]['height']?$result[0]['height']:"",
			"weight"=>$result[0]['weight']?$result[0]['weight']:"",
			"address"=>$result[0]['address']?$result[0]['address']:"",
			"inspire_desc"=>$result[0]['inspire_desc']?$result[0]['inspire_desc']:"",
			"favorite_nba_team"=>$result[0]['nba_team']?$result[0]['nba_team']:"",
			"team_logo"=>$result[0]['team_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_logo']:"",
			"team_bkwd_logo"=>$result[0]['team_bkwd_logo']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['team_bkwd_logo']:"",
			"age"=>$result[0]['age']?$result[0]['age']:"0",
			"emergency_name"=>$result[0]['emergency_name']?$result[0]['emergency_name']:"",
			"emergency_relation"=>$result[0]['emergency_relation']?$result[0]['emergency_relation']:"",
			"emergency_phone"=>$result[0]['emergency_phone']?$result[0]['emergency_phone']:"",
			"emergency_blood_group"=>$result[0]['emergency_blood_group']?$result[0]['emergency_blood_group']:"",
			"profile_pic"=>$result[0]['profile_pic']?BASE_PATH."/timthumb.php?src=uploads/".$result[0]['profile_pic']:BASE_PATH."timthumb.php?src=uploads/default_user.png",
        	"token"=>$result[0]['token']
        );
		
	return $data;
	}
	
	
	//New registered users
	public static function fbsigninNew($id,$type){
	
	global $conn;
	$data=array();
	//$path=BASE_PATH."uploads/";
	$path=BASE_PATH."/timthumb.php?src=uploads/";
	
		if($type=='google')
		$where_condition="WHERE users.googleid=:id";
		else
		$where_condition="WHERE users.fbid=:id";
	
	$sql="SELECT users.id as user_id,users.*,nba_team_list.team_name as favorite_nba_team, CONCAT('$path',nba_team_list.team_logo) as 
			team_logo,CONCAT('$path',nba_team_list.team_bkwd_logo) as team_bkwd_logo,CONCAT('$path',users.profile_pic) as profile_pic FROM users 
			LEFT JOIN nba_team_list ON nba_team_list.id=users.favorite_nba_team $where_condition";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$id);
	try{
		$sth->execute();
		$result=$sth->fetchAll(PDO::FETCH_ASSOC);
		$profile_info=$result['0'];
	}
	
	catch(Exception $e){}
	return $profile_info;
	}
	
	
	public static function updateFavTeam($user_id,$favorite_nba_team){
		
		global $conn;
	
		$sql="UPDATE users set favorite_nba_team=:favorite_nba_team WHERE users.id=:user_id";
		$sth=$conn->prepare($sql);
		$sth->bindValue('user_id',$user_id);
		$sth->bindValue('favorite_nba_team',$favorite_nba_team);
		try{$sth->execute();}
		catch(Exception $e){}
		
		return true;
	}
	
	
	//calculation of post created time
	public static function time_since($created_on){
	global $conn;
	$sth=$conn->prepare("select UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP('$created_on') as time_diff");
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	$diff=$res[0]['time_diff'];

	if($diff < 60){
		$response = $diff.' s ago';
	}elseif($diff < 3600){
		$response = floor($diff / 60).' mins ago';	
	}elseif($diff < 86400){
		$response = floor($diff / 3600).' hrs ago';
	}elseif($diff < 2592000){
		$response = floor($diff / 86400).' days ago';
	}elseif($diff < 31104000){
		$response = floor($diff / 2592000).' months ago';
	}else{
		$response = floor($diff / 31104000).' year ago';
	}
	
	return $response;
	}
	
	public static function generateRandomString($length = 10){
	$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
	$randomString = '';
	for ($i = 0; $i < $length; $i++){
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
	}
	
	
	public static function generateRandomImgId($length = 15){
	
	$characters   = '0123456789';
	
	$randomString = '';
	for ($i = 0; $i < $length; $i++){
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
	}

}
?>
