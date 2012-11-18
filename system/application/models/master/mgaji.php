<?php

class MGaji extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("kode", "string", 8, array("notnull" => false));
        $this->hasColumn("pangkat_id", "integer", 4);
        $this->hasColumn("masa_kerja", "integer", 1);
        $this->hasColumn("gaji", "integer", 4);
        $this->hasColumn("kenaikan", "integer", 4);
    }

    public function setUp() {
        $this->setTableName("klh_gaji");

        $this->hasOne("MPangkat", array(
            "local" => "pangkat_id",
            "foreign" => "id"
        ));
    }

}
