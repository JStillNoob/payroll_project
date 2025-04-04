<?php 
    class EmployeeService extends BaseService 
    {
        public function __construct()
        {
            parent::__construct();
            
        }   

        public function listEmployees(){
      
            global $conn;
    
            $sql = "SELECT * FROM `tblemployee` e WHERE e.FlagDeleted=0";
            $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
            $array = array();
    
            while ($row = mysqli_fetch_assoc($result)) {
           
            $array[] = $row;
            }
    
            return $array;
       }
       
       public function getEmployeeByID($employeeID)
       {
            global $conn;

            $sql = "SELECT * FROM `tblemployee` e WHERE e.FlagDeleted=0 and e.EmployeeID";
            $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
            $array = array();
    
            while ($row = mysqli_fetch_assoc($result)) {
           
            $array[] = $row;
            }
    
            return $array;
       }

       public function save($data){
       
            global $conn;
            
           $EmployeeID = $data['EmployeeID'];
           $EmployeeFirstName = $conn->real_escape_string($data['FirstName']);
           $EmployeeMiddleName = $conn->real_escape_string($data['MiddleName']);
           $EmployeeLastName = $data['LastName'];
           $Age = $data['Age'];
           $ContactNumber = $data['ContactNumber'];
           $sex = $data['Sex'];
           $generatedCode = $data['GeneratedCode'];
          
            
            if($EmployeeID > 0)
            {
                $sql = "UPDATE `tblemployee` SET `FirstName`='$EmployeeFirstName',`MiddleName`='$EmployeeMiddleName',`LastName`='$EmployeeLastName',
                `Age`=$Age,`ContactNumber`='$ContactNumber',`Sex`='$sex' WHERE EmployeeID=$EmployeeID";
            }
            else
            {
                $sql = "INSERT INTO `tblemployee`(`FirstName`, `MiddleName`, `LastName`, `Age`, `ContactNumber`, `DateCreated`, `FlagDeleted`,`Sex`,`GeneratedCode`) 
                        VALUES ('$EmployeeFirstName', '$EmployeeMiddleName', '$EmployeeLastName', $Age, '$ContactNumber', now(), 0,'$sex','$generatedCode')";
    
            }
            
            if(mysqli_query($conn, $sql))
            {
                if ($EmployeeID == 0){
                    
                    $EmployeeID = mysqli_insert_id($conn);
    
                }
    
                return Result::success("Successfully saved", $EmployeeID);
            }
            
           return Result::error($sql);
            
            
       }
       public function delete($employeeID)
       {
           global $conn;
   
           $sql = "UPDATE `tblemployee` SET `FlagDeleted` = 1 WHERE `EmployeeID`=$employeeID";
           if (mysqli_query($conn, $sql)) {
               return Result::success("Successfully deleted.");
           }
           return Result::error(mysqli_error($conn));
       }
    }
?>