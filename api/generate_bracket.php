<?php
//this is an api to generate tournament schedule

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

$success=$msg="0";$data=array();
// +-----------------------------------+
// + STEP 2: get data				   +
// +-----------------------------------+


$token=$_REQUEST['token'];
$event_id=$_REQUEST['event_id'];
$tour_id=$_REQUEST['tour_id'];

if(!($token && $event_id && $tour_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
// +-----------------------------------+
// + STEP 3: perform operations		   +
// +-----------------------------------+

	$user_id=Users::getUserId($token);
	
	if($user_id){
	
	$team_count=Tournament::getTeamCount($tour_id,$event_id);
	$court_count=Tournament::getCourtCount($tour_id,$event_id);
	$tour_type=Tournament::getTourType($tour_id);
	
	$players=array();
	$courts=array();
	
	for ($x=1; $x <=$team_count ; $x++) { 
		array_push($players, 'T'.$x);
	}
	
	shuffle($players);
    $count = count($players);	//players_count

	for ($x=1; $x <=$court_count ; $x++) { 
		array_push($courts, 'C'.$x);
	}
	
	$start_date=Scheduling::getStartTime($tour_id);

	$winner_bracket='W';
	$loser_bracket='L';

	$start_time = date('Y-m-d H:i:s', strtotime($start_date));
	$time=array();
	
	array_push($time,$start_time,date('H:i:s',strtotime('+1 hour', strtotime($start_time))),date('H:i:s',strtotime('+2 hour', strtotime($start_time))),date('H:i:s',strtotime('+3 hour', strtotime($start_time))));
	
	if($tour_type==3){
		
	$match_per_day=Scheduling::getMatchCount($tour_id);
	$day_count=Scheduling::getDayGap($tour_id);
	$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$time[0]);

		if($allocated_count<$match_per_day){
			$allocate_time=$time[0];
		}
		else{
			
				for ($i=1; $i < $day_count ; $i++) { 
					
					$new_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d 10:00:00", strtotime($time[0])) . " +1 day"));
					$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$new_time);
					if($allocated_count<=$match_per_day){
						$allocate_time = $new_time;	
					}
				}
			}
			
			for ($i=count($players); $i >0; $i--) { 
		
			for ($j=1; $j < $i; $j++) { 
			
				if($i!=$j)
				$team[]=$i.','.$j;
			}

		}

		shuffle($team);
		
		foreach($team as $value){
			$e=explode(',',$value);
			shuffle($courts);
			//echo $e[0].' ';
			//echo $e[1].'<br>';
			Scheduling::RobinLevelMatch($event_id,$tour_id,$e[0],$e[1],$courts[0],$allocate_time,1,$winner_bracket);
	    

		}
	
	}
	else{
	
	 // Order players.
    for ($i = 0; $i < log($count / 2, 2); $i++) {
        $out = array();

        foreach ($players as $player) {
            $splice = pow(2, $i);
			$out = array_merge($out, array_splice($players, 0, $splice));
			$out = array_merge($out, array_splice($players, -$splice));
        }
		
        $players = $out;
    }
    
	$team=array();

	$match_per_day=Scheduling::getMatchCount($tour_id);
	$day_count=Scheduling::getDayGap($tour_id);
	$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$time[0]);

		if($allocated_count<$match_per_day){
			$allocate_time=$time[0];
		}
		else{
			
			for ($i=1; $i < $day_count ; $i++) { 
				
				$new_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d 10:00:00", strtotime($time[0])) . " +1 day"));
				$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$new_time);
				if($allocated_count<=$match_per_day){
					$allocate_time = $new_time;	
				}
			}
		}
			
	// Print match list. Round 1
    for ($i = 0; $i < $count; $i++) {
	
		if(count($courts)>=$count/2){
		$team['T1'][]=$players[$i];
		$team['T2'][]=$players[++$i];
		$team['court'][]= $courts[$i/2];
		$team['time'][]=$allocate_time;
		Scheduling::saveEventMatch($event_id,$tour_id,$team['T1'][$i/2],$team['T2'][$i/2],$team['court'][$i/2],$allocate_time);
		}
		else{
		
		shuffle($courts);
		$team['T1'][]=$players[$i];
		$team['T2'][]=$players[++$i];
		$team['court'][]= $courts[0];
		$team['time'][]=$allocate_time;
		Scheduling::saveEventMatch($event_id,$tour_id,$team['T1'][$i/2],$team['T2'][$i/2],$team['court'][$i/2],$allocate_time);
				
		}
    }

    	//BYE CASE
    	//n - no of rounds in the tournament
   		$n=Scheduling::getRounds($tour_id);
   		
   		for($y=2;$y<=$n;$y++){

    		$last_allocated_time=Scheduling::getLastTimeAllocation($tour_id,$y-1);
			$bye_team=Scheduling::getBye($tour_id,$y-1);
			$matches=Scheduling::getMatchesByRound($tour_id,$y-1);
			$loss_bracket_matches=Scheduling::getLosingMatchesByRound($tour_id,$y-1);			
			
			$match_per_day=Scheduling::getMatchCount($tour_id);
			$day_count=Scheduling::getDayGap($tour_id);
			$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$last_allocated_time);

				if($allocated_count<$match_per_day){
					$allocate_time1=$last_allocated_time;
				}
				else{
				
					for ($m=1; $m < $day_count ; $m++) { 

						$new_time1 = date('Y-m-d H:i:s',strtotime(date("Y-m-d 10:00:00", strtotime($last_allocated_time)) . " +1 day"));
						$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$new_time1);
						if($allocated_count<=$match_per_day){
							$allocate_time1 = $new_time1;	
						}
					}
				}


			$matches=array_reverse($matches);
			foreach($matches as $k => $value){
				shuffle($courts);
				if($bye_team){
					Scheduling::nextLevelMatch($event_id,$tour_id,$bye_team,$matches[$k]['id'],$courts[0],$allocate_time1,$y,$winner_bracket);
	    		}
				
    			Scheduling::nextLevelMatch($event_id,$tour_id,$matches[$k]['id'],$matches[$k+1]['id'],$courts[0],$allocate_time1,$y,$winner_bracket);
				if($tour_type==2)
    			Scheduling::nextLevelMatch($event_id,$tour_id,$value['id'],$matches[$k+1]['id'],$courts[0],$allocate_time1,$y,$loser_bracket);
	    	}
			
			
			if($tour_type==2){
			
			$loss_bracket_matches= array_reverse($loss_bracket_matches);
				$loser_bye=Scheduling::getLoserBye($tour_id,$y-1);

				foreach ($loss_bracket_matches as $k => $value) {
					if(count($loser_bye)==1)
		    		Scheduling::nextLevelMatch($event_id,$tour_id,$loser_bye[0]['id'],$value['id'],$courts[0],$allocate_time1,$y,$loser_bracket);
		    		elseif(count($loser_bye)>1)
		    		Scheduling::nextLevelMatch($event_id,$tour_id,$loser_bye[0]['id'],$loser_bye[1]['id'],$courts[0],$allocate_time1,$y,$loser_bracket);

					shuffle($courts);
					Scheduling::nextLevelMatch($event_id,$tour_id,$value['id'],$loss_bracket_matches[$k+1]['id'],$courts[0],$allocate_time1,$y,$loser_bracket);
					
				}
			
			}
				
    	}
		
		if($tour_type==2){
			$penultimate_game=Scheduling::getFinalGame($tour_id,$event_id);
    		Scheduling::nextLevelMatch($event_id,$tour_id,$penultimate_game[0]['wid'],$penultimate_game[0]['lid'],$courts[0],$allocate_time1,$n,$loser_bracket);

    		$final_game=Scheduling::getFinalGame($tour_id,$event_id);
    		Scheduling::nextLevelMatch($event_id,$tour_id,$final_game[0]['wid'],$final_game[0]['lid'],$courts[0],$allocate_time1,$n,$winner_bracket);
		}
	
	}
	
	$success='1';
	$msg="Bracket Generated";
		
		
	}
	else{
	$success='0';
	$msg="Token Expired";
	}	
}
// +-----------------------------------+
// + STEP 4: send json data		    +
// +-----------------------------------+
if($success==1){
echo json_encode(array("success"=>$success,"msg"=>$msg,"tour_type"=>$tour_type));
}
else
echo json_encode(array("success"=>$success,"msg"=>$msg));
?>