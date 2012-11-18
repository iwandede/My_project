<?php

class DRiwayatHukuman extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("hukuman_id", "integer", 4);
        $this->hasColumn("no_sk", "string", 32);
        $this->hasColumn("tanggal", "date");
        $this->hasColumn("tanggal_mulai", "date");
        $this->hasColumn("tanggal_selesai", "date");
        $this->hasColumn("unit_id", "integer", 4);
        $this->hasColumn("keterangan", "string", 128, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayathukuman");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MHukuman", array(
            "local" => "hukuman_id",
            "foreign" => "id"
        ));

        $this->hasOne("MSatuanKerja", array(
            "local" => "unit_id",
            "foreign" => "id"
        ));
    }

}