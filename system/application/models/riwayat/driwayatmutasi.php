<?php

class DRiwayatMutasi extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("mutasi_id", "integer", 4);
        $this->hasColumn("jabatan_id", "integer", 4);
        $this->hasColumn("tanggal", "date");
        $this->hasColumn("keterangan", "string", 64, array("notnull" => false));
        $this->hasColumn("obsolete", "boolean", 1, array("default" => 0));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatmutasi");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MMutasi", array(
            "local" => "mutasi_id",
            "foreign" => "id"
        ));

        $this->hasOne("MJabatan", array(
            "local" => "jabatan_id",
            "foreign" => "id"
        ));
    }

}
