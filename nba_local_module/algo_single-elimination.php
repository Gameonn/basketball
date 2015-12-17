<?php
require_once('php_include/db_connection.php');
require_once('classes/AllClasses.php');

$event_id=2;
$tour_id=3;

//Set round for each game 

$players=array();
/*for ($x=1; $x <=$team_count ; $x++) { 
	array_push($players, 'T'.$x);
}

$players = range(1,$n);*/
$players = ["A","B","C","D","E","F","G"];
	shuffle($players);
    $count = count($players);

	//$courts=["C1","C2","C3","C4","C5","C6","C7","C8"];
	$courts=["C1","C2"];	
	$start_date=Scheduling::getStartTime($tour_id);

	$winner_bracket='W';
	$loser_bracket='L';

	$start_time = date('Y-m-d H:i:s', strtotime($start_date));
	$time=array();
	
	array_push($time,$start_time,date('H:i:s',strtotime('+1 hour', strtotime($start_time))),date('H:i:s',strtotime('+2 hour', strtotime($start_time))),date('H:i:s',strtotime('+3 hour', strtotime($start_time))));
	
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
		Scheduling::saveEventMatch($event_id,$tour_id,$team['T1'][$i/2],$team['T2'][$i/2],$team['court'][$i/2],$allocate_time,0);
		}
		else{
		
		shuffle($courts);
		$team['T1'][]=$players[$i];
		$team['T2'][]=$players[++$i];
		$team['court'][]= $courts[0];
		$team['time'][]=$allocate_time;
		Scheduling::saveEventMatch($event_id,$tour_id,$team['T1'][$i/2],$team['T2'][$i/2],$team['court'][$i/2],$allocate_time,0);
				
		}
    }


    	//BYE CASE
    	//n - no of rounds in the tournament
   		$n=Scheduling::getRounds($tour_id);
   		
   		for($y=2;$y<=$n;$y++){

    		$last_allocated_time=Scheduling::getLastTimeAllocation($tour_id,$y-1);
			$bye_team=Scheduling::getBye($tour_id,$y-1);
			$matches=Scheduling::getMatchesByRound($tour_id,$y-1);
			
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
			//for($k=count($matches)-1; $k>=0; $k--){
				shuffle($courts);
				if($bye_team){
					Scheduling::nextLevelMatch($event_id,$tour_id,$bye_team,$matches[$k]['id'],$courts[0],$allocate_time1,$y,$winner_bracket);
	    		}
					
	    		////echo $bye_team.','.$value['id'].'<br>';
	    			//echo $value['id'].' '.$matches[$k+1]['id'];
    			Scheduling::nextLevelMatch($event_id,$tour_id,$matches[$k]['id'],$matches[$k+1]['id'],$courts[0],$allocate_time1,$y,$winner_bracket);
	    	}
			//}
				

    	}
    	
