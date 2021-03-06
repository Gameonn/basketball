<?php
require_once('../php_include/db_connection.php');
require_once('../classes/AllClasses.php');

$token = $_REQUEST['token'];
$player_id = $_REQUEST['player_id'];
$event_id = $_REQUEST['event_id'];
$event_type = $_REQUEST['event_type'];
$success='0';$msg='0';

if(!($token && $player_id && $event_id && $event_type)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 

	$uid=Users::getUserId($token);
	if($uid){
	
	$sql="SELECT * from event_favorite_players where fav_by=:uid and fav_to=:user_id2 and event_id=:event_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('uid',$uid);
	$sth->bindValue('user_id2',$player_id);
	$sth->bindValue('event_id',$event_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(count($result)){
	
	$success='0';
	$msg="Already Favorite this player";
	}
	else{
	
	$sql="INSERT into event_favorite_players(id,event_id,event_type,fav_by,fav_to,created_on) VALUES(DEFAULT,:event_id,:event_type,:uid,:user_id2,NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('uid',$uid);
	$sth->bindValue('user_id2',$player_id);
	$sth->bindValue('event_id',$event_id);
	$sth->bindValue('event_type',$event_type);
	try{$sth->execute();
	$success='1';
	$msg="Player Added to Favorite";
	}
	catch(Exception $e){}
	}
	}
	else{
	$success='0';
	$msg="Token Expired";
	}

}
echo json_encode(array('success'=>$success,'msg'=>$msg));

?>
