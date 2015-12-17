<?php
require_once('php_include/db_connection.php');
require_once('classes/AllClasses.php');

$event_id=1;
$tour_id=2;

//Set round for each game 
$team_count=Scheduling::getTeamCount($tour_id);

$players1=array();
$players=array();
$p1=array();//winner case round1
$p2=array();//default bye
$p3=array();//loser case round1
$p4=array();//default bye
$first_phase=2*($team_count)-pow(2,ceil(log($team_count,2)));
$second_phase=$team_count-$first_phase;

	for ($x=1; $x <= $team_count ; $x++) { 
		array_push($players1, 'T'.$x);
		shuffle($players1);
	}

	foreach ($players1 as $key => $value) {
		if($key<$first_phase)
			array_push($p1, $value);
		else
			array_push($p2, $value);

	}

    $count = count($p1);
	$courts=["C1","C2","C3"];	
	$start_date=Scheduling::getStartTime($tour_id);

	$winner_bracket='W';
	$loser_bracket='L';

	$start_time = date('Y-m-d H:i:s', strtotime($start_date));
	$time=array();
	
	array_push($time,$start_time,date('H:i:s',strtotime('+1 hour', strtotime($start_time))),date('H:i:s',strtotime('+2 hour', strtotime($start_time))),date('H:i:s',strtotime('+3 hour', strtotime($start_time))));
	
	$team=array();

	$match_per_day=Scheduling::getMatchCount($tour_id);
	$day_count=Scheduling::getDayGap($tour_id);
		
	
		// Print match list. Round 1
	    for ($i = 0; $i < $count; $i++) {

			$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$time[0]);
		    	if($allocated_count<$match_per_day){
		    		$allocate_time=$time[0];
				}
				else{
						
					for ($j=1; $j < $day_count ; $j++) { 
						$last_allocated_time_1=Scheduling::getLastTimeAllocation($tour_id,1);
						$new_time = date('Y-m-d H:i:s',strtotime(date("Y-m-d 10:00:00", strtotime($last_allocated_time_1)) . " +1 day"));
						$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$new_time);
						if($allocated_count<=$match_per_day){
							$allocate_time = $new_time;	
						}
						else{
							$allocate_time=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($last_allocated_time_1)) . " +1 hour"));
						}
					}
				}

			if(count($courts)>=$count/2){
			$team['T1'][]=$p1[$i];
			$team['T2'][]=$p1[++$i];
			$team['court'][]= $courts[$i/2];
			$team['time'][]=$allocate_time;
			Scheduling::saveEventMatch($event_id,$tour_id,$team['T1'][$i/2],$team['T2'][$i/2],$team['court'][$i/2],$allocate_time,$winner_bracket);
			}
			else{
			
			shuffle($courts);
			$team['T1'][]=$p1[$i];
			$team['T2'][]=$p1[++$i];
			$team['court'][]= $courts[0];
			$team['time'][]=$allocate_time;
			Scheduling::saveEventMatch($event_id,$tour_id,$team['T1'][$i/2],$team['T2'][$i/2],$team['court'][$i/2],$allocate_time,$winner_bracket);
					
			}
	    }

    	if(count($p2)){
    		foreach ($p2 as $key => $value) {
    		$last_allocated_time_1=Scheduling::getLastTimeAllocation($tour_id,1);
    		$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$last_allocated_time_1);
	    	if($allocated_count<=$match_per_day){
	    		$allocate_time=$time[0];
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
							$allocate_time=date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", strtotime($last_allocated_time_1)) . " +1 hour"));
						}
				}
			}	
    		shuffle($courts);
    		//$t- default bye for round 1
	    	Scheduling::saveEventMatch($event_id,$tour_id,$value,$t,$courts[0],$allocate_time,$winner_bracket);
	    	
	    	}
    	}

    		$last_allocated_time=Scheduling::getLastTimeAllocation($tour_id,1);
			$matches=Scheduling::getMatchesWithBye($tour_id,1);
			$matches_wb=Scheduling::getMatchesByRound($tour_id,1);
			$match_per_day=Scheduling::getMatchCount($tour_id);
			$day_count=Scheduling::getDayGap($tour_id);
			$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$last_allocated_time);
			$loss_bracket_matches=Scheduling::getLosingMatchesWithBye($tour_id,1);

				if($allocated_count<=$match_per_day){
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
					
					if($matches[$k+1])
	    			Scheduling::nextLevelMatch($event_id,$tour_id,$matches[$k]['id'],$matches[$k+1]['id'],$courts[0],$allocate_time1,2,$winner_bracket);
		    	}


	    		//winning matches without bye round1
		    	$mwb1=Scheduling::getMatchesByRound($tour_id,1);
		    	$mwb2=Scheduling::getMatchesByRound($tour_id,2);
		    	$mwb=array_merge($mwb1,$mwb2);

	    	
		    	Scheduling::PhaseSeparation($event_id,$tour_id,$mwb,$courts,2);
		

    	//BYE CASE
    	//n - no of rounds in the tournament
   		$n=Scheduling::getRounds($tour_id);
   		
   		for($y=3;$y<=$n;$y++){

    		$last_allocated_time=Scheduling::getLastTimeAllocation($tour_id,$y-1);
			$matches=Scheduling::getMatchesWithBye($tour_id,$y-1);
			if($y>3){
				$matches_wb_next=Scheduling::getMatchesByRound($tour_id,$y-1);
			}
			
			$match_per_day=Scheduling::getMatchCount($tour_id);
			$day_count=Scheduling::getDayGap($tour_id);
			$allocated_count=Scheduling::getTimeAllocationDay($tour_id,$last_allocated_time);
			$loss_bracket_matches=Scheduling::getLosingMatchesWithBye($tour_id,$y-1);

				if($allocated_count<=$match_per_day){
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
					
					if($matches[$k+1])
	    			Scheduling::nextLevelMatch($event_id,$tour_id,$matches[$k]['id'],$matches[$k+1]['id'],$courts[0],$allocate_time1,$y,$winner_bracket);
		    	}
				
				$loss_bracket_matches= array_reverse($loss_bracket_matches);
				$matches_wb_next=array_reverse($matches_wb_next);
				if(count($matches_wb_next)>1)
					$match_array=array_merge_recursive($loss_bracket_matches,$matches_wb_next);
				else
					$match_array=$loss_bracket_matches;
				
				Scheduling::PhaseSeparation($event_id,$tour_id,$match_array,$courts,$y);

				// 	foreach ($loss_bracket_matches as $k => $value) {
						
				// 		shuffle($courts);
				// 		//if($loss_bracket_matches[$k+1])
				// 		Scheduling::nextLevelMatch($event_id,$tour_id,$value['id'],$loss_bracket_matches[$k+1]['id'],$courts[0],$allocate_time1,$y,$loser_bracket);
						
				// 	}

				// if($y>3){
				// 	$matches_wb_next=array_reverse($matches_wb_next);
				// 	foreach($matches_wb_next as $k => $value){
				// 	shuffle($courts);
				// 		//if($matches_wb[$k+1])
	   //  			Scheduling::nextLevelMatch($event_id,$tour_id,$matches_wb_next[$k]['id'],$matches_wb_next[$k+1]['id'],$courts[0],$allocate_time1,$y,$loser_bracket);
	    	
    // 			}
				// }
					

    	}
    	

    		$penultimate_game=Scheduling::getFinalGame($tour_id,$event_id);
    		Scheduling::nextLevelMatch($event_id,$tour_id,$penultimate_game[0]['wid'],$penultimate_game[0]['lid'],$courts[0],$allocate_time1,$n,$loser_bracket);

    		$final_game=Scheduling::getFinalGame($tour_id,$event_id);
    		Scheduling::nextLevelMatch($event_id,$tour_id,$final_game[0]['wid'],$final_game[0]['lid'],$courts[0],$allocate_time1,$n,$winner_bracket);