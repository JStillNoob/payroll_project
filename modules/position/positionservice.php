<?php 
    class PositionService extends BaseService 
    {
        public function __construct()
        {
            parent::__construct();
            
        }   

        public function savePositionData($data) {
            global $conn;
        
            $PositionID = $data['PositionID'];
            $PositionName = $conn->real_escape_string($data['PositionName']);
            $BaseSalary = $data['BaseSalary'];
        
            if ($PositionID > 0) {
                $sql = "UPDATE `tblposition` SET `PositionName`='$PositionName', BaseSalary=$BaseSalary WHERE PositionID=$PositionID";
            } else {
                $sql = "INSERT INTO `tblposition` (`PositionName`, BaseSalary,DateCreated, FlagDeleted) VALUES ('$PositionName', $BaseSalary,now(),0)";
            }
        
            if (mysqli_query($conn, $sql)) {
                if ($PositionID == 0) {
                    $PositionID = mysqli_insert_id($conn);
                }
                return Result::success("Successfully saved", $PositionID);
            } else {
                return Result::error(mysqli_error($conn)); 
            }

        }
        public function getPositionByID($PositionID) {
            global $conn;
        
            $sql = "SELECT * FROM `tblposition` p WHERE p.FlagDeleted = 0 and p.PositionID=$PositionID";
            $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        
            return mysqli_fetch_assoc($result);
        }
        
        

        
        public function listPositions(){
      
            global $conn;
    
            $sql = "SELECT * FROM `tblposition` p WHERE p.FlagDeleted=0";
            $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
            $array = array();
    
            while ($row = mysqli_fetch_assoc($result)) {
           
            $array[] = $row;
            }
    
            return $array;
       }

       public function deletePosition($PositionID){

            global $conn;

            $sql = "UPDATE `tblposition` SET FlagDeleted=1 WHERE PositionID=$PositionID";
            if (mysqli_query($conn, $sql)) {
                return Result::success("Successfully deleted");
            } else {
                return Result::error(mysqli_error($conn)); // Return the actual SQL error
            }

        }

                

    }
?>