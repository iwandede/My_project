<?php

class DRiwayatOrganisasi extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("jenis_organisasi", "string", 64);
        $this->hasColumn("tanggal_mulai", "date");
        $this->hasColumn("tanggal_selesai", "date");
        $this->hasColumn("keterangan", "string", 128, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatorganisasi");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));
    }

}