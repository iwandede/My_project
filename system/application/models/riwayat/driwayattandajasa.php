<?php

class DRiwayatTandaJasa extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("tanda_jasa_id", "integer", 4);
        $this->hasColumn("tanggal", "date");
        $this->hasColumn("keterangan", "string", 128, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayattandajasa");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MTandaJasa", array(
            "local" => "tanda_jasa_id",
            "foreign" => "id"
        ));
    }

}