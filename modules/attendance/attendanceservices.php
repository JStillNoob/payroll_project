<?php 
class AttendanceServices extends BaseService 
{
    public function __construct()
    {
        parent::__construct();
    }   

    public function listAttendance()
    {
        global $conn;
        $sql = "SELECT * FROM todays_attendance;";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }   
    public function listAllAttendanceHistory()
    {
        global $conn;
        $sql = "SELECT * FROM vw_attendance;"; 
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }
    
    
    public function getEmployeeIDbyQR($data)
    {
        global $conn;
        $generatedCode = $data["QRCode"];
        $logType = $data["logType"];

       
        $sql =  "SELECT e.* FROM tblemployee e WHERE e.GeneratedCode = '$generatedCode'";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $EmployeeID = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $EmployeeID = $row['EmployeeID'];
        };
        
        if ($logType == "TimeIn")
        {
            $return = $this->saveAttendanceTimeIn($EmployeeID);
        }
        else
        {
            $return = $this->saveAttendanceTimeOut($EmployeeID);
        }
        
        if($return !== 0 )
        {
            return Result::success("Successfully saved", $return);
        }
        else
        {
            return Result::error($return);
        }
    }

    public function saveAttendanceTimeIn($EmployeeID)
    {
        global $conn;
        $timeIn = date("H:i:s");
        $sql = "INSERT INTO tblattendance (EmployeeID, TimeIn, AttendanceDate) 
                 VALUES ($EmployeeID,'$timeIn',now())";

        if(mysqli_query($conn, $sql))
        {
            return mysqli_insert_id($conn);
        }
        return 0;
    }

    public function saveAttendanceTimeOut($EmployeeID)
    {
        global $conn;
       
        $timeOut = date("H:i:s");
        $sql = "UPDATE `tblattendance`  
                SET TimeOut = '$timeOut', Status = 'Present'  
                WHERE EmployeeID = $EmployeeID AND AttendanceDate = CURDATE()";


          if(mysqli_query($conn, $sql))
          {
            return 1;
          }
          return 0;
    }

    public function listAttendanceinForm()
    {
        global $conn;
        $sql = "SELECT ed.*, CONCAT(e.FirstName, ' ', e.LastName) AS EmployeeName 
                FROM tblattendance ed
                LEFT JOIN tblemployee e ON ed.EmployeeID = e.EmployeeID";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }  

    public function getAttendanceByDateRange($data)
    {
        $startDate = $data['start'];
        $endDate = $data['end'];


        global $conn;
        $sql = "SELECT Concat(e.FirstName, ' ', e.LastName) as 'EmployeeName', e.EmployeeID,
        IFNULL(MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Monday' THEN Status END),'Absent') AS Monday,
        IFNULL(MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Tuesday' THEN Status END),'Absent') AS Tuesday,
        IFNULL(MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Wednesday' THEN Status END),'Absent') AS Wednesday,
        IFNULL(MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Thursday' THEN Status END),'Absent') AS Thursday,
        IFNULL(MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Friday' THEN Status END),'Absent') AS Friday,
        IFNULL(MAX(CASE WHEN DAYNAME(a.AttendanceDate) = 'Saturday' THEN Status END),'Absent') AS Saturday
        FROM tblattendance a
        LEFT JOIN tblemployee e on e.EmployeeID = a.EmployeeID
        WHERE a.AttendanceDate BETWEEN '$startDate' AND '$endDate'
        GROUP BY e.EmployeeID";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }



    

    
}


?>
