<?php

class ApplePayValidateSessionResponseModel extends BaseResponseModel implements iBarionModel
{
    public $ApplePaySessionResponse;

    function __construct()
    {
        parent::__construct();
        $this->ApplePaySessionResponse = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            parent::fromJson($json);
            $this->ApplePaySessionResponse = jget($json, 'ApplePaySessionResponse');
        }
    }
}
