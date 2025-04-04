<?php 
class EmployeeDeductionService extends BaseService 
{
    public function __construct()
    {
        parent::__construct();
    }   

    public function listEmployeeDeductions(){
        global $conn;
        $sql = "SELECT * FROM `tblemployeededuction`";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }

    public function EmployeeDeductionsbyEmployeeID($EmployeeID)
    {
        global $conn;
        $sql = "SELECT CONCAT(e.FirstName,' ', e.LastName) AS EmployeeName , a.typeName, d.Category, d.Amount, ed.DateCreated 
                FROM `tblemployeededuction` ed
                LEFT JOIN tblemployee e ON ed.EmployeeID = e.EmployeeID 
                LEFT JOIN tbldeduction d ON ed.DeductionID = d.DeductionID
                LEFT JOIN  tbldeductiontype a ON d.DeductiontypeID = a.DeductiontypeID 
                WHERE e.EmployeeID = $EmployeeID;";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }       




    public function getEmployeeDeductionByID($EmployeeDeductionID)
    {
        global $conn;
        $sql = "SELECT * FROM `tblemployeededuction` d WHERE d.EmployeeDeductionID =$EmployeeDeductionID";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }

    public function saveEmployeeDeduction($data){
        global $conn;
        $EmployeeDeductionID = $data['EmployeeDeductionID'];
        $EmployeeID = $data['EmployeeID'];
        $DeductionID = $data['DeductionID'];

       
            
        if($EmployeeDeductionID > 0)
        {
            $sql = "UPDATE `tblemployeededuction` SET `DeductionID`= $DeductionID WHERE EmployeeDeductionID=$EmployeeDeductionID";
        }
        else
        {
            $sql = "INSERT INTO `tblemployeededuction`(`EmployeeID`, `DeductionID`, `DateCreated`) 
                    VALUES ($EmployeeID, $DeductionID, now())";
        }
 
        if(mysqli_query($conn, $sql))
        {
            if ($EmployeeDeductionID == 0){
                $EmployeeDeductionID = mysqli_insert_id($conn);
            }
            return Result::success("Successfully saved", $EmployeeDeductionID);
        }
        return $sql;
    }

    public function deleteEmployeeDeduction($EmployeeDeductionID)
    {
        global $conn;
        $sql = "UPDATE `tblemployeededuction` DELETE `EmployeeDeductionID`=$EmployeeDeductionID  WHERE `EmployeeDeductionID`=$EmployeeDeductionID";
        if (mysqli_query($conn, $sql)) {
            return Result::success("Successfully deleted.");
        }
        return Result::error(mysqli_error($conn));
    }
}
?>
