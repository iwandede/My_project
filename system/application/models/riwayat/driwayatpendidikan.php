<?php

class DRiwayatPendidikan extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("lembaga", "string", 64, array("notnull" => false));
        $this->hasColumn("pendidikan_id", "integer", 4);
        $this->hasColumn("jurusan", "string", 32, array("notnull" => false));
        $this->hasColumn("nomor_ijazah", "string", 32, array("notnull" => false));
        $this->hasColumn("tanggal_ijazah", "date");
    }

    public function setUp() {
        $this->setTableName("klh_riwayatpendidikan");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MPendidikanFormal", array(
            "local" => "pendidikan_id",
            "foreign" => "id"
        ));
    }

}