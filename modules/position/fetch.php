<?php
include_once 'positionservice.php';
$positionSVC = new PositionService();

header("Content-Type: application/json");

if ($_SERVER['CONTENT_TYPE'] === "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

/*** Start Position Service ***/
if (isset($_POST['listPositions']) ) 
{

	$result = $positionSVC->listPositions();
	echo json_encode($result);
	exit;

}

if (isset($_POST['SavePosition']) ) 
{
	$data = $_POST['SavePosition'];
	
	$result = $positionSVC->savePositionData($data);
	echo json_encode($result);
	
	exit;
}

if(isset($_POST['DeletePositionByID']))
{
	$positionID = $_POST['DeletePositionByID'];
	$result = $positionSVC->deletePosition($positionID);

	echo json_encode($result);
	exit;
	
}

if (isset($_POST['ListPositionID'])) {
    $positionID = $_POST['ListPositionID'];

    $result = $positionSVC->getPositionByID($positionID);
    echo json_encode($result);
    exit;
}

/*** End Position Service ***/


?>