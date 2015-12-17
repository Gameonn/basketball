<?php
//this is an api to get nba team list

// +-----------------------------------+
// + STEP 1: include required files    +
// +-----------------------------------+
require_once("../php_include/db_connection.php");
require_once("../classes/AllClasses.php");

$success=$msg="0";$data=array();



	$data= GeneralFunctions::getNbaTeam();



// +-----------------------------------+
// + STEP 4: send json data			   +
// +-----------------------------------+

echo json_encode(array("success"=>'1',"msg"=>"NBA TEAM LIST","nba"=>$data));

?>