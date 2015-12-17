<?php
require_once('../php_include/db_connection.php');
require_once('../classes/AllClasses.php');

$event_id=$_REQUEST['event_id'];
$tour_id=$_REQUEST['tour_id'];
$data=array();

if(!($event_id && $tour_id)){
	$success="0";
	$msg="Incomplete Parameters";
	$data=array();
}
else{
$success='1';
$msg="Schedule";
$data=Scheduling::BracketFormation($tour_id);
}

echo json_encode(array("success"=>$success,"msg"=>$msg,'round'=>$data ));



?>
