<?php

abstract class BaseService {
    public int $userId = 0;
    public function __construct(){
        $this->userId = 0;

  
    }
    
    public function setcurrentUserId ($loggedInUserID){
        $this->userId = $loggedInUserID;
    }
    
    
    public function setUserTypeId ($userTypeId){
        $this->userTypeId = $userTypeId;
    }

}


?>