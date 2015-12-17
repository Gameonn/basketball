<?php
class Messages{
	
		//fetching last message chat for a user
	public static function getAllUserMessages($user_id){
		global $conn;
		$messages=array();
		
		//mode r-recieve  s-send
		//$path=BASE_PATH."/timthumb.php?src=uploads/";
		$path=BASE_PATH."/uploads/";
		
		$sql="SELECT temp2.* FROM (SELECT temp.* FROM (SELECT u.id,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message_type, m.message,'s' as mode,m.created_on,
		CASE 
                  WHEN DATEDIFF(NOW(),m.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),m.created_on) ,' days ago')
                  WHEN HOUR(TIMEDIFF(NOW(),m.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),m.created_on)) ,' hrs ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),m.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),m.created_on)) ,' mins ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),m.created_on)) ,' s ago')
                END as time_elapsed
		FROM messages m JOIN users u ON m.user_id_receiver=u.id WHERE m.user_id_sender=:user_id 
		UNION 
		SELECT u.id,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video,m.message_type, m.message,'r' as mode,m.created_on,
		CASE 
                  WHEN DATEDIFF(NOW(),m.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),m.created_on) ,' days ago')
                  WHEN HOUR(TIMEDIFF(NOW(),m.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),m.created_on)) ,' hrs ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),m.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),m.created_on)) ,' mins ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),m.created_on)) ,' s ago')
                END as time_elapsed
		FROM messages m JOIN users u ON m.user_id_sender=u.id WHERE m.user_id_receiver=:user_id) as temp ORDER BY temp.created_on DESC) as temp2 GROUP BY temp2.id order by temp2.created_on DESC";
		$sth = $conn->prepare($sql);
		$sth->bindParam('user_id',$user_id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				//$value['image']=GeneralFunctions::getImagePath($value['image'], 200, 200);
				$value['created_date'] = date('M-d-Y',strtotime($value['created_on']));
				$value['time'] = date('g:i a',strtotime($value['created_on']));
				$value['time_elapsed']=self::time_since($value['created_on']);
				$messages[]=$value;
			}
		}catch(Exception $e){}
		return $messages;
	}

	
	//fetching matched users chat
	public static function getMatchedChat($user_id){
	
	global $conn;
	$path=BASE_PATH."/uploads/";
	$sql="SELECT temp2.* FROM (SELECT temp.* FROM (SELECT users.id as user_id,users.fbid,users.username as name,concat('$path',users.profile_pic) as profile_pic,users.profile_pic as profile_pic_name, 
	users.bio,users.age,users.city, m.id as mid, m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message_type, m.message,'s' as mode,m.created_on,
			CASE 
                  WHEN DATEDIFF(NOW(),users.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),users.created_on) ,' days ago')
                  WHEN HOUR(TIMEDIFF(NOW(),users.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),users.created_on)) ,' hrs ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),users.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),users.created_on)) ,' mins ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),users.created_on)) ,' s ago')
                END as last_visited
			FROM `user_like` 
				join users on users.id=user_like.liked_to
				left join messages m ON m.user_id_receiver=users.id and m.user_id_sender=:user_id
			WHERE liked_by=:user_id  and user_like.status=1 and liked_to IN (select liked_by from user_like where liked_to=:user_id and user_like.status=1)

UNION

SELECT users.id as user_id,users.fbid,users.username as name,concat('$path',users.profile_pic) as profile_pic,users.profile_pic as profile_pic_name, 
users.bio,users.age,users.city, m.id as mid, m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message_type, m.message,'r' as mode,m.created_on,
			CASE 
                  WHEN DATEDIFF(NOW(),users.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),users.created_on) ,' days ago')
                  WHEN HOUR(TIMEDIFF(NOW(),users.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),users.created_on)) ,' hrs ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),users.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),users.created_on)) ,' mins ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),users.created_on)) ,' s ago')
                END as last_visited
		FROM `user_like` 
			join users on users.id=user_like.liked_to
			left join messages m ON m.user_id_sender=users.id and m.user_id_receiver=:user_id
		WHERE liked_by=:user_id  and user_like.status=1 and liked_to IN (select liked_by from user_like where liked_to=:user_id and user_like.status=1)) as temp ORDER BY temp.mid DESC) as temp2 GROUP BY temp2.user_id ORDER BY temp2.user_id DESC";
		$sth=$conn->prepare($sql);
		$sth->bindParam('user_id',$user_id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value){
				$value['image_name']=$value['image_name']?$value['image_name']:"";
				$value['video_name']=$value['video_name']?$value['video_name']:"";
				$value['image']=$value['image']?$value['image']:"";
				$value['video']=$value['video']?$value['video']:"";
				$value['message_type']=$value['message_type']?$value['message_type']:"";
				$value['message']=$value['message']?$value['message']:"";
				$value['created_on']=$value['created_on']?$value['created_on']:"";
				$value['time'] = date('g:i a',strtotime($value['created_on']));
				$value['created_date'] = date('M-d-Y',strtotime($value['created_on']));			
				$value['time_elapsed']=self::time_since($value['created_on']);
				$value['message_status']=$value['message_type']?'1':'0';
				$value['unread_count']=(string)count(self::getUnreadCount($user_id,$value['user_id']));
				$all_messages[]=$value;
			}
		}catch(Excpetion $e){}
		return $all_messages;
	
	}
	
	
	//fetching all conversation messages between users
	public static function getUserMessages($user_id,$other_id){
		global $conn;
		$all_messages=array();
		//$path=BASE_PATH."/timthumb.php?src=uploads/";
		$path=BASE_PATH."/uploads/";
		$sql = "SELECT temp2.* from(SELECT temp.* from (SELECT m.id,m.user_id_sender as uid,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,
		m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message, m.message_type, m.created_on FROM messages m 
		JOIN users u ON u.id=m.user_id_sender WHERE m.user_id_sender=:other_id AND m.user_id_receiver=:user_id 
		UNION 
		SELECT m.id,m.user_id_sender as uid,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,
		concat('$path',m.image) as image,concat('$path',m.video) as video ,m.message, m.message_type, m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender 
		WHERE m.user_id_sender=:user_id AND m.user_id_receiver=:other_id ) as temp ORDER BY temp.created_on DESC LIMIT 0,30) as temp2 ORDER BY temp2.created_on";
		$sth=$conn->prepare($sql);
		$sth->bindParam('other_id',$other_id);
		$sth->bindParam('user_id',$user_id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				$value['time'] = date('g:i a',strtotime($value['created_on']));
				$value['created_date'] = date('M-d-Y',strtotime($value['created_on']));			
				$value['time_elapsed']=self::time_since($value['created_on']);
				$all_messages[]=$value;
			}
		}catch(Excpetion $e){}
		return $all_messages;
	}

	//fetching all messages received from other user after a giver message_id 
	public static function getRecUserMessagesAfter($user_id,$other_id,$id){
		global $conn;
		$all_messages=array();
		$path=BASE_PATH."/uploads/";
		//FROM_UNIXTIME( UNIX_TIMESTAMP( `messages`.created_on ) +".SERVER_OFFSET."+ ({$zone}) )  as msg_time
		
		$sql = "SELECT m.id,u.id as uid,u.fbid,u.username as name,CONCAT('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,
		CONCAT('$path',m.image) as image,CONCAT('$path',m.video) as video, m.message, m.message_type,m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender WHERE m.user_id_sender=:other_id
		 AND m.user_id_receiver=:user_id and m.id>:message_id order by m.created_on ASC "; 
		$sth=$conn->prepare($sql);
		$sth->bindValue(':other_id',$other_id);
		$sth->bindValue(':user_id',$user_id);
		$sth->bindValue(':message_id',$id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				$value['time'] = date('g:i a',strtotime($value['created_on']));
				$value['created_date'] = date('M-d-Y',strtotime($value['created_on']));
				$value['time_elapsed']=self::time_since($value['created_on']);
				$all_messages[]=$value;
			}
		}catch(Excpetion $e){}
		return $all_messages;
	}

	public static function getUnreadCount($user_id,$other_id){
		
	global $conn;
		$sql="SELECT m.id,u.id as uid,m.message,m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender
		WHERE m.user_id_sender=:other_id AND m.user_id_receiver=:user_id AND m.is_read=0"; 
	
		$sth=$conn->prepare($sql);
		$sth->bindValue('other_id',$other_id);
		$sth->bindValue('user_id',$user_id);
		try{$sth->execute();}
		catch(Exception $e){}
		$result=$sth->fetchAll();
		
		return $result;
		
	}
	
	//fetching all messages received from other user before a giver message_id
	public static function getRecMessagesBefore($user_id,$other_id,$id){
		global $conn;
		$all_messages=array();
		$sql = "SELECT m.id,u.id as uid,u.username,m.message,m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender WHERE m.user_id_sender=:other_id AND m.user_id_receiver=:user_id AND m.id < :message_id order by m.created_on ASC"; 
		$sth=$conn->prepare($sql);
		$sth->bindValue(':other_id',$other_id);
		$sth->bindValue(':user_id',$user_id);
		$sth->bindValue(':message_id',$id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				$value['time'] = date('g:i a',strtotime($value['created_on']));
				$value['created_date'] = date('M-d-Y',strtotime($value['created_on']));
				$value['time_elapsed']=self::time_since($value['created_on']);
				$all_messages[]=$value;
			}
		}catch(Excpetion $e){}
		return $all_messages;
	}

	
	//update the read status of the sent message
	public static function updateReadStatus($user_id,$other_id){
		
	global $conn;
		$sql="UPDATE messages as m set is_read=1 WHERE m.user_id_sender=:other_id AND m.user_id_receiver=:user_id";
		$sth=$conn->prepare($sql);
		$sth->bindValue('other_id',$other_id);
		$sth->bindValue('user_id',$user_id);
		try{$sth->execute();}
		catch(Exception $e){}
		
		return true;
		
	}
	

		//fetching all messages received and sent from other user after a giver message_id 	
	public static function getUserMessagesAfter($user_id,$other_id,$id){
		global $conn;
		$all_messages=array();
		//$path=BASE_PATH."/timthumb.php?src=uploads/";
		$path=BASE_PATH."/uploads/";
		
		$sql = "SELECT m.id,u.id as uid,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message, m.message_type,m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender WHERE m.user_id_sender=:other_id
		 AND m.user_id_receiver=:user_id and m.id>:message_id
		UNION
		SELECT m.id,u.id as uid,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message, m.message_type,m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender WHERE m.user_id_sender=:user_id
		 AND m.user_id_receiver=:other_id and m.id>:message_id"; 
		$sth=$conn->prepare($sql);
		$sth->bindValue(':other_id',$other_id);
		$sth->bindValue(':user_id',$user_id);
		$sth->bindValue(':message_id',$id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				$value['time'] = date('g:i a',strtotime($value['created_on']));
				$value['created_date'] = date('M-d-Y',strtotime($value['created_on']));
				$value['time_elapsed']=self::time_since($value['created_on']);
				$all_messages[]=$value;
			}
		}catch(Excpetion $e){}
		return $all_messages;
	}


		//fetching all messages received and sent from other user before a giver message_id
	public static function getUserMessagesBefore($user_id,$other_id,$id){
		global $conn;
		$all_messages=array();
		//$path=BASE_PATH."/timthumb.php?src=uploads/";
		$path=BASE_PATH."/uploads/";
		
		$sql = "SELECT temp.* from(SELECT m.id,u.id as uid,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message, m.message_type,m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender WHERE m.user_id_sender=:other_id
		 AND m.user_id_receiver=:user_id and m.id<:message_id
		 UNION
		 SELECT m.id,u.id as uid,u.fbid,u.username as name,concat('$path',u.profile_pic) as profile_pic,m.image as image_name,m.video as video_name,concat('$path',m.image) as image,concat('$path',m.video) as video, m.message, m.message_type,m.created_on FROM messages m JOIN users u ON u.id=m.user_id_sender WHERE m.user_id_sender=:user_id
		 AND m.user_id_receiver=:other_id and m.id<:message_id) as temp ORDER BY temp.created_on ASC"; 
		$sth=$conn->prepare($sql);
		$sth->bindValue(':other_id',$other_id);
		$sth->bindValue(':user_id',$user_id);
		$sth->bindValue(':message_id',$id);
		try{
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				$value['time'] = date('g:i a',strtotime($value['created_on']));
				$value['created_date'] = date('M-d-Y',strtotime($value['created_on']));
				$value['time_elapsed']=self::time_since($value['created_on']);
				$all_messages[]=$value;
			}
		}catch(Excpetion $e){}
		return $all_messages;
	}
	
	//saving message and sending message to other user
	public static function saveUserMessage($user_id,$other_id,$message,$image,$video,$message_type){
		global $conn;
		$insertid=0;
		$sql="INSERT INTO messages(id,user_id_sender,user_id_receiver,message,image,video,message_type,is_read,created_on) VALUES(DEFAULT,:user_id_sender,:user_id_receiver,:message,:image,:video,:message_type,0,NOW())";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':user_id_sender',$user_id);
		$sth->bindParam(':user_id_receiver',$other_id);
		$sth->bindParam(':message',$message);
		$sth->bindParam(':image',$image);
		$sth->bindParam(':video',$video);
		$sth->bindParam(':message_type',$message_type);
		try{
			$sth->execute();
			$insertid = $conn->lastInsertId();
		}catch(Exception $e){}
		return $insertid;
	}
	
		public static function local_time($time_zone,$dateFormat,$current_server_time){

	$minutes	=substr($time_zone,3);
	$hours		=substr($time_zone,1,2);
	$sign		=substr($time_zone,0,1);
	$seconds	=$sign.($hours * 3600)+($minutes * 60);
	$qqq = gmdate($dateFormat, strtotime($current_server_time) + $seconds);
	
	return $qqq;
	}
	
	
		//calculation of post created time
	public static function time_since($created_on){
	
	global $conn;
	$sth=$conn->prepare("SELECT UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP('$created_on') as time_diff");
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
	
	
}
?>
