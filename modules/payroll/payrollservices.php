<?php 
class PayrollServices extends BaseService 
{
    public function __construct()
    {
        parent::__construct();
    }   


    public function saveEmployeeSheetByEmployeeID($data)
    {
        global $conn;
        $payrollID = $data['PayrollID'];
        $employee_id = $data['EmployeeID'];
        $datePeriod = $data['DatePeriod'];
        $salary = $data['Salary'];
        $attendanceIDs = $data['AttendanceIDs'];
        $totalHours = $data['TotalHours'];

        $sql = "INSERT INTO `tblpayroll`(`EmployeeID`, `EmployeeDeductionID`, `TotalHours`, `DatePeriod`, `NetSalary`) 
        VALUES ($employee_id,0,$totalHours,'$datePeriod',0)";
      
        if(mysqli_query($conn, $sql))
        {
            if($payrollID == 0)
            {
                $payrollID = mysqli_insert_id($conn);
            }

            if(count($attendanceIDs) > 0)
    	    {
    	       $data = $this->saveAttendanceIDToPayrollDetails($payrollID, $attendanceIDs);
    	    }
            return Result::success("Successfully saved", $data);
        }
        else{
            return Result::error($sql);
        }
    }

    public function saveAttendanceIDToPayrollDetails($payrollID, $attendanceIDs)
    {
        global $conn;

        $id = $payrollID;
    
        foreach($attendanceIDs as $data)
        {
            $attendanceID = $data['attendanceID'];
            $hours = $data['hours'];
            if($this->CheckIfAttendanceIDExists($attendanceID))
            {
                $sql = "UPDATE `tblpayrolldetails` SET `Hours`=$hours WHERE AttendanceID=$attendanceID";
               
            }
            else
            {
                $sql = "INSERT INTO `tblpayrolldetails`(`PayrollID`, `AttendanceID`, `Hours`, `DateCreated`) 
                        VALUES ($id,$attendanceID,$hours,now())";
            }
            
             mysqli_query($conn, $sql);
        }
        
        return $attendanceIDs;
    }

    public function CheckIfAttendanceIDExists($attendanceID)
    {
        global $conn;
        
        $sql = "Select a.AttendanceID from tblpayrolldetails a where  a.AttendanceID = $attendanceID";
        
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) == 0)
        {
            return false;
        }
        
        return true;
    }

    public function getAttendanceByEmployeeIDAndDatePeriod($data)
    {
        $payrollID = $data['PayrollID'];
        $employeeID = $data['EmployeeID'];
        $startDate = $data['StartDate'];
        $endDate = $data['EndDate'];
       
        global $conn;
        $sql = "SELECT a.EmployeeID,
        MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Monday' THEN a.AttendanceID END) AS Monday_AttendanceID,
        SUM(CASE WHEN DAYNAME(a.AttendanceDate) = 'Monday' THEN TIMESTAMPDIFF(HOUR, TimeIn, TimeOut) ELSE 0 END) AS Monday_Hours,
        MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Tuesday' THEN a.AttendanceID END) AS Tuesday_AttendanceID,
        SUM(CASE WHEN DAYNAME(a.AttendanceDate) = 'Tuesday' THEN TIMESTAMPDIFF(HOUR, TimeIn, TimeOut) ELSE 0 END) AS Tuesday_Hours,
        MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Wednesday' THEN a.AttendanceID END) AS Wednesday_AttendanceID,
        SUM(CASE WHEN DAYNAME(a.AttendanceDate) = 'Wednesday' THEN TIMESTAMPDIFF(HOUR, TimeIn, TimeOut) ELSE 0 END) AS Wednesday_Hours,
        MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Thursday' THEN a.AttendanceID END) AS Thursday_AttendanceID,
        SUM(CASE WHEN DAYNAME(a.AttendanceDate) = 'Thursday' THEN TIMESTAMPDIFF(HOUR, TimeIn, TimeOut) ELSE 0 END) AS Thursday_Hours,
        MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Friday' THEN a.AttendanceID END) AS Friday_AttendanceID,
        SUM(CASE WHEN DAYNAME(a.AttendanceDate) = 'Friday' THEN TIMESTAMPDIFF(HOUR, TimeIn, TimeOut) ELSE 0 END) AS Friday_Hours,
        MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Saturday' THEN a.AttendanceID END) AS Saturday_AttendanceID,
        SUM(CASE WHEN DAYNAME(a.AttendanceDate) = 'Saturday' THEN TIMESTAMPDIFF(HOUR, TimeIn, TimeOut) ELSE 0 END) AS Saturday_Hours
        FROM tblattendance a
        LEFT JOIN tblemployee e on e.EmployeeID = a.EmployeeID
        WHERE a.AttendanceDate BETWEEN '$startDate' AND '$endDate' and e.EmployeeID = $employeeID";
        $result = mysqli_query($conn, query: $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }

    public function getPayrollByDatePeriod($datePeriod)
    {
        global $conn;

        $sql = "SELECT 
                CONCAT(a.FirstName, ' ', a.LastName) AS EmployeeName, IFNULL(e.TotalHours,0) AS TotalHours,
                COALESCE(d.BaseSalary, 0) AS BaseSalary, 
                COALESCE(CEIL(e.TotalHours / 8), 0) AS TotalDays,
                    COALESCE(
                                CASE 
                                    WHEN e.TotalHours < 48 THEN 0 
                                    ELSE CEIL(e.TotalHours - 48) 
                                END, 
                            0) AS Overtime,
                COALESCE(CEIL((e.TotalHours) * (d.BaseSalary / 8 )), 0) AS GrossSalary,
                COALESCE(SUM(f.Amount), 0) AS TotalDeduction,
                COALESCE(CEIL((e.TotalHours) * (d.BaseSalary/8)) - SUM(f.Amount), 0) AS NetSalary,
                COALESCE(e.DatePeriod, 'No Record') AS DatePeriod
                FROM tblemployee a
                LEFT JOIN tblposition d ON d.PositionID = a.PositionID
                LEFT JOIN tblpayroll e ON e.EmployeeID = a.EmployeeID 
                LEFT JOIN tblemployeededuction c ON c.EmployeeID = a.EmployeeID and e.EmployeeDeductionID = c.EmployeeDeductionID
                LEFT JOIN tbldeduction f ON c.DeductionID = f.DeductionID
                WHERE e.DatePeriod IS NULL OR e.DatePeriod = '$datePeriod'
                GROUP BY a.EmployeeID, a.FirstName, a.LastName, d.BaseSalary, e.TotalHours, e.DatePeriod ";
        
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array; 
    }
}
?>
