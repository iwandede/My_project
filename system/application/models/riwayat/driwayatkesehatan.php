<?php

class DRiwayatKesehatan extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("jenis_penyakit", "string", 64);
        $this->hasColumn("rawat", "boolean", 1, array("default" => 0));
        $this->hasColumn("tanggal", "date");
    }

    public function setUp() {
        $this->setTableName("klh_riwayatkesehatan");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));
    }

}