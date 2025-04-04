<?php
include_once 'payrollservices.php';
$payrollSVC = new PayrollServices();

header("Content-Type: application/json");

if ($_SERVER['CONTENT_TYPE'] === "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

try {
    if (isset($_POST['SavePayroll']) ) 
    {
        $data = $_POST['SavePayroll'];
        $result = $payrollSVC->saveEmployeeSheetByEmployeeID($data);
        echo json_encode($result);
        exit;
    }

    if (isset($_POST['GetAttendanceByDatePeriodAndEmployeeID']) ) 
    {
        $data = $_POST['GetAttendanceByDatePeriodAndEmployeeID'];
        $result = $payrollSVC->getAttendanceByEmployeeIDAndDatePeriod($data);
        echo json_encode($result);
        exit;
    }

    if (isset($_POST['listEmployeePayroll']) )
    {
        $datePeriod = $_POST['listEmployeePayroll'];
        $result = $payrollSVC->getPayrollByDatePeriod($datePeriod);
        echo json_encode($result);
        exit;
    }

    // Ensure a default response if no conditions are met
    echo json_encode(['error' => 'Invalid request']);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
