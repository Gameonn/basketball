<?php
class GeneralFunctions
{
	public static function getImagePath($file_name){
		if(!empty($file_name))
		{
			return BASE_PATH."uploads/".$file_name;
				//return BASE_PATH."timthumb.php?src=uploads/".$file_name;
			}
			else
			{
				return BASE_PATH."uploads/default_256.png";
				//return BASE_PATH."timthumb.php?src=uploads/default_256.png";
				
			}
		}


	public static function get_notifications($user_id){
	
	global $conn;
	
	$sql="SELECT rc_notification.*,rc_posts.title as post_title,rc_posts.id as post_id,rc_users.name,rc_users.image,
	CASE 
                  WHEN DATEDIFF(NOW(),rc_notification.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_notification.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_notification.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_notification.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_notification.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_notification.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_notification.created_on)) ,' s ago')
                END as time_elapsed FROM rc_notification 
	join rc_posts on rc_posts.id=rc_notification.post_id join rc_users on rc_users.id=rc_notification.user_id_sender where rc_notification.user_id_receiver=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	return $result;
	}
	
	public static function get_comments($post_id){
	
	
	global $conn;
	$path=BASE_PATH."timthumb.php?src=uploads/";
	$sql="SELECT rc_comments.*,(select rc_users.name from rc_users where rc_users.id=rc_comments.user_id) as uname,(select concat('$path',rc_users.image) from rc_users where rc_users.id=rc_comments.user_id) as c_profile_pic,
CASE 
                  WHEN DATEDIFF(NOW(),rc_comments.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_comments.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_comments.created_on)) ,' s ago')
                END as comment_time

from rc_comments where rc_comments.post_id=:post_id order by rc_comments.created_on DESC";
	$sth=$conn->prepare($sql);
	$sth->bindValue('post_id',$post_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	return $res;
	}


	public static function get_post_detail($post_id,$user_id){
	
	global $conn;
	
	$sql="select rc_users.*,rc_users.id as uid,rc_posts.*,rc_posts.city as post_city,rc_posts.id as pid,(select count(rc_likes.id) from rc_likes where rc_likes.post_id=rc_posts.id) 
	as likes_count,(select count(rc_likes.id) from rc_likes where rc_likes.post_id=rc_posts.id and rc_likes.user_id='$user_id') as is_liked,rc_comments.id as cid,
	rc_comments.user_id as c_uid,rc_comments.*,(select rc_users.name from rc_users where rc_users.id=rc_comments.user_id) as un,
	(select rc_users.image from rc_users where rc_users.id=rc_comments.user_id) as c_profile_pic,

CASE 
                  WHEN DATEDIFF(NOW(),rc_posts.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_posts.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_posts.created_on)) ,' s ago')
                END as post_time,
CASE 
                  WHEN DATEDIFF(NOW(),rc_comments.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_comments.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_comments.created_on)) ,' s ago')
                END as comment_time

from rc_posts left join rc_users on rc_users.id=rc_posts.user_id left join rc_comments on rc_comments.post_id=rc_posts.id where rc_users.is_deleted=0 and rc_posts.id=:post_id ";
	//DB::raw(DB::select());
	$sth=$conn->prepare($sql);
	$sth->bindValue('post_id',$post_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll(PDO::FETCH_ASSOC);
	
	if($res){
	
	foreach($res as $key=>$value){
			
			if(!$final[$value['pid']]){
	 	$final[$value['pid']]=array(
	 			'user_id'=>$value['uid'],
				'p_name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['lat']?$value['lat']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['lng']?$value['lng']:"",
				"post_id"=>$value['pid']?$value['pid']:"",
				"post_title"=>$value['title']?$value['title']:"",
				"post_description"=>$value['description']?$value['description']:"",
				'post_image'=>$value['post_image']?BASE_PATH."timthumb.php?src=uploads/".$value['post_image']:"",
				
				'post_city'=>$value['post_city']?$value['post_city']:"",
				'post_time'=>$value['post_time']?$value['post_time']:"",
				'likes_count'=>$value['likes_count']?$value['likes_count']:0,
				'is_liked'=>$value['is_liked']?$value['is_liked']:'0',
				'comments'=>array()
				);
			}
			
			if(!ISSET($final[$value['pid']]['comments'][$value['cid']])){
			if($value['cid']){
			$final[$value['pid']]['comments'][]=array(
				"comment_id"=>$value['cid']?$value['cid']:"",
				"user_id"=>$value['c_uid']?$value['c_uid']:"",
				"name"=>$value['un']?$value['un']:"",
				'profile_pic'=>$value['c_profile_pic']?BASE_PATH."timthumb.php?src=uploads/".$value['c_profile_pic']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'comment'=>$value['comment']?$value['comment']:"",
				'comment_time'=>$value['comment_time']?$value['comment_time']:""
				);
			}	
			}
		}	
        }
        
        
       if($final){
		foreach($final as $key=>$val){
		$data2=array();
		
		$result[]=$val;
		}
        }
        return $result;
	}
	
	
	public static function addNotification($user_id,$oid,$post_id,$type,$notify_message){
	
	global $conn;
	$sql="insert into rc_notification(`id`,`post_id`,`user_id_sender`,`user_id_receiver`,title,type,is_read,created_on) values(DEFAULT,:post_id,:user_id,:oid,:title,:type,0,NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('post_id',$post_id);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('oid',$oid);
	$sth->bindValue('type',$type);
	$sth->bindValue('title',$notify_message);
	try{$sth->execute();}
	catch(Exception $e){}
	
	
	}
	
	public static function get_recent_chats($uid){
	
	global $conn;
	
	$sql="select * from (select * from (select users.*,users.id as uid,

CASE 
                  WHEN DATEDIFF(NOW(),messages.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),messages.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),messages.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),messages.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),messages.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),messages.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),messages.created_on)) ,' s ago')
                END as message_time 
from messages left join users on users.id=messages.user_id1 where messages.user_id2=:uid
UNION DISTINCT 
select users.*,users.id as uid,
CASE 
                  WHEN DATEDIFF(NOW(),messages.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),messages.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),messages.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),messages.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),messages.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),messages.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),messages.created_on)) ,' s ago')
                END as message_time from messages left join users on users.id=messages.user_id2 where messages.user_id1=:uid
             
              ) as temp order by temp.message_time DESC) as trp group by trp.uid ";
              
        $sth=$conn->prepare($sql);	
	$sth->bindValue('uid',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();   
              
	if($res){
	foreach($res as $key=>$value){
	if(!ISSET($final[$value['uid']])){
	 	$final[$value['uid']]=array(
	 			'user_id'=>$value['uid'],
				'name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['lat']?$value['lat']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['lng']?$value['lng']:"",
				'last_chat_time'=>$value['message_time']?$value['message_time']:""
				);
			}
			}
			}
	
		if(is_array($final)){
		foreach($final as $key=>$value){
		
		
		$data[]=$value;
		
		}
		}
	
	return $data;
	}
	
	
		public static function get_last_chats($uid){
	
	global $conn;
	
	$sql="select * from (select * from (select rc_users.*,rc_users.id as uid,rc_messages.message,rc_messages.created_on as mco,

CASE 
                  WHEN DATEDIFF(NOW(),rc_messages.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_messages.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_messages.created_on)) ,' s ago')
                END as message_time 
from rc_messages left join rc_users on rc_users.id=rc_messages.user_id1 where rc_messages.user_id2=:uid
UNION DISTINCT 
select rc_users.*,rc_users.id as uid,rc_messages.message,rc_messages.created_on as mco,
CASE 
                  WHEN DATEDIFF(NOW(),rc_messages.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_messages.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_messages.created_on)) ,' s ago')
                END as message_time from rc_messages left join rc_users on rc_users.id=rc_messages.user_id2 where rc_messages.user_id1=:uid
             
              ) as temp order by temp.mco DESC) as trp group by trp.uid";
              
        $sth=$conn->prepare($sql);	
	$sth->bindValue('uid',$uid);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();   
              
	if($res){
	foreach($res as $key=>$value){
	if(!ISSET($final[$value['uid']])){
	 	$final[$value['uid']]=array(
	 			'user_id'=>$value['uid'],
				'name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['latitude']?$value['latitude']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['longitude']?$value['longitude']:"",
				'last_chat_time'=>$value['message_time']?$value['message_time']:"",
				'last_message'=>$value['message']?$value['message']:""
				
				);
			}
			}
			}
	
		if(is_array($final)){
		foreach($final as $key=>$value){
		
		
		$data[]=$value;
		
		}
		}
	
	return $data;
	}
			
		public static function get_feeds($user_id){
	
	global $conn;
	
	
	$sql="select rc_users.*,rc_users.id as uid,rc_posts.*,rc_posts.city as post_city,rc_posts.id as pid,rc_comments.id as cid,rc_comments.user_id as c_uid,rc_comments.*,(select count(rc_likes.id) from rc_likes 
		where rc_likes.post_id=rc_posts.id and rc_likes.user_id='$user_id') as is_liked,(select count(rc_likes.id) from rc_likes where rc_likes.post_id=rc_posts.id) as likes_count,
(select rc_users.name from rc_users where rc_users.id=rc_comments.user_id) as un,(select rc_users.image from rc_users where rc_users.id=rc_comments.user_id) as c_profile_pic,
CASE 
                  WHEN DATEDIFF(NOW(),rc_posts.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_posts.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_posts.created_on)) ,' s ago')
                END as post_time,
CASE 
                  WHEN DATEDIFF(NOW(),rc_comments.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_comments.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_comments.created_on)) ,' s ago')
                END as comment_time
from rc_posts left join rc_users on rc_users.id=rc_posts.user_id left join rc_comments on rc_comments.post_id=rc_posts.id where rc_users.is_deleted=0 order by rc_posts.created_on DESC ";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
		if($res){
		foreach($res as $key=>$value){
		if(!$final[$value['pid']]){
	 	$final[$value['pid']]=array(
				'user_id'=>$value['uid'],
				'p_name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['lat']?$value['lat']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['lng']?$value['lng']:"",
				"post_id"=>$value['pid']?$value['pid']:"",
				"post_title"=>$value['title']?$value['title']:"",
				"post_description"=>$value['description']?$value['description']:"",
				'post_city'=>$value['post_city']?$value['post_city']:"",
			'post_image'=>$value['post_image']?BASE_PATH."timthumb.php?src=uploads/".$value['post_image']:"",
				'post_time'=>$value['post_time']?$value['post_time']:"",
				'likes_count'=>$value['likes_count']?$value['likes_count']:0,
				'is_liked'=>$value['is_liked']?$value['is_liked']:'0',
				'comments'=>array()
				);
			}
		
			
		if(!ISSET($final[$value['pid']]['comments'][$value['cid']])){
		if($value['cid']){
			$final[$value['pid']]['comments'][$value['cid']]=array(
				"comment_id"=>$value['cid']?$value['cid']:"",
				"user_id"=>$value['c_uid']?$value['c_uid']:"",
				'profile_pic'=>$value['c_profile_pic']?BASE_PATH."timthumb.php?src=uploads/".$value['c_profile_pic']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				"name"=>$value['un']?$value['un']:"",
				'comment'=>$value['comment']?$value['comment']:"",
				'comment_time'=>$value['comment_time']?$value['comment_time']:""
				);
			   }	
			}	
		}	
        }
       if($final){
        	foreach($final as $key=>$value){
		$data2=array();
		foreach($value['comments'] as $val){
		$data2[]=$val;
		}
		$value['comments']=$data2;		
		$result[]=$value;
	}
        }
        
	return $result;
	}


	public static function get_favorite_posts($user_id){
	
	global $conn;
	
	
	$sql="select rc_users.*,rc_users.id as uid,rc_posts.city as post_city,rc_posts.*,rc_posts.id as pid,rc_comments.id as cid,rc_comments.user_id as c_uid,rc_comments.*,(select count(rc_likes.id) from rc_likes 
		where rc_likes.post_id=rc_posts.id and rc_likes.user_id='$user_id') as is_liked,(select count(rc_likes.id) from rc_likes where rc_likes.post_id=rc_posts.id) as likes_count,
(select rc_users.name from rc_users where rc_users.id=rc_comments.user_id) as un,(select rc_users.image from rc_users where rc_users.id=rc_comments.user_id) as c_profile_pic,
CASE 
                  WHEN DATEDIFF(NOW(),rc_posts.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_posts.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_posts.created_on)) ,' s ago')
                END as post_time,
CASE 
                  WHEN DATEDIFF(NOW(),rc_comments.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_comments.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_comments.created_on)) ,' s ago')
                END as comment_time
from rc_posts left join rc_users on rc_users.id=rc_posts.user_id join rc_favorites on rc_favorites.city=rc_posts.city left join rc_comments on rc_comments.post_id=rc_posts.id where rc_users.is_deleted=0 order by rc_posts.created_on DESC ";
	$sth=$conn->prepare($sql);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
		if($res){
		foreach($res as $key=>$value){
		if(!$final[$value['pid']]){
	 	$final[$value['pid']]=array(
				'user_id'=>$value['uid'],
				'p_name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['lat']?$value['lat']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['lng']?$value['lng']:"",
				"post_id"=>$value['pid']?$value['pid']:"",
				"post_title"=>$value['title']?$value['title']:"",
				'post_city'=>$value['post_city']?$value['post_city']:"",
				"post_description"=>$value['description']?$value['description']:"",
				'post_image'=>$value['post_image']?BASE_PATH."timthumb.php?src=uploads/".$value['post_image']:"",
				'post_time'=>$value['post_time']?$value['post_time']:"",
				'likes_count'=>$value['likes_count']?$value['likes_count']:0,
				'is_liked'=>$value['is_liked']?$value['is_liked']:'0',
				'comments'=>array()
				);
			}
		
			
		if(!ISSET($final[$value['pid']]['comments'][$value['cid']])){
		if($value['cid']){
			$final[$value['pid']]['comments'][$value['cid']]=array(
				"comment_id"=>$value['cid']?$value['cid']:"",
				"user_id"=>$value['c_uid']?$value['c_uid']:"",
				'profile_pic'=>$value['c_profile_pic']?BASE_PATH."timthumb.php?src=uploads/".$value['c_profile_pic']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				"name"=>$value['un']?$value['un']:"",
				'comment'=>$value['comment']?$value['comment']:"",
				'comment_time'=>$value['comment_time']?$value['comment_time']:""
				);
			   }	
			}	
		}	
        }
       if($final){
        	foreach($final as $key=>$value){
		$data2=array();
		foreach($value['comments'] as $val){
		$data2[]=$val;
		}
		$value['comments']=$data2;		
		$result[]=$value;
	}
        }
        
	return $result;
	}




		public static function get_city_posts($city,$user_id){
	
	global $conn;
	
	
	$sql="select rc_users.*,rc_users.id as uid,rc_posts.*,rc_posts.city as post_city,rc_posts.id as pid,rc_comments.id as cid,rc_comments.user_id as c_uid,rc_comments.*,(select count(rc_likes.id) from rc_likes 
		where rc_likes.post_id=rc_posts.id and rc_likes.user_id='$user_id') as is_liked,(select count(rc_likes.id) from rc_likes where rc_likes.post_id=rc_posts.id) as likes_count,
(select rc_users.name from rc_users where rc_users.id=rc_comments.user_id) as un,(select rc_users.image from rc_users where rc_users.id=rc_comments.user_id) as c_profile_pic,
CASE 
                  WHEN DATEDIFF(NOW(),rc_posts.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_posts.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_posts.created_on)) ,' s ago')
                END as post_time,
CASE 
                  WHEN DATEDIFF(NOW(),rc_comments.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_comments.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_comments.created_on)) ,' s ago')
                END as comment_time
from rc_posts left join rc_users on rc_users.id=rc_posts.user_id  left join rc_comments on rc_comments.post_id=rc_posts.id where rc_users.is_deleted=0 and rc_posts.city=:city order by rc_posts.created_on DESC ";
	$sth=$conn->prepare($sql);	
	$sth->bindValue('city',$city);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
		if($res){
		foreach($res as $key=>$value){
		if(!$final[$value['pid']]){
	 	$final[$value['pid']]=array(
				'user_id'=>$value['uid'],
				'p_name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['lat']?$value['lat']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['lng']?$value['lng']:"",
				"post_id"=>$value['pid']?$value['pid']:"",
				"post_title"=>$value['title']?$value['title']:"",
				'post_city'=>$value['post_city']?$value['post_city']:"",
				"post_description"=>$value['description']?$value['description']:"",
			'post_image'=>$value['post_image']?BASE_PATH."timthumb.php?src=uploads/".$value['post_image']:"",
				'post_time'=>$value['post_time']?$value['post_time']:"",
				'likes_count'=>$value['likes_count']?$value['likes_count']:0,
				'is_liked'=>$value['is_liked']?$value['is_liked']:'0',
				'comments'=>array()
				);
			}
		
			
		if(!ISSET($final[$value['pid']]['comments'][$value['cid']])){
		if($value['cid']){
			$final[$value['pid']]['comments'][$value['cid']]=array(
				"comment_id"=>$value['cid']?$value['cid']:"",
				"user_id"=>$value['c_uid']?$value['c_uid']:"",
				'profile_pic'=>$value['c_profile_pic']?BASE_PATH."timthumb.php?src=uploads/".$value['c_profile_pic']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				"name"=>$value['un']?$value['un']:"",
				'comment'=>$value['comment']?$value['comment']:"",
				'comment_time'=>$value['comment_time']?$value['comment_time']:""
				);
			   }	
			}	
		}	
        }
       if($final){
        	foreach($final as $key=>$value){
		$data2=array();
		foreach($value['comments'] as $val){
		$data2[]=$val;
		}
		$value['comments']=$data2;		
		$result[]=$value;
	}
        }
        
	return $result;
	}


	
	public static function get_user_posts($user_id){
	
	global $conn;
	
	
	$sql="select rc_users.*,rc_users.id as uid,rc_posts.*,rc_posts.city as post_city,rc_posts.id as pid,rc_comments.id as cid,rc_comments.user_id as c_uid,rc_comments.*,(select count(rc_likes.id) from rc_likes 
		where rc_likes.post_id=rc_posts.id and rc_likes.user_id='$user_id') as is_liked,(select count(rc_likes.id) from rc_likes where rc_likes.post_id=rc_posts.id) as likes_count,
(select rc_users.name from rc_users where rc_users.id=rc_comments.user_id) as un,(select rc_users.image from rc_users where rc_users.id=rc_comments.user_id) as c_profile_pic,
	CASE 
                  WHEN DATEDIFF(NOW(),rc_posts.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_posts.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_posts.created_on)) ,' s ago')
                END as post_time,
	CASE 
                  WHEN DATEDIFF(NOW(),rc_comments.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_comments.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_comments.created_on)) ,' s ago')
                END as comment_time
from rc_posts left join rc_users on rc_users.id=rc_posts.user_id  left join rc_comments on rc_comments.post_id=rc_posts.id where rc_users.is_deleted=0 and rc_posts.user_id=:user_id 
order by rc_posts.created_on DESC";
	$sth=$conn->prepare($sql);	
	$sth->bindValue('user_id',$user_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
		if($res){
		foreach($res as $key=>$value){
		if(!$final[$value['pid']]){
	 	$final[$value['pid']]=array(
				'user_id'=>$value['uid'],
				'p_name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['lat']?$value['lat']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['lng']?$value['lng']:"",
				"post_id"=>$value['pid']?$value['pid']:"",
				"post_title"=>$value['title']?$value['title']:"",
				'post_city'=>$value['post_city']?$value['post_city']:"",
				"post_description"=>$value['description']?$value['description']:"",
				'post_image'=>$value['post_image']?BASE_PATH."timthumb.php?src=uploads/".$value['post_image']:"",
				'post_time'=>$value['post_time']?$value['post_time']:"",
				'likes_count'=>$value['likes_count']?$value['likes_count']:0,
				'is_liked'=>$value['is_liked']?$value['is_liked']:'0',
				'comments'=>array()
				);
			}
		
			
		if(!ISSET($final[$value['pid']]['comments'][$value['cid']])){
		if($value['cid']){
			$final[$value['pid']]['comments'][$value['cid']]=array(
				"comment_id"=>$value['cid']?$value['cid']:"",
				"user_id"=>$value['c_uid']?$value['c_uid']:"",
				'profile_pic'=>$value['c_profile_pic']?BASE_PATH."timthumb.php?src=uploads/".$value['c_profile_pic']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				"name"=>$value['un']?$value['un']:"",
				'comment'=>$value['comment']?$value['comment']:"",
				'comment_time'=>$value['comment_time']?$value['comment_time']:""
				);
			   }	
			}	
		}	
        }
       if($final){
        	foreach($final as $key=>$value){
		$data2=array();
		foreach($value['comments'] as $val){
		$data2[]=$val;
		}
		$value['comments']=$data2;		
		$result[]=$value;
	}
        }
        
	return $result;
	}
	
	public static function search_posts($city,$start_time,$end_time,$searchkey,$user_id){
	
	global $conn;
	if($searchkey)
		$param="and rc_posts.title LIKE '%{$searchkey}%'";

	if($start_time && $end_time)
		$time_condition="and rc_posts.created_on BETWEEN ('$start_time') AND ('$end_time')";

	$sql="select rc_users.*,rc_users.id as uid,rc_posts.*,rc_posts.id as pid,rc_comments.id as cid,rc_comments.user_id as c_uid,rc_comments.*,
		(select rc_users.name from rc_users where rc_users.id=rc_comments.user_id) as un,

CASE 
                  WHEN DATEDIFF(NOW(),rc_posts.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_posts.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_posts.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_posts.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_posts.created_on)) ,' s ago')
                END as post_time,
CASE 
                  WHEN DATEDIFF(NOW(),rc_comments.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_comments.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_comments.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_comments.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_comments.created_on)) ,' s ago')
                END as comment_time
from rc_posts left join rc_users on rc_users.id=rc_posts.user_id  left join rc_comments on rc_comments.post_id=rc_posts.id where rc_users.is_deleted=0 $param
and rc_posts.city=:city $time_condition";
	$sth=$conn->prepare($sql);	
	$sth->bindValue('city',$city);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
if($res){
		foreach($res as $key=>$value){
		if(!$final[$value['pid']]){
	 	$final[$value['pid']]=array(
				'user_id'=>$value['uid'],
				'p_name'=>$value['name']?$value['name']:"",
				'latitude'=>$value['lat']?$value['lat']:"",
				'profile_pic'=>$value['image']?BASE_PATH."timthumb.php?src=uploads/".$value['image']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				'longitude'=>$value['lng']?$value['lng']:"",
				"post_id"=>$value['pid']?$value['pid']:"",
				"post_title"=>$value['title']?$value['title']:"",
				'post_city'=>$value['post_city']?$value['post_city']:"",
				"post_description"=>$value['description']?$value['description']:"",
				'post_image'=>$value['post_image']?BASE_PATH."timthumb.php?src=uploads/".$value['post_image']:"",
				'post_time'=>$value['post_time']?$value['post_time']:"",
				'likes_count'=>$value['likes_count']?$value['likes_count']:0,
				'is_liked'=>$value['is_liked']?$value['is_liked']:'0',
				'comments'=>array()
				);
			}
		
			
		if(!ISSET($final[$value['pid']]['comments'][$value['cid']])){
		if($value['cid']){
			$final[$value['pid']]['comments'][$value['cid']]=array(
				"comment_id"=>$value['cid']?$value['cid']:"",
				"user_id"=>$value['c_uid']?$value['c_uid']:"",
				'profile_pic'=>$value['c_profile_pic']?BASE_PATH."timthumb.php?src=uploads/".$value['c_profile_pic']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
				"name"=>$value['un']?$value['un']:"",
				'comment'=>$value['comment']?$value['comment']:"",
				'comment_time'=>$value['comment_time']?$value['comment_time']:""
				);
			   }	
			}	
		}	
        }
       if($final){
        	foreach($final as $key=>$value){
		$data2=array();
		foreach($value['comments'] as $val){
		$data2[]=$val;
		}
		$value['comments']=$data2;		
		$result[]=$value;
	}
        }
        
	return $result;
	}
	
	
	public static function get_push_ids($user_id){
	global $conn;
	
	$sql="select rc_users.* from rc_users where rc_users.id=:user_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
		
	return $res;
	}
	
	
		
	public static function get_messages($uid1,$uid2){
	
	global $conn;
	$sql="select rc_users.name,rc_users.image as profile_pic,rc_users.id as uid,rc_messages.id as mid,rc_messages.message,
CASE 
                  WHEN DATEDIFF(NOW(),rc_messages.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_messages.created_on) ,'d ago')

                  WHEN HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_messages.created_on)) ,' s ago')
                END as message_time
from rc_messages join rc_users on rc_messages.user_id_sender=rc_users.id where rc_messages.user_id_reciever=:uid1 and  rc_messages.user_id_sender =:uid2
	UNION DISTINCT select rc_users.name,rc_users.image,rc_users.id as uid,rc_messages.id as mid,rc_messages.message,
CASE 
                  WHEN DATEDIFF(NOW(),rc_messages.created_on) != 0 THEN CONCAT(DATEDIFF(NOW(),rc_messages.created_on) ,'d ago')
                  WHEN HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(HOUR(TIMEDIFF(NOW(),rc_messages.created_on)) ,'h ago')
                  WHEN MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) != 0 THEN CONCAT(MINUTE(TIMEDIFF(NOW(),rc_messages.created_on)) ,'m ago')
                  ELSE
                     CONCAT(SECOND(TIMEDIFF(NOW(),rc_messages.created_on)) ,' s ago')
                END as message_time
from rc_messages join rc_users on rc_messages.user_id_sender = rc_users.id where rc_messages.user_id_sender=:uid1 and rc_messages.user_id_reciever =:uid2";
	
	$sth=$conn->prepare($sql);
	$sth->bindValue('uid1',$uid1);
	$sth->bindValue('uid2',$uid2);
	try{$sth->execute();}
	catch(Exception $e){}
	$res=$sth->fetchAll();
	
	if($res){
	foreach($res as $key=>$value){
	 $data[]=array(
        "user_id"=>$value['uid'],
        "name"=>$value['name']?$value['name']:"",
        "profile_pic"=>$value['profile_pic']?BASE_PATH."/timthumb.php?src=uploads/".$value['profile_pic']:BASE_PATH."timthumb.php?src=uploads/no_image.png",
        "message_id"=>$value['mid']?$value['mid']:"",
        "message_text"=>$value['message']?$value['message']:"",
        "message_time"=>$value['message_time']?$value['message_time']:""
        );
        }
        }
	return $data;
	}
	
	public static function getBasePath(){
	return BASE_PATH."/timthumb.php?src=uploads/";
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