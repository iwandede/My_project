<?php

class DAnak extends Doctrine_Record {

    public function setTableDefinition() {        
        $this->hasColumn("nama", "string", 255);
        $this->hasColumn("tanggal_lahir", "date");
        $this->hasColumn("jenis_kelamin", "boolean", 1, array("default" => 0));
        $this->hasColumn("parent_id", "integer", 4, array("default" => 0));
    }

    public function setUp() {
        $this->setTableName("klh_anak");
        
        $this->hasOne("DPegawai", array(
            "local" => "parent_id",
            "foreign" => "id"
        ));
    }

}
