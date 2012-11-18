<?php

class DRiwayatPrestasi extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("jenis_prestasi", "string", 64);
        $this->hasColumn("tanggal", "date");
        $this->hasColumn("keterangan", "string", 128, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatprestasi");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));
    }

}