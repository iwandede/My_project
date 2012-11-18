<?php

class DRiwayatDP3 extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("jabatan_id", "integer", 4);
        $this->hasColumn("tanggal", "date");
        $this->hasColumn("kesetiaan", "integer", 1);
        $this->hasColumn("prestasi", "integer", 1);
        $this->hasColumn("tanggung_jawab", "integer", 1);
        $this->hasColumn("ketaatan", "integer", 1);
        $this->hasColumn("kejujuran", "integer", 1);
        $this->hasColumn("kerja_sama", "integer", 1);
        $this->hasColumn("prakarsa", "integer", 1);
        $this->hasColumn("kepemimpinan", "integer", 1);
        $this->hasColumn("penilai_pegawai_id", "integer", 4);
        $this->hasColumn("penilai_jabatan_id", "integer", 4);
        $this->hasColumn("atasan_penilai_pegawai_id", "integer", 4);
        $this->hasColumn("atasan_penilai_jabatan_id", "integer", 4);
    }

    public function setUp() {
        $this->setTableName("klh_riwayatdp3");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MJabatan", array(
            "local" => "jabatan_id",
            "foreign" => "id"
        ));

        $this->hasOne("DPegawai as Penilai", array(
            "local" => "penilai_pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MJabatan as PenilaiJabatan", array(
            "local" => "penilai_jabatan_id",
            "foreign" => "id"
        ));

        $this->hasOne("DPegawai as AtasanPenilai", array(
            "local" => "atasan_penilai_pegawai_id",
            "foreign" => "id"
        ));

        $this->hasOne("MJabatan as AtasanPenilaiJabatan", array(
            "local" => "atasan_penilai_jabatan_id",
            "foreign" => "id"
        ));
    }

    public static function graphPerkembangan($id, $kolom) {
        $q = Doctrine_Query::create()
                        ->select("tanggal, $kolom")
                        ->from("DRiwayatDP3")
                        ->where("pegawai_id = ?", $id)
                        ->orderBy("tanggal ASC")
                        ->execute();
        $retval = array(array(), array());
        if ($q->count() > 0)
            foreach ($q as $row) {
                $retval[0][] = (int) $row->get($kolom);
                $retval[1][] = date("d-m-Y", strtotime($row->get("tanggal")));
            }
        else
            $retval = array(array(0), array(0));
        return $retval;
    }

}