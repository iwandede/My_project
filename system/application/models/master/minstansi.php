<?php

class MInstansi extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama_instansi", "string", 255);
    }

    public function setUp() {
        $this->setTableName("klh_instansi");

        $this->hasMany("DPegawai as DPs", array(
            "local" => "id",
            "foreign" => "instansi_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        $instansis = Doctrine::getTable("MInstansi")->findAll();
        foreach ($instansis as $instansi)
            $retval[$instansi->id] = $instansi->nama_instansi;
        
        return $retval;
    }

}
