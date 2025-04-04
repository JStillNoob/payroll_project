<?php
include_once 'attendanceservices.php';
$attendanceSVC = new AttendanceServices();

header("Content-Type: application/json");

if ($_SERVER['CONTENT_TYPE'] === "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

/*** Start Deduction Service ***/
if (isset($_POST['ListAttendance']) ) 
{
	$result = $attendanceSVC->listAttendance();
	echo json_encode($result);
	exit;
}

if (isset($_POST['ListHistoryAttendance']) ) 
{
	$result = $attendanceSVC->listAllAttendanceHistory();
	echo json_encode($result);
	exit;
}

if(isset($_POST['SaveAttendance']) )
{
    $data = $_POST['SaveAttendance'];
    $result = $attendanceSVC->getEmployeeIDbyQR($data);
    echo json_encode($result);
    exit;
}

if (isset($_POST['ListAttendanceinForm']) ) 
{
	$result = $attendanceSVC->listAttendanceinForm();
	echo json_encode($result);
	exit;
}

if (isset($_POST['ListAttendanceByDateRange']) )
{
	$data = $_POST['ListAttendanceByDateRange'];
	$result = $attendanceSVC->getAttendanceByDateRange($data);
	echo json_encode($result);
	exit;
}


/*** End Deduction Service ***/
?>
