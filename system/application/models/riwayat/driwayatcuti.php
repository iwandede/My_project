<?php

class DRiwayatCuti extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("cuti_id", "integer", 4);
        $this->hasColumn("tanggal_mulai", "date");
        $this->hasColumn("tanggal_selesai", "date");
        $this->hasColumn("keterangan", "string", 128, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatcuti");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MCuti", array(
            "local" => "cuti_id",
            "foreign" => "id"
        ));
    }

}