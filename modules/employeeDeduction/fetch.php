<?php
include_once 'employeedeductionservices.php';
$deductionSVC = new EmployeeDeductionService();

header("Content-Type: application/json");

if ($_SERVER['CONTENT_TYPE'] === "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

/*** Start Deduction Service ***/
if (isset($_POST['listEmployeeDeductions']) ) 
{
	$result = $deductionSVC->listEmployeeDeductions();
	echo json_encode($result);
	exit;
}

if (isset($_POST['SaveEmployeeDeduction']) ) 
{
	$data = $_POST['SaveEmployeeDeduction'];
	$result = $deductionSVC->saveEmployeeDeduction($data);
    echo json_encode($result);
    
    exit;
}

if (isset($_POST['DeleteDeductionByID']) ) 
{
	$EmployeedeductionID = $_POST['DeleteDeductionByID'];
	$result = $deductionSVC->deleteEmployeeDeduction($EmployeedeductionID);
	echo json_encode($result);
	exit;
}

if (isset($_POST['ListEmployeeDeductionByID']) ) 
{
	$EmployeedeductionID = $_POST['ListEmployeeDeductionByID'];
	$result = $deductionSVC->getEmployeeDeductionByID($EmployeedeductionID);
	echo json_encode($result);
	exit;
}




if (isset($_POST['ListEmployeeDeductionByEmployeeID']) ) 
{
	$EmployeeID = $_POST['ListEmployeeDeductionByEmployeeID'];
	$result = $deductionSVC->EmployeeDeductionsbyEmployeeID($EmployeeID);
	echo json_encode($result);
	exit;
}




/*** End Deduction Service ***/
?>
