<?php

class MPendidikanNonFormal extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama_pendidikan", "string", 32);
        $this->hasColumn("minimal_pangkat", "integer", 4);
    }

    public function setUp() {
        $this->setTableName("klh_pendidikannonformal");
        
        $this->hasOne("MPangkat", array(
            "local" => "minimal_pangkat",
            "foreign" => "id"
        ));
    }
    
    public static function options_array() {
        $retval = array();
        
        $pendidikans = Doctrine::getTable("MPendidikanNonFormal")->findAll();
        foreach ($pendidikans as $pendidikan)
            $retval[$pendidikan->id] = $pendidikan->nama_pendidikan;
        
        return $retval;
    }

}
