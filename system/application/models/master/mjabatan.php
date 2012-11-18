<?php

class MJabatan extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama_eselon", "string", 1024);
        $this->hasColumn("minimal_pangkat", "integer", 4);
        $this->hasColumn("eselon", "string", 10);
        $this->hasColumn("urutan_duk", "string", 4);
    }

    public function setUp() {
        $this->setTableName("klh_jabatan");
        
        $this->hasOne("MPangkat", array(
            "local" => "minimal_pangkat",
            "foreign" => "id"
        ));

        $this->hasMany("DRiwayatJabatan as DRJs", array(
            "local" => "id",
            "foreign" => "jabatan_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatMutasi as DRMs", array(
            "local" => "id",
            "foreign" => "jabatan_id",
            "cascade" => array("delete")
        ));
    }
    
    public static function options_array() {
        $retval = array();
        
        $jabatans = Doctrine::getTable("MJabatan")->findAll();
        foreach ($jabatans as $jabatan)
            $retval[$jabatan->id] = $jabatan->nama_eselon;
        
        return $retval;
    }

}
