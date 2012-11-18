<?php

class MCuti extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("jenis_cuti", "string", 128);
    }

    public function setUp() {
        $this->setTableName("klh_cuti");

        $this->hasMany("DRiwayatCuti as DRCs", array(
            "local" => "id",
            "foreign" => "cuti_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        
        $cutis = Doctrine::getTable("MCuti")->findAll();
        foreach ($cutis as $cuti)
            $retval[$cuti->id] = $cuti->jenis_cuti;
        
        return $retval;
    }

}
