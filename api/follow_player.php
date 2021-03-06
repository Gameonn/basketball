<?php
require_once('../php_include/db_connection.php');
require_once('../classes/AllClasses.php');

$token = $_REQUEST['token'];
$player_id = $_REQUEST['player_id'];
$success='0';$msg='0';

if(!($token && $player_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{ 

	$user_id=Users::getUserId($token);
	if($user_id){
	
	$sql="SELECT * from players_follow where follow_by=:uid and follow_to=:user_id2";
	$sth=$conn->prepare($sql);
	$sth->bindValue('uid',$user_id);
	$sth->bindValue('user_id2',$player_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll();
	
	if(count($result)){
	
	$success='0';
	$msg="Already following this player";
	}
	else{
	
	$sql="INSERT into players_follow(id,follow_by,follow_to,created_on) VALUES(DEFAULT,:uid,:user_id2,NOW())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('uid',$user_id);
	$sth->bindValue('user_id2',$player_id);
	try{$sth->execute();
	$success='1';
	$msg="Player Added to Follow list";
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
