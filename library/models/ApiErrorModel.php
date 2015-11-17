<?php

class ApiErrorModel {
    public $ErrorCode;
    public $Title;
    public $Description;

    function __construct() {
        $this->ErrorCode = "";
        $this->Title = "";
        $this->Description = "";
    }

    public function fromJson($json) {
        if(!empty($json)){
            $this->ErrorCode = $json['ErrorCode'];
            $this->Title = $json['Title'];
            $this->Description = $json['Description'];
        }
    }
}

?>