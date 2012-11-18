<?php

class MPangkat extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama_pangkat", "string", 32);
        $this->hasColumn("golongan_ruang", "string", 5);
    }

    public function setUp() {
        $this->setTableName("klh_pangkat");

        $this->hasMany("MPendidikanNonFormal as MPNFs", array(
            "local" => "id",
            "foreign" => "minimal_pangkat",
            "cascade" => array("delete")
        ));

        $this->hasMany("MJabatan as MJs", array(
            "local" => "id",
            "foreign" => "minimal_pangkat",
            "cascade" => array("delete")
        ));

        $this->hasMany("MGaji as MGs", array(
            "local" => "id",
            "foreign" => "pangkat_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatPangkat as DRPs", array(
            "local" => "id",
            "foreign" => "pangkat_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        
        $pangkats = Doctrine::getTable("MPangkat")->findAll();
        foreach ($pangkats as $pangkat)
            $retval[$pangkat->id] = "$pangkat->nama_pangkat ($pangkat->golongan_ruang)";
        
        return $retval;
    }

}
