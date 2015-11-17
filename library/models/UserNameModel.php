<?php

class UserNameModel implements iBarionModel
{
    public $LoginName;
    public $FirstName;
    public $LastName;
    public $OrganizationName;

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->LoginName = $json['LoginName'];
            $this->FirstName = $json['FirstName'];
            $this->LastName = $json['LastName'];
            $this->OrganizationName = $json['OrganizationName'];
        }
    }
}

?>