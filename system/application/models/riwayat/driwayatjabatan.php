<?php

class DRiwayatJabatan extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("jabatan_id", "integer", 4);
        $this->hasColumn("no_sk", "string", 32);
        $this->hasColumn("tanggal_sk", "date");
        $this->hasColumn("tmt", "date");
    }

    public function setUp() {
        $this->setTableName("klh_riwayatjabatan");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MJabatan", array(
            "local" => "jabatan_id",
            "foreign" => "id"
        ));
    }

}
