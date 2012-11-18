<?php

class DRiwayatKunjungan extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("jenis_kunjungan", "string", 64);
        $this->hasColumn("tanggal_berangkat", "date");
        $this->hasColumn("tanggal_kembali", "date");
        $this->hasColumn("tujuan", "string", 128, array("notnull" => false));
        $this->hasColumn("negara", "string", 128, array("notnull" => false));
        $this->hasColumn("penyelenggara", "string", 128, array("notnull" => false));
        $this->hasColumn("sumber_dana", "string", 128, array("notnull" => false));
        $this->hasColumn("keterangan", "string", 128, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatkunjungan");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));
    }

}