<?php

class BaseResponseModel{
    public $Errors;
    public $RequestSuccessful;

    function __construct(){
        $this->Errors = array();
        $this->RequestSuccessful = false;
    }

    public function fromJson($json){
        if(!empty($json)){
            $this->RequestSuccessful = true;
            if (!empty($json['Errors'])) {
              $this->RequestSuccessful = false;
            }
            foreach ($json['Errors'] as $error) {
                $apiError = new ApiErrorModel();
                $apiError->fromJson($error);
                array_push($this->Errors, $apiError);
            }
        }
    }
}

?>