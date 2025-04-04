<?php 
class DeductionService extends BaseService 
{
    public function __construct()
    {
        parent::__construct();
    }   

    public function listDeductions(){
        global $conn;
        $sql = "SELECT a.DeductionID, b.typeName, a.Category, a.Amount 
                FROM `tbldeduction` a
                LEFT JOIN tbldeductiontype b ON b.DeductiontypeID = a.DeductiontypeID";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }
       
    public function getDeductionByID($deductionID)
    {
        global $conn;
        $sql = "SELECT b.typeName , a.Category , a.Amount FROM `tbldeduction` a
                LEFT JOIN tbldeductiontype b ON b.DeductiontypeID = a.DeductiontypeID
                WHERE a.DeductionID=$deductionID";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }

    public function save($data){
        global $conn;
        $DeductionID = $data['DeductionID'];
        $Category = $conn->real_escape_string($data['Category']);
        $Amount = $data['Amount'];
        $DeductionTypeID = $data['DeductionTypeID'];
            
        if($DeductionID > 0)
        {
            $sql = "UPDATE `tbldeduction` SET `Category`='$Category', `Amount`=$Amount, `DeductiontypeID`=$DeductionTypeID WHERE DeductionID=$DeductionID";
        }
        else
        {
            $sql = "INSERT INTO `tbldeduction`(`Category`, `Amount`, `DeductiontypeID`) 
                    VALUES ('$Category', $Amount, $DeductionTypeID)";
        }
            
        if(mysqli_query($conn, $sql))
        {
            if ($DeductionID == 0){
                $DeductionID = mysqli_insert_id($conn);
            }
            return Result::success("Successfully saved", $DeductionID);
        }
        return Result::error($sql);
    }

    public function delete($deductionID)
    {
        global $conn;
        $sql = "DELETE FROM `tbldeduction` WHERE `DeductionID`=$deductionID";
        if (mysqli_query($conn, $sql)) {
            return Result::success("Successfully deleted.");
        }
        return Result::error(mysqli_error($conn));
    }

    public function typedeductionSave($data)
    {
        global $conn;
        $DeductionTypeName = $conn->real_escape_string($data['DeductionTypeName']);
        
        $sql = "INSERT INTO `tbldeductiontype`(`typeName`) 
                VALUES ('$DeductionTypeName')";
        
        if(mysqli_query($conn, $sql))
        {
            $DeductionTypeID = mysqli_insert_id($conn);
            return Result::success("Successfully saved", $DeductionTypeID);
        }
        return Result::error($sql);
    }

    public function getDeductionTypeByID($typeID)
    {
        global $conn;
        $sql = "SELECT * FROM `tbldeductiontype` WHERE DeductiontypeID=$typeID";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }

    public function listDeductionTypes() {
        global $conn;
        $sql = "SELECT DeductiontypeID, typeName FROM tbldeductiontype";
        $result = mysqli_query($conn, $sql) or die(json_encode(mysqli_error($conn)));
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
        return $array;
    }
}