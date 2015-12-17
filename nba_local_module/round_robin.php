<?php
require_once('php_include/db_connection.php');
require_once('classes/AllClasses.php');

$event_id=7;
$tour_id=8;

//Set round for each game 
$players = range(1,8);
	//$players = ["A","B","C","D","E","F","G","H"];
	shuffle($players);

    $count = count($players);

	//$courts=["C1","C2","C3","C4","C5","C6","C7","C8"];
	$courts=["C1","C2","C3"];	
	$start_date=Scheduling::getStartTime($tour_id);
	$winner_bracket='W';
	$team=array();


	$last_allocated_time=Scheduling::getRobinTimeAllocation($tour_id);
	if(!$last_allocated_time)
		$last_allocated_time=$start_date;

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
			Scheduling::RobinLevelMatch($event_id,$tour_id,$e[0],$e[1],$courts[0],$allocate_time1,1,$winner_bracket);
	    

		}

