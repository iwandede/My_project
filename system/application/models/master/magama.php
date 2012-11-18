<?php

class MAgama extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama_agama", "string", 255);
    }

    public function setUp() {
        $this->setTableName("klh_agama");

        $this->hasMany("DPegawai as DPs", array(
            "local" => "id",
            "foreign" => "agama_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        $agamas = Doctrine::getTable("MAgama")->findAll();
        foreach ($agamas as $agama)
            $retval[$agama->id] = $agama->nama_agama;
        
        return $retval;
    }

}
