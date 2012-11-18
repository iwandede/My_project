<?php

class MPendidikanFormal extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama_pendidikan", "string", 32);
    }

    public function setUp() {
        $this->setTableName("klh_pendidikanformal");

        $this->hasMany("DRiwayatPendidikan as DRPFs", array(
            "local" => "id",
            "foreign" => "pendidikan_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        
        $pendidikans = Doctrine::getTable("MPendidikanFormal")->findAll();
        foreach ($pendidikans as $pendidikan)
            $retval[$pendidikan->id] = $pendidikan->nama_pendidikan;
        
        return $retval;
    }

}
