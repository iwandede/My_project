<?php

class MStatusPernikahan extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("status_pernikahan", "string", 255);
    }

    public function setUp() {
        $this->setTableName("klh_statuspernikahan");

        $this->hasMany("DPegawai as DPs", array(
            "local" => "id",
            "foreign" => "status_pernikahan_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        $status_pernikahans = Doctrine::getTable("MStatusPernikahan")->findAll();
        foreach ($status_pernikahans as $status_pernikahan)
            $retval[$status_pernikahan->id] = $status_pernikahan->status_pernikahan;
        
        return $retval;
    }

}
