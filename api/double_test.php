<?php
//testing double elimination 
//API_KEY='mXFrykVhdZemQAJ3pHnOzVCw16qzKFxZ6MUC4UnF'; 2060922
 	//$players = ["A","B","C","D","E"];
    /*shuffle($players);
    $players = array_chunk($players, 2);

    foreach($players as $match => $player)
        echo "Match " . ($match+1) . ": " . $player[0] . "x" . $player[1] . "<br>";
		*/
		/*shuffle($players);

for ($x = 0; $x < count($players); $x += 2) {
  echo "Match " . (($x/2)+1) . ": " . $players[$x] . "x" . $players[$x+1] . "\n";
}
die;*/


/*$competitors=$_REQUEST['competitors'];

$players = range(1, 5);
    $count = count($players);
	
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

    // Print match list.
    for ($i = 0; $i < $count; $i++) {
        printf('%s vs %s<br />%s', $players[$i], $players[++$i], PHP_EOL);
    }
*/
$competitors=array(
    "Louisville",
    "Duke",
    "Gonzaga",
    "Kansas",
    "Indiana",
    "Ohio State"/*,
    "Miami",
    "Georgetown",
    "Florida" ,
    "Michigan State",
    "Kansas State",
    "New Mexico",
    "Michigan",
    "Syracuse",
    "Memphis",
    "Marquette",
    "Virginia Commonwealth University",
    "Saint Louis",
    "Wisconsin",
    "Butler",
    "Arizona",
    "University of Nevada Las Vegas",
    "North Carolina",
    "Missouri",
    "Notre Dame",
    "North Carolina State",
    "Mississippi",
    "Pittsburgh",
    "Illinois",
    "Oklahoma State",
    "University of California Los Angeles",
    "Creighton",
    "San Diego State",
    "Cincinnati",
    "Oklahoma",
    "Minnesota",
    "Oregon",
    "Colorado",
    "Wichita State",
    "Temple",
    "Iowa State",
    "Colorado State",
    "Villanova",
    "California",
    "Belmont",
    "Bucknell",
    "Ole Miss",
    "New Mexico State",
    "Akron",
    "Middle Tennessee State Univeristy",
    "Davidson",
    "Montana",
    "South Dakota State",
    "Harvard",
    "La Salle",
    "Valparaiso",
    "Northwestern State",
    "Florida Gulf Coast University",
    "Iona",
    "Pacific",
    "Albany",
    "Southern University",
    "Western Kentucky",
    "Long Island University",
    "North Carolina A&T State University",*/
);

$rounds = log( count( $competitors ), 2 ) + 1;

// round one
for( $i = 0; $i < log( count( $competitors ), 2 ); $i++ )
{
    $seeded = array( );
    foreach( $competitors as $competitor )
    {
        $splice = pow( 2, $i );

        $seeded = array_merge( $seeded, array_splice( $competitors, 0, $splice ) );
        $seeded = array_merge( $seeded, array_splice( $competitors, -$splice ) );
    }
    $competitors = $seeded;
}
$events = array_chunk( $seeded, 2 );


if( $rounds > 2 )
{
    $round_index = count( $events );

    // second round
    for( $i = 0; $i < count( $competitors ) / 2; $i++ )
    {
        array_push( $events, array(
            array( 'from_event_index' => $i, 'from_event_rank' => 1 ), // rank 1 = winner
            array( 'from_event_index' => ++$i, 'from_event_rank' => 1 )
        ) );
    }

    $round_matchups = array( );
    for( $i = 0; $i < count( $competitors ) / 2; $i++ )
    {
        array_push( $round_matchups, array(
            array( 'from_event_index' => $i, 'from_event_rank' => 2 ), // rank 2 = loser
            array( 'from_event_index' => ++$i, 'from_event_rank' => 2 )
        ) );
    }
    $events = array_merge( $events, $round_matchups );

    for( $i = 0; $i < count( $round_matchups ); $i++ )
    {
        array_push( $events, array(
            array( 'from_event_index' => $i + count( $competitors ) / 2, 'from_event_rank' => 2 ),
            array( 'from_event_index' => $i + count( $competitors ) / 2 + count( $competitors ) / 2 / 2, 'from_event_rank' => 1 )
        ) );
    }
}

if( $rounds > 3 )
{
    // subsequent rounds
    for( $i = 0; $i < $rounds - 3; $i++ )
    {
        $round_events = pow( 2, $rounds - 3 - $i );
        $total_events = count( $events );

        for( $j = 0; $j < $round_events; $j++ )
        {
            array_push( $events, array(
                array( 'from_event_index' => $j + $round_index, 'from_event_rank' => 1 ),
                array( 'from_event_index' => ++$j + $round_index, 'from_event_rank' => 1 )
            ) );
        }

        for( $j = 0; $j < $round_events; $j++ )
        {
            array_push( $events, array(
                array( 'from_event_index' => $j + $round_index + $round_events * 2, 'from_event_rank' => 1 ),
                array( 'from_event_index' => ++$j + $round_index + $round_events * 2, 'from_event_rank' => 1 )
            ) );
        }

        for( $j = 0; $j < $round_events / 2; $j++ )
        {
            array_push( $events, array(
                array( 'from_event_index' => $j + $total_events, 'from_event_rank' => 2 ),
                array( 'from_event_index' => $j + $total_events + $round_events / 2, 'from_event_rank' => 1 )
            ) );
        }

        $round_index = $total_events;
    }

}

if( $rounds > 1 )
{
    // finals
    array_push( $events, array(
        array( 'from_event_index' => count( $events ) - 3, 'from_event_rank' => 1 ),
        array( 'from_event_index' => count( $events ) - 1, 'from_event_rank' => 1 )
     ) );
}


echo json_encode(array('events'=> $events));


?>
