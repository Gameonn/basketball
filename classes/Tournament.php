<?php
class Tournament{

public static function StartEvent($user_id,$competition_level,$league_level,$event_status){

global $conn;
	$sql="INSERT into event(id,user_id,competition_level,league_level,public_status,created_on) values(DEFAULT,:user_id,:competition_level,:league_level,:public_status,UTC_TIMESTAMP())";
	$sth=$conn->prepare($sql);
	$sth->bindValue('user_id',$user_id);
	$sth->bindValue('competition_level',$competition_level);
	$sth->bindValue('league_level',$league_level);
	$sth->bindValue('public_status',$event_status);
	try{ 
	$sth->execute();
	$event_id=$conn->lastInsertId();		
	}
	catch(Exception $e){}

	return $event_id;
}


public static function getTeamCount($tour_id,$event_id){

global $conn;
$sql="SELECT team_count FROM event_tournament WHERE id=:tour_id and event_id=:event_id";
$sth=$conn->prepare($sql);
$sth->bindValue('tour_id',$tour_id);
$sth->bindValue('event_id',$event_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
$team_count=$result[0]['team_count'];

return $team_count;
}

public static function getCourtCount($tour_id,$event_id){

global $conn;
$sql="SELECT court_count FROM event_tournament WHERE id=:tour_id and event_id=:event_id";
$sth=$conn->prepare($sql);
$sth->bindValue('tour_id',$tour_id);
$sth->bindValue('event_id',$event_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
$court_count=$result[0]['court_count'];

return $court_count;
}

public static function getTourType($tour_id){

global $conn;
$sql="SELECT tournament_type FROM event_tournament WHERE id=:tour_id";
$sth=$conn->prepare($sql);
$sth->bindValue('tour_id',$tour_id);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);
$tournament_type=$result[0]['tournament_type'];

return $tournament_type;
}


public static function AddTourTeam($user_id,$team_name,$team_logo,$team_color_away,$team_color_home){

global $conn;
$sql="SELECT * FROM event_tournament_team WHERE team_name=:team_name";
$sth=$conn->prepare($sql);
$sth->bindValue('team_name',$team_name);
try{$sth->execute();}
catch(Exception $e){}
$result=$sth->fetchAll(PDO::FETCH_ASSOC);

if(!$result){

$sql="INSERT into event_tournament_team(id,user_id,team_name,team_logo,team_color_home,team_color_away,event_type,created_on) VALUES(DEFAULT,:user_id,:team_name,:team_logo,:team_color_home,:team_color_away,3,UTC_TIMESTAMP())";
$sth=$conn->prepare($sql);
$sth->bindValue('team_name',$team_name);
$sth->bindValue('user_id',$user_id);
$sth->bindValue('team_logo',$team_logo);
$sth->bindValue('team_color_away',$team_color_away);
$sth->bindValue('team_color_home',$team_color_home);
try{$sth->execute();
$team_id=$conn->lastInsertId();
}
catch(Exception $e){}
}

return $team_id;
}

		
}
?>