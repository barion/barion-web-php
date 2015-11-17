<?php

class UserModel implements iBarionModel
{
    public $Name;
    public $Email;

    function fromJson($json)
    {
        if (!empty($json)) {
            $this->Email = $json['Email'];

            $name = new UserNameModel();
            $name->fromJson($json['Name']);
        }
    }
}

?>