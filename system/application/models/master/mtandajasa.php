<?php

class MTandaJasa extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama", "string", 255);
    }

    public function setUp() {
        $this->setTableName("klh_tandajasa");

        $this->hasMany("DRiwayatTandaJasa as DRTJs", array(
            "local" => "id",
            "foreign" => "tanda_jasa_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        $tandajasas = Doctrine::getTable("MTandaJasa")->findAll();
        foreach ($tandajasas as $tandajasa)
            $retval[$tandajasa->id] = $tandajasa->nama;
        
        return $retval;
    }

}
