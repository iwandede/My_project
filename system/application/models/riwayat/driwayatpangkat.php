<?php

class DRiwayatPangkat extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("pangkat_id", "integer", 4);
        $this->hasColumn("no_sk", "string", 32);
        $this->hasColumn("tanggal_sk", "date");
        $this->hasColumn("tmt", "date");
        $this->hasColumn("obsolete", "boolean", 1, array("default" => 0));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatpangkat");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MPangkat", array(
            "local" => "pangkat_id",
            "foreign" => "id"
        ));
    }

}
