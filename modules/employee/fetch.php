<?php
include_once 'employeeservice.php';
$employeeSVC = new EmployeeService();

header("Content-Type: application/json");

if ($_SERVER['CONTENT_TYPE'] === "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

/*** Start Employee Service ***/
if (isset($_POST['listEmployees']) ) 
{

	$result = $employeeSVC->listEmployees();
	echo json_encode($result);
	exit;

}

if (isset($_POST['SaveEmployee']) ) 
{
	$data = $_POST['SaveEmployee'];
    
	$result = $employeeSVC->save($data);
    echo json_encode($result);
    exit;
	
}

if (isset($_POST['DeleteEmployeeByID']) ) 
{
	$employeeID = $_POST['DeleteEmployeeByID'];

	$result = $employeeSVC->delete($employeeID);
    
	echo json_encode($result);
	exit;

}


if (isset($_POST['ListEmployeeByID']) ) 
{
	$employeeID = $_POST['ListEmployeeByID'];

	$result = $employeeSVC->getEmployeeByID($employeeID);
	echo json_encode($result);
	exit;

}

/*** End Employee Service ***/


?>