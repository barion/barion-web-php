<?php

class ItemModel implements iBarionModel
{
    public $Name;
    public $Description;
    public $Quantity;
    public $Unit;
    public $UnitPrice;
    public $ItemTotal;
    public $SKU;
    
    function __construct()
    {
        $this->Name = "";
        $this->Description = "";
        $this->Quantity = 0;
        $this->Unit = "";
        $this->UnitPrice = 0;
        $this->ItemTotal = 0;
        $this->SKU = "";
    }

    public function fromJson($json)
    {
        if (!empty($json)) {
            $this->Name = $json['Name'];
            $this->Description = $json['Description'];
            $this->Quantity = $json['Quantity'];
            $this->Unit = $json['Unit'];
            $this->UnitPrice = $json['UnitPrice'];
            $this->ItemTotal = $json['ItemTotal'];
            $this->SKU = $json['SKU'];
        }
    }
}

?>