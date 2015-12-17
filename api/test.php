<?php
require_once('../php_include/db_connection.php');
require_once('../classes/AllClasses.php');

//Set round for each game 
$players = range(1, 31);
//$players = ["A","B","C","D","E","F","G","H","I","J","L","M","N","O","P"];
    $count = count($players);
	//$courts=["C1","C2","C3","C4","C5","C6","C7","C8"];
	$courts=["C1","C2","C3"];	
	$start_time="2015-11-24 12:00:20";
	//$start_date = gmdate('Y-m-d',strtotime(date("Y-m-d", strtotime($start_date)) . " +7 days"));
	$time=array();
	
	array_push($time,$start_time,date('H:i:s',strtotime('+1 hour', strtotime($start_time))),date('H:i:s',strtotime('+2 hour', strtotime($start_time))),date('H:i:s',strtotime('+3 hour', strtotime($start_time))));
	
	//$time=["T1","T2","T3","T4"];
	
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
	// Print match list. Round 1
    for ($i = 0; $i < $count; $i++) {
	
		if(count($courts)>=$count/2){
		$team['T1'][]=$players[$i];
		$team['T2'][]=$players[++$i];
		$team['court'][]= $courts[$i/2];
		}
		//printf('%s vs %s at %s starting at %s <br />%s', $players[$i], $players[++$i], $courts[$i/2], $time[0], PHP_EOL);
		else{
		if($courts[$i/2]){
		$team['T1'][]=$players[$i];
		$team['T2'][]=$players[++$i];
		$team['court'][]= $courts[$i/2];
		}
		//printf('%s vs %s at %s starting at %s <br />%s', $players[$i], $players[++$i], $courts[$i/2], $time[$i/2], PHP_EOL);
		else{
		shuffle($courts);
		//shuffle($time);
		$team['T1'][]=$players[$i];
		$team['T2'][]=$players[++$i];
		$team['court'][]= $courts[0];
		//printf('%s vs %s at %s starting at %s <br />%s', $players[$i], $players[++$i], $courts[0], $time[0], PHP_EOL);
		}
		
		}
    }
	
	
	$team['time']=array();
	
	for($i=0;$i<count($team['T1']);$i++){
	
		for($j=0;$j<$i;$j++){
		
		if($team['court'][$j]==$team['court'][$i])
		$team['time'][$i]=date('Y-m-d H:i:s',strtotime('+'.$i.' hour', strtotime($start_time)));
		else
		$team['time'][$i]=date('Y-m-d H:i:s',strtotime($start_time));
		
		}
	}
	
	for($i=0;$i<count($team['time']);$i++){
	
	if(date('H:i:s',strtotime($team['time'][$i]))>=date('H:i:s',strtotime('20:00:00'))){
		$team['time'][$i]=date('Y-m-d H:i:s',strtotime('+12 hours',strtotime($team['time'][$i])));
	}
	elseif(date('H:i:s',strtotime($team['time'][$i]))<=date('H:i:s',strtotime('10:00:00'))){
		$team['time'][$i]=date('Y-m-d H:i:s',strtotime('+6 hours',strtotime($team['time'][$i])));
	}
	
	}
	print_r($team);die;
	/*if(date([$i])>=)
		$team['time'][$i]=date('H:i:s',strtotime)
	}
	*/
	/*$players = ["A","B","C","D","E","F","G","H","I","J","L","M","N","O","P","Q"];
    shuffle($players);
    $players = array_chunk($players, 2);
	$courts=["C1","C2","C3"];


    foreach($players as $match => $player)
        //echo "Match " . ($match+1) . ": " . $player[0] . "x" . $player[1] . "<br>";
		if(count($courts)>=count($players)){
		$court=array_chunk($court,2);
		}
		$round1[]=$player[0].','.$player[1];*/
