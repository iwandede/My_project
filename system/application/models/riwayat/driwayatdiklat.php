<?php

class DRiwayatDiklat extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("diklat_id", "integer", 4);
        $this->hasColumn("tanggal_mulai", "date");
        $this->hasColumn("tanggal_selesai", "date");
        $this->hasColumn("penyelenggara", "string", 64);
        $this->hasColumn("tempat", "string", 64);
        $this->hasColumn("keterangan", "string", 128);
    }

    public function setUp() {
        $this->setTableName("klh_riwayatdiklat");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MDiklat", array(
            "local" => "diklat_id",
            "foreign" => "id"
        ));
    }

}
