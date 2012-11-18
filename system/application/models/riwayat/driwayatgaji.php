<?php

class DRiwayatGaji extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("gaji_pokok", "integer", 4);
        $this->hasColumn("tunjangan_jabatan", "integer", 4);
        $this->hasColumn("tunjangan_pasangan", "integer", 4);
        $this->hasColumn("tunjangan_anak", "integer", 4);
        $this->hasColumn("kenaikan_berkala", "integer", 1);
        $this->hasColumn("nilai_kenaikan", "integer", 1);
        $this->hasColumn("tanggal", "date", "", array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_riwayatgaji");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));
    }

}
