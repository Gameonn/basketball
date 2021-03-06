<?php
class Scheduling
{


	public static function getStartTime($tour_id){

	global $conn;
	$sql="SELECT start_date from event_tournament WHERE id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$tour_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$start_date=$result[0]['start_date'];

	return $start_date;
	}


	public static function getTeamCount($tour_id){

	global $conn;
	$sql="SELECT team_count from event_tournament WHERE id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$tour_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$team_count=$result[0]['team_count'];

	return $team_count;
	}

	public static function getDayGap($tour_id){

	global $conn;
	$sql="SELECT DATE(end_date)-DATE(start_date) as day_count FROM `event_tournament` WHERE id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$tour_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$day_count=$result[0]['day_count']?$result[0]['day_count']:'1';

	return $day_count;
	}


	public static function getRounds($tour_id){

	global $conn;
	$sql="SELECT team_count,tournament_type as tour_type from event_tournament WHERE id=:tour_id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$team_count=$result[0]['team_count'];
	$tour_type=$result[0]['tour_type'];
	
	/*if($team_count>2 && $team_count<=4)
		$rounds=2;
	elseif($team_count>4 && $team_count<=8)
		$rounds=3;
	elseif ($team_count>8 && $team_count<=16)
		$rounds=4;
	elseif ($team_count>16 && $team_count<=32)
		$rounds=5;
	else
		$rounds=6;*/
		if($tour_type==1)
		$rounds=ceil(log($team_count,2));
		else
		$rounds=ceil(log($team_count,2)) + ceil(log(log($team_count,2),2));

	return $rounds;
	}


	public static function getMatchCount($tour_id){

	global $conn;
	//tour_type=1 for single 2- double 3 for round robin

	$sql="SELECT event_tournament.*,(SELECT DATE(end_date)-DATE(start_date) as day_count FROM `event_tournament` WHERE id=:id) as day_count from event_tournament WHERE id=:id";
	$sth=$conn->prepare($sql);
	$sth->bindValue('id',$tour_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$tour_type=$result[0]['tournament_type'];
	$day_count=$result[0]['day_count']?$result[0]['day_count']:'1';
	$team_count=$result[0]['team_count'];

	if($tour_type==1)
		$match_count=$team_count-1;
	elseif($tour_type==2)
		$match_count=2*($team_count)-2;
	else
		$match_count=($team_count)*($team_count-1)/2;

	if($day_count==1)
	$match_per_day=$match_count;
	else
	$match_per_day=ceil(($match_count-1)/($day_count));

	return $match_per_day;
	}

	public static function getPreviousCourtAllocation($location_id){

	global $conn;
	$sql="SELECT start_time from test_game WHERE court_id=:location_id ORDER BY id DESC LIMIT 1";
	$sth=$conn->prepare($sql);
	$sth->bindValue('location_id',$location_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$start_date=$result[0]['start_time'];

	return $start_date;

	}


	public static function getLastTimeAllocation($tour_id,$round){

	global $conn;
	$sql="SELECT start_time from test_game WHERE tour_id=:tour_id and round=:round ORDER BY id DESC LIMIT 1";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$start_date=$result[0]['start_time'];

	return $start_date;

	}


	public static function getRobinTimeAllocation($tour_id){

	global $conn;
	$sql="SELECT start_time from test_game WHERE tour_id=:tour_id ORDER BY id DESC LIMIT 1";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$start_date=$result[0]['start_time'];

	return $start_date;

	}

	public static function getTimeAllocationDay($tour_id,$time){

	global $conn;
	$sql="SELECT count(id) as count from test_game WHERE tour_id=:tour_id and DATE(start_time)=DATE(:time)";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('time',$time);
	try{$sth->execute();}
	catch(Exception $e){}
	$result=$sth->fetchAll(PDO::FETCH_ASSOC);
	$count=$result[0]['count'];

	return $count;

	}

	public static function getBye($tour_id,$round){

	global $conn;
	if($round==1)
	$sql="SELECT id FROM `test_game` WHERE (team1_id='0' OR team2_id='0') AND round=:round and tour_id=:tour_id and bracket_flag='W' ";	
	else
	$sql="SELECT id FROM `test_game` WHERE (team1_parent=0 OR team2_parent=0) AND round=:round and tour_id=:tour_id and bracket_flag='W'";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);
	$bye_team=$game_set[0]['id'];

	return $bye_team;
	}


	public static function getLoserBye($tour_id,$round){

	global $conn;

	$sql="SELECT id FROM `test_game` WHERE (team1_parent=0 OR team2_parent=0) AND round=:round and tour_id=:tour_id and bracket_flag='L'";	
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

	return $game_set;
	}


	//match details without bye
	public static function getMatchesByRound($tour_id,$round){

	global $conn;
	if($round==1)
	$sql="SELECT * FROM `test_game` WHERE round=:round AND tour_id=:tour_id AND team1_id!='0' AND team2_id!='0' ";
	else
	$sql="SELECT * FROM `test_game` WHERE round=:round and tour_id=:tour_id and team1_parent!=0 and team2_parent!=0 and bracket_flag='W'";

	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

	return $game_set;
	}


	//match details with bye
	public static function getMatchesWithBye($tour_id,$round){

	global $conn;
	
	$sql="SELECT * FROM `test_game` WHERE round=:round and tour_id=:tour_id and bracket_flag='W'";

	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

	return $game_set;
	}


	public static function getLosingMatchesByRound($tour_id,$round){

	global $conn;
	
	$sql="SELECT * FROM `test_game` WHERE round=:round and tour_id=:tour_id and team1_parent!=0 and team2_parent!=0 and bracket_flag='L'";

	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

	return $game_set;
	}

	public static function getLosingMatchesWithBye($tour_id,$round){

	global $conn;
	
	$sql="SELECT * FROM `test_game` WHERE round=:round and tour_id=:tour_id and bracket_flag='L'";

	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

	return $game_set;
	}

public static function getRobinRounds($tour_id){

	global $conn;
	
		$team_count=Scheduling::getTeamCount($tour_id);

		if($team_count%2){//odd case
			$rounds=$team_count;
			$match_per_round=($team_count-1)/2;
		}
		else{//even case
			$rounds=$team_count-1;
			$match_per_round=($team_count)/2;
		}

		$game_detail['rounds']=$rounds;
		$game_detail['match_per_round']=$match_per_round;

	return $match_per_round;
	}


	public static function getActiveRound($tour_id){

	global $conn;
	$sql="SELECT round from test_game WHERE tour_id=:tour_id order by id DESC LIMIT 1";
		$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);	
	$round_count=$game_set[0]['round'];

	return $round_count;
	}

	public static function getGameSetCount($tour_id,$round){

	global $conn;	

	$sql="SELECT count(test_game.id) as round_matches from test_game WHERE tour_id=:tour_id and round=:round";
	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);	
	$round_matches=$game_set[0]['round_matches'];

	return $round_matches;
	}


	public static function PhaseSeparation($event_id,$tour_id,$match_array,$courts,$round){

	global $conn;
	
	$p3=array();
	$p4=array();
	$loser_bracket='L';
	$match_per_day=Scheduling::getMatchCount($tour_id);
	$day_count=Scheduling::getDayGap($tour_id);
	$first_loss_phase=2*(count($match_array))-pow(2,ceil(log(count($match_array),2)));//match_array- matches without bye

				foreach ($match_array as $key => $value) {
					if($key<$first_loss_phase)
						array_push($p3, $value);
					else
						array_push($p4, $value);
				}


			//Round1 loss case
			if(count($p3)){
				for ($i = 0; $i < count($p3); $i++) {
	    		$last_allocated_time=Scheduling::getLastTimeAllocation($tour_id,1);
	    		
	    		$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$last_allocated_time);
		    	if($allocated_count<=$match_per_day){
		    		
		    		$allocate_time=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($last_allocated_time)) . " +1 day"));
				}
				else{

					for ($j=1; $j < $day_count ; $j++) { 
						$last_allocated_time=Scheduling::getLastTimeAllocation($tour_id,1);
						$new_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d 10:00:00", strtotime($last_allocated_time)) . " +1 day"));
						$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$new_time);
						if($allocated_count<=$match_per_day){
							$allocate_time = $new_time;	
						}
						else{
							$allocate_time=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($last_allocated_time)) . " +1 hour"));
						}
					}
				}
				
	    		shuffle($courts);
	    		//$t- default bye for round 1
		    	Scheduling::nextLevelMatch($event_id,$tour_id,$p3[$i]['id'],$p3[++$i]['id'],$courts[0],$allocate_time,$round,$loser_bracket);
		    	}
			}

			//Round 1 loss case default bye
			if(count($p4)){
	    		foreach ($p4 as $key => $value) {
	    		$last_allocated_time_1=Scheduling::getLastTimeAllocation($tour_id,1);
	    		$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$last_allocated_time_1);
		    	if($allocated_count<=$match_per_day){
		    		$allocate_time=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($last_allocated_time_1)) . " +1 hour"));
				}
				else{
						
					for ($i=1; $i < $day_count ; $i++) { 
						$last_allocated_time_1=Scheduling::getLastTimeAllocation($tour_id,1);
						$new_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d 10:00:00", strtotime($last_allocated_time_1)) . " +1 day"));
						$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$new_time);
						if($allocated_count<=$match_per_day){
							$allocate_time = $new_time;	
						}
						else{
							$allocate_time=date('Y-m-d H:i:s',strtotime(date("Y-m-d 10:00:00", strtotime($last_allocated_time)) . " +1 hour"));
						}
					}
				}	
	    		shuffle($courts);
	    		//$t- default bye for round 1
		    	Scheduling::nextLevelMatch($event_id,$tour_id,$value['id'],$t,$courts[0],$allocate_time,$round,$loser_bracket);
		    	}
			}

	}


	public static function getFinalGame($tour_id,$event_id){

	global $conn;
	$sql="SELECT id as wid,(SELECT id FROM `test_game` WHERE `event_id` = :event_id AND `tour_id` = :tour_id AND bracket_flag='L' ORDER BY id DESC LIMIT 1)
	 as lid FROM `test_game` WHERE `event_id` = :event_id AND `tour_id` = :tour_id AND bracket_flag='W' ORDER BY id DESC LIMIT 1";
 	$sth=$conn->prepare($sql);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('event_id',$event_id);
	try{$sth->execute();}
	catch(Exception $e){}
	$final_game=$sth->fetchAll(PDO::FETCH_ASSOC);

	return $final_game;
	}


	public static function BracketFormation($tour_id){

		global $conn;
		$final=array();
		$sql="SELECT * from test_game WHERE tour_id=:tour_id";
		$sth=$conn->prepare($sql);
		$sth->bindValue('tour_id',$tour_id);
		try{$sth->execute();}
		catch(Exception $e){}
		$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($game_set as $key => $value) {
			
			$final[$value['round']][]=array(

				"id"=>$value['id'],
				"team1"=>$value['team1_id'],
				"team2"=>$value['team2_id'],
				"team1_parent"=>$value['team1_parent'],
				"team2_parent"=>$value['team2_parent'],
				"round"=>$value['round'],
				"bracket_flag"=>$value['bracket_flag'],
				"start_time"=>$value['start_time'],
				"start_date"=>date('m/d',strtotime($value['start_time'])),
				"time"=>date('h:i A',strtotime($value['start_time'])),
				"end_time"=>$value['end_time'],
				"court"=>$value['court_id']

				);

		}

		foreach($final as $key=>$value){

			$data[]=$value;
		}

	return $data;
	}


	//for round 1 match scheduling
	public static function saveEventMatch($event_id,$tour_id,$team1,$team2,$location_id,$time,$bracket_flag){
		
	global $conn;

	$sql="SELECT * from test_game WHERE tour_id=:tour_id and (team1_id IN (:team1,:team2) OR team2_id IN (:team2,:team1)) and round=1";
	$sth=$conn->prepare($sql);
	$sth->bindValue('team1',$team1);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('team2',$team2);
	try{$sth->execute();}
	catch(Exception $e){
		echo $e->getMessage();
	}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

		if(!count($game_set)){	

		$sql="SELECT * from test_game WHERE tour_id=:tour_id and court_id=:location_id and start_time=:start_time";
		$sth=$conn->prepare($sql);
		$sth->bindValue('location_id',$location_id);
		$sth->bindValue('tour_id',$tour_id);
		$sth->bindValue('start_time',$time);
		try{$sth->execute();}
		catch(Exception $e){}
		$result=$sth->fetchAll(PDO::FETCH_ASSOC);

		if($result){
			$prev_start_time=self::getPreviousCourtAllocation($location_id);
			$start_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($prev_start_time)) . " +1 hour"));
			$end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($prev_start_time)) . " +2 hour"));
		}
		else{
			$start_time = $time;
			$end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($time)) . " +1 hour"));
		}
		
				$team1=$team1?$team1:0;//def value for team as 0 in case of bye
				$team2=$team2?$team2:0;// def value of team in case of bye

		$sql="INSERT into test_game(id,event_id,tour_id,team1_id,team2_id,start_time,end_time,round,bracket_flag,court_id,created_on)
			  VALUES(DEFAULT,:event_id,:tour_id,:team1,:team2,:start_time,:end_time,1,:bracket_flag,:location_id,UTC_TIMESTAMP())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('event_id',$event_id);
		$sth->bindValue('tour_id',$tour_id);
		$sth->bindValue('team1',$team1);
		$sth->bindValue('team2',$team2);
		$sth->bindValue('location_id',$location_id);
		$sth->bindValue('start_time',$start_time);
		$sth->bindValue('end_time',$end_time);
		$sth->bindValue('bracket_flag',$bracket_flag);
		try{$sth->execute();}
		catch(Exception $e){
			echo $e->getMessage();
		}
		
		}
	}




		//for round 2 to n match scheduling
	public static function nextLevelMatch($event_id,$tour_id,$team1,$team2,$location_id,$time,$round,$bracket_flag){
		
	global $conn;

	$sql="SELECT * from test_game WHERE tour_id=:tour_id and (team1_parent IN (:team1,:team2) OR team2_parent IN (:team2,:team1)) and round=:round and bracket_flag=:bracket_flag";
	$sth=$conn->prepare($sql);
	$sth->bindValue('team1',$team1);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('team2',$team2);
	$sth->bindValue('round',$round);
	$sth->bindValue('bracket_flag',$bracket_flag);
	try{$sth->execute();}
	catch(Exception $e){}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

		if(!count($game_set)){	

		$sql="SELECT * from test_game WHERE tour_id=:tour_id and court_id=:location_id and start_time=:start_time";
		$sth=$conn->prepare($sql);
		$sth->bindValue('location_id',$location_id);
		$sth->bindValue('tour_id',$tour_id);
		$sth->bindValue('start_time',$time);
		try{$sth->execute();}
		catch(Exception $e){
			$e->getMessage();
		}
		$result=$sth->fetchAll(PDO::FETCH_ASSOC);

		if($result){
			$prev_start_time=self::getPreviousCourtAllocation($location_id);
			$start_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($prev_start_time)) . " +1 hour"));
			$end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($prev_start_time)) . " +2 hour"));
		}
		else{
			$start_time = $time;
			$end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($time)) . " +1 hour"));
		}
		
				$team1=$team1?$team1:0;//def value for team as 0 in case of bye
				$team2=$team2?$team2:0;// def value of team in case of bye

		$sql="INSERT into test_game(id,event_id,tour_id,team1_parent,team2_parent,start_time,end_time,round,bracket_flag,court_id,created_on)
			  VALUES(DEFAULT,:event_id,:tour_id,:team1,:team2,:start_time,:end_time,:round,:bracket_flag,:location_id,UTC_TIMESTAMP())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('event_id',$event_id);
		$sth->bindValue('tour_id',$tour_id);
		$sth->bindValue('team1',$team1);
		$sth->bindValue('team2',$team2);
		$sth->bindValue('round',$round);
		$sth->bindValue('location_id',$location_id);		
		$sth->bindValue('bracket_flag',$bracket_flag);
		$sth->bindValue('start_time',$start_time);
		$sth->bindValue('end_time',$end_time);
		try{$sth->execute();}
		catch(Exception $e){
			echo $e->getMessage();
		}
		
		}
	}


			//for round robin match scheduling
	public static function RobinLevelMatch($event_id,$tour_id,$team1,$team2,$location_id,$time,$round,$bracket_flag){
		
	global $conn;

	$sql="SELECT * from test_game WHERE tour_id=:tour_id and (team1_parent IN (:team1,:team2) AND team2_parent IN (:team2,:team1)) and round=:round";
	$sth=$conn->prepare($sql);
	$sth->bindValue('team1',$team1);
	$sth->bindValue('tour_id',$tour_id);
	$sth->bindValue('team2',$team2);
	$sth->bindValue('round',$round);
	try{$sth->execute();}
	catch(Exception $e){
		echo $e->getMessage();
	}
	$game_set=$sth->fetchAll(PDO::FETCH_ASSOC);

		if(!count($game_set)){	

		$sql="SELECT * from test_game WHERE tour_id=:tour_id and court_id=:location_id and start_time=:start_time";
		$sth=$conn->prepare($sql);
		$sth->bindValue('location_id',$location_id);
		$sth->bindValue('tour_id',$tour_id);
		$sth->bindValue('start_time',$time);
		try{$sth->execute();}
		catch(Exception $e){}
		$result=$sth->fetchAll(PDO::FETCH_ASSOC);

		if($result){
			$prev_start_time=self::getPreviousCourtAllocation($location_id);
			$start_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($prev_start_time)) . " +1 hour"));
			$end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($prev_start_time)) . " +2 hour"));
		}
		else{
			$start_time = $time;
			$end_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($time)) . " +1 hour"));
		}
		
		$sql="INSERT into test_game(id,event_id,tour_id,team1_id,team2_id,start_time,end_time,round,bracket_flag,court_id,created_on)
			  VALUES(DEFAULT,:event_id,:tour_id,:team1,:team2,:start_time,:end_time,:round,:bracket_flag,:location_id,UTC_TIMESTAMP())";
		$sth=$conn->prepare($sql);
		$sth->bindValue('event_id',$event_id);
		$sth->bindValue('tour_id',$tour_id);
		$sth->bindValue('team1',$team1);
		$sth->bindValue('team2',$team2);
		$sth->bindValue('round',$round);
		$sth->bindValue('location_id',$location_id);		
		$sth->bindValue('bracket_flag',$bracket_flag);
		$sth->bindValue('start_time',$start_time);
		$sth->bindValue('end_time',$end_time);
		try{$sth->execute();}
		catch(Exception $e){
			echo $e->getMessage();
		}
		
		}
	}




}