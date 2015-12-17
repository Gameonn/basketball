<?php

function is_player($round, $row, $team) {
    return $row == pow(2, $round-1) + 1 + pow(2, $round)*($team - 1);
}

$num_teams = 16;
$total_rounds = floor(log($num_teams, 2)) + 1;
$max_rows = $num_teams*2;
$team_array = array();
$unpaired_array = array();
$score_array = array();

for ($round = 1; $round <= $total_rounds; $round++) {
    $team_array[$round] = 1;
    $unpaired_array[$round] = False;
    $score_array[$round] = False;
}


echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"1\">\n";
echo "\t<tr>\n";

for ($round = 1; $round <= $total_rounds; $round++) {

    echo "\t\t<td colspan=\"2\"><strong>Round $round</strong></td>\n";

}

echo "\t</tr>\n";

for ($row = 1; $row <= $max_rows; $row++) {

    echo "\t<tr>\n";

    for ($round = 1; $round <= $total_rounds; $round++) {
        $score_size = pow(2, $round)-1;
        if (is_player($round, $row, $team_array[$round])) {
            $unpaired_array[$round] = !$unpaired_array[$round];
            echo "\t\t<td>Team</td>\n";
            echo "\t\t<td width=\"20\">&nbsp;</td>\n";
            $team_array[$round]++;
            $score_array[$round] = False;
        } else {
            if ($unpaired_array[$round] && $round != $total_rounds) {
                if (!$score_array[$round]) {
                    echo "\t\t<td rowspan=\"$score_size\">Score</td>\n";
                    echo "\t\t<td rowspan=\"$score_size\" width=\"20\">$round</td>\n";
                    $score_array[$round] = !$score_array[$round];
                }
            } else {
                echo "\t\t<td colspan=\"2\">&nbsp;</td>\n";
            }
        }

    }

    echo "\t</tr>\n";

}

echo "</table>\n";

?>