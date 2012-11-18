<?php

class MMutasi extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("jenis_mutasi", "string", 32);
    }

    public function setUp() {
        $this->setTableName("klh_mutasi");

        $this->hasMany("DRiwayatMutasi as DRMs", array(
            "local" => "id",
            "foreign" => "mutasi_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        
        $mutasis = Doctrine::getTable("MMutasi")->findAll();
        foreach ($mutasis as $mutasi)
            $retval[$mutasi->id] = $mutasi->jenis_mutasi;
        
        return $retval;
    }

}
