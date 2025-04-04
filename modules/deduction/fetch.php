<?php
include_once 'deductionservice.php';
$deductionSVC = new DeductionService();

header("Content-Type: application/json");

if ($_SERVER['CONTENT_TYPE'] === "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

/*** Start Deduction Service ***/
if (isset($_POST['listDeductions']) ) 
{
	$result = $deductionSVC->listDeductions();
	echo json_encode($result);
	exit;
}

if (isset($_POST['SaveDeduction']) ) 
{
	$data = $_POST['SaveDeduction'];
	$result = $deductionSVC->save($data);
    echo json_encode($result);
    exit;
}

if (isset($_POST['DeleteDeductionByID']) ) 
{
	$deductionID = $_POST['DeleteDeductionByID'];
	$result = $deductionSVC->delete($deductionID);
	echo json_encode($result);
    exit;
}

if (isset($_POST['ListDeductionByID']) ) 
{
	$deductionID = $_POST['ListDeductionByID'];
	$result = $deductionSVC->getDeductionByID($deductionID);
	echo json_encode($result);
    exit;
}

/*** Start Deduction Type Service ***/
if (isset($_POST['SaveDeductionType']) ) 
{
	$data = $_POST['SaveDeductionType'];
	$result = $deductionSVC->typedeductionSave($data);
    echo json_encode($result);
    exit;
}

if (isset($_POST['ListDeductionTypeByID']) ) 
{
	$typeID = $_POST['ListDeductionTypeByID'];
	$result = $deductionSVC->getDeductionTypeByID($typeID);
	echo json_encode($result);
    exit;
}

if (isset($_POST['ListDeductionTypes'])) {
    $result = $deductionSVC->listDeductionTypes(); // Fetch from database
    echo json_encode($result);
    exit;
}
/*** End Deduction Type Service ***/
?>
