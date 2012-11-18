<?php

class DRiwayatSeminar extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("jenis_seminar", "string", 128, array("notnull" => false));
        $this->hasColumn("tanggal_mulai", "date");
        $this->hasColumn("tanggal_selesai", "date");
        $this->hasColumn("penyelenggara", "string", 64);
        $this->hasColumn("tempat", "string", 64);
        $this->hasColumn("keterangan", "string", 128, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatseminar");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));
    }

}