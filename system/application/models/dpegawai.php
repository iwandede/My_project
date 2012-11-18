<?php

class DPegawai extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("user_id", "integer", 4, array("default" => 0));

        $this->hasColumn("nama", "string", 255);
        $this->hasColumn("nip", "string", 32);
        $this->hasColumn("nip_lama", "string", 32);
        $this->hasColumn("pin", "string", 12);
        $this->hasColumn("nomor_kartu_pegawai", "string", 32, array("notnull" => false));
        $this->hasColumn("instansi_id", "integer", 4, array("default" => 0));

        $this->hasColumn("tempat_lahir", "string", 25, array("notnull" => false));
        $this->hasColumn("tanggal_lahir", "date", "", array("notnull" => false));
        $this->hasColumn("agama_id", "integer", 4, array("default" => 0));

        $this->hasColumn("eselon", "string", 5, array("notnull" => false));
        $this->hasColumn("kelompok_pegawai_1", "integer", 4);
        $this->hasColumn("kelompok_pegawai_2", "integer", 4);
        $this->hasColumn("kelompok_pegawai_3", "integer", 4);
        $this->hasColumn("kelompok_pegawai_4", "integer", 4);
        $this->hasColumn("status_kerja", "integer", 4);
        $this->hasColumn("tanggal_pensiun", "date");

        $this->hasColumn("tinggi", "string", 5, array("notnull" => false));
        $this->hasColumn("berat", "string", 5, array("notnull" => false));
        $this->hasColumn("golongan_darah", "string", 5, array("notnull" => false));
        $this->hasColumn("jenis_kelamin", "boolean", 1, array("default" => 0));

        $this->hasColumn("alamat", "string", 255, array("notnull" => false));
        $this->hasColumn("telepon_rumah", "string", 20, array("notnull" => false));
        $this->hasColumn("telepon_genggam", "string", 20, array("notnull" => false));
        $this->hasColumn("alamat_email", "string", 50, array("notnull" => false));

        $this->hasColumn("tmt", "date", array("notnull" => false));
        $this->hasColumn("kenaikan_pangkat_berkala", "integer", 1, array("notnull" => false));

        $this->hasColumn("status_pernikahan_id", "integer", 1, array("default" => 1));
        $this->hasColumn("pasangan", "string", 255, array("notnull" => false));
        $this->hasColumn("nomor_kartu_pasangan", "string", 64, array("notnull" => false));

        $this->hasColumn("nomor_askes", "string", 64, array("notnull" => false));
        $this->hasColumn("nomor_npwp", "string", 64, array("notnull" => false));
        $this->hasColumn("nomor_induk_kependudukan", "string", 64, array("notnull" => false));

        $this->hasColumn("tanggal_sumpah_pns", "date", "", array("notnull" => false));
        $this->hasColumn("tanggal_cpns", "date", "", array("notnull" => false));
        $this->hasColumn("masa_kerja_tambahan", "integer", 1);

        $this->hasColumn("duk", "string", 64, array("default" => "9999999999999999999999999999999999999999999999999999999999999999"));
    }

    public function setUp() {
        $this->setTableName("klh_pegawai");
        $this->actAs("Timestampable");

        $this->hasOne("DUser", array(
            "local" => "user_id",
            "foreign" => "id"
        ));

        $this->hasOne("MInstansi", array(
            "local" => "instansi_id",
            "foreign" => "id"
        ));

        $this->hasOne("MAgama", array(
            "local" => "agama_id",
            "foreign" => "id"
        ));

        $this->hasOne("MSatuanKerja as MSK1", array(
            "local" => "kelompok_pegawai_1",
            "foreign" => "id"
        ));

        $this->hasOne("MSatuanKerja as MSK2", array(
            "local" => "kelompok_pegawai_2",
            "foreign" => "id"
        ));

        $this->hasOne("MSatuanKerja as MSK3", array(
            "local" => "kelompok_pegawai_3",
            "foreign" => "id"
        ));

        $this->hasOne("MSatuanKerja as MSK4", array(
            "local" => "kelompok_pegawai_4",
            "foreign" => "id"
        ));

        $this->hasOne("MStatusPernikahan", array(
            "local" => "status_pernikahan_id",
            "foreign" => "id"
        ));

        $this->hasMany("DAnak as DAnaks", array(
            "local" => "id",
            "foreign" => "parent_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatPangkat as DRPs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatJabatan as DRJs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatMutasi as DRMs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatGaji as DRGs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatPendidikan as DRPFs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatPendidikanNonFormal as DRPNFs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatSeminar as DRSs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatDiklat as DRDs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatCuti as DRCs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatPrestasi as DRPrs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatKunjungan as DRKs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatOrganisasi as DROs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatTandaJasa as DRTJs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatDP3 as DRDP3s", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatKesehatan as DRKes", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DRiwayatHukuman as DRHs", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));

        $this->hasMany("DPresensi as DPresensis", array(
            "local" => "id",
            "foreign" => "pegawai_id",
            "cascade" => array("delete")
        ));
    }

    public function getRiwayatJabatan($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DRiwayatJabatan")
                ->where("pegawai_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getRiwayatPendidikan($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DRiwayatPendidikan")
                ->where("pegawai_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getRiwayatPendidikanNonFormal($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DRiwayatPendidikanNonFormal")
                ->where("pegawai_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getRiwayatDiklat($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DRiwayatDiklat")
                ->where("pegawai_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getRiwayatPangkat($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DRiwayatPangkat")
                ->where("pegawai_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getRiwayatKunjungan($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DRiwayatKunjungan")
                ->where("pegawai_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getRiwayatTandaJasa($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DRiwayatTandaJasa")
                ->where("pegawai_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getAnak($where, $orderby) {
        $q = Doctrine_Query::create()
                ->from("DAnak")
                ->where("parent_id = ?", $this->id);
        foreach ($where as $w)
            $q->andWhere($w[0], $w[1]);
        return $q->orderBy($orderby)->execute();
    }

    public function getSisaCuti() {
        $cuti_tahunan = 15;

        $sisa = $cuti_tahunan;

        $awal_tahun = date("Y") . "-01-01";
        foreach ($this->DRCs as $drc)
            if (($drc->tanggal_mulai >= $awal_tahun || $drc->tanggal_selesai >= $awal_tahun)) {// && $drc->cuti_id == 1) {
                $mulai = $drc->tanggal_mulai < $awal_tahun ? strtotime($awal_tahun) : strtotime($drc->tanggal_mulai);
                $selesai = strtotime($drc->tanggal_selesai);
                while ($mulai <= $selesai) {
                    if (!MLibur::isLibur($mulai))
                        $sisa--;
                    $mulai = next_day($mulai);
                }
            }

        return $sisa;
    }

    public function lastRiwayatPangkat($now = null) {
        if ($now == null)
            $now = time();

        $last = 0;
        $retval = false;
        foreach ($this->DRPs as $pangkat) {
            if (($pangkat_time = strtotime($pangkat->tmt)) > $last && $pangkat_time <= $now) {
                $last = $pangkat_time;
                $retval = $pangkat;
            }
        }
        return $retval;
    }

    public function lastRiwayatPendidikan() {
        $last = 0;
        $retval = false;
        foreach ($this->DRPFs as $pendidikan) {
            if (($pendidikan_time = strtotime($pendidikan->tanggal_ijazah)) > $last) {
                $last = $pendidikan_time;
                $retval = $pendidikan;
            }
        }
        return $retval;
    }

    public function lastRiwayatJabatan($now = null) {
        if ($now == null)
            $now = time();

        $last = 0;
        $retval = false;
        foreach ($this->DRJs as $jabatan) {
            if (($jabatan_time = strtotime($jabatan->tanggal_sk)) > $last && $jabatan_time <= $now) {
                $last = $jabatan_time;
                $retval = $jabatan;
            }
        }
        return $retval;
    }

    public function lastRiwayatDP3() {
        $last = 0;
        $retval = false;
        foreach ($this->DRDP3s as $dp3) {
            if (($dp3_time = strtotime($dp3->tanggal)) > $last) {
                $last = $dp3_time;
                $retval = $dp3;
            }
        }
        return $retval;
    }

    public static function ratioGender() {
        $retval = array(array("Laki-Laki", 0), array("Perempuan", 0));

        $q = Doctrine_Query::create()
                ->select("COUNT(id) jumlah")
                ->from("DPegawai")
                ->where("jenis_kelamin = ?", 0)
                ->andWhere("status_kerja != ?", 1)
                ->execute();
        $retval[0][1] = (int) $q->getFirst()->jumlah;

        $q = Doctrine_Query::create()
                ->select("COUNT(id) jumlah")
                ->from("DPegawai")
                ->where("jenis_kelamin = ?", 1)
                ->andWhere("status_kerja != ?", 1)
                ->execute();
        $retval[1][1] = (int) $q->getFirst()->jumlah;

        return $retval;
    }

    public static function ratioPangkat() {
        $retval = array();

        $pangkats = MPangkat::options_array();
        foreach ($pangkats as $i => $value) {
//            $pangkats[$i] = explode(" ", $value);
//            $pangkats[$i] = $pangkats[$i][0];
            $pangkats[$i] = $value;
        }

        $pegawais = Doctrine_Query::
                create()
                ->from("DPegawai")
                ->where("status_kerja != ?", 1)
                ->execute();
//        $pegawais = Doctrine::getTable("DPegawai")->findByStatus_kerja(0);
        $temp = array();
        $temp[0] = 0;
        foreach ($pegawais as $pegawai) {
            $key = 0;

            $pid = $pegawai->lastRiwayatPangkat();
            if ($pid)
                $key = $pid->pangkat_id;

            if (empty($temp[$key]))
                $temp[$key] = 1;
            else
                $temp[$key] = $temp[$key] + 1;
        }

        foreach ($pangkats as $key => $value)
            $retval[] = array($value, empty($temp[$key]) ? 0 : $temp[$key]);
        $retval[] = array("Lain-lain", $temp[0]);

        return $retval;
    }

    public static function ratioPendidikan() {
        $retval = array();

        $pendidikans = MPendidikanFormal::options_array();
        foreach ($pendidikans as $i => $value) {
            if ($value == "Sekolah Dasar")
                $pendidikans[$i] = "SD";
            else if ($value == "Sekolah Menengah Pertama")
                $pendidikans[$i] = "SMP";
            else if ($value == "Sekolah Menengah Atas")
                $pendidikans[$i] = "SMA";
            else if ($value == "Diploma 3")
                $pendidikans[$i] = "D3";
            else if ($value == "Diploma 4/Strata 1")
                $pendidikans[$i] = "D4/S1";
            else if ($value == "Spesialis 1/Strata 2")
                $pendidikans[$i] = "S2";
            else if ($value == "Spesialis 2/Strata 3")
                $pendidikans[$i] = "S3";
        }


        $pegawais = Doctrine_Query::
                create()
                ->from("DPegawai")
                ->where("status_kerja != ?", 1)
                ->execute();
//        $pegawais = Doctrine::getTable("DPegawai")->findByStatus_kerja(0);
        $temp = array();
        $temp[0] = 0;
        foreach ($pegawais as $pegawai) {
            $key = 0;

            $pid = $pegawai->lastRiwayatPendidikan();
            if ($pid)
                $key = $pid->pendidikan_id;

            if (empty($temp[$key]))
                $temp[$key] = 1;
            else
                $temp[$key] = $temp[$key] + 1;
        }

        foreach ($pendidikans as $key => $value)
            $retval[] = array($value, empty($temp[$key]) ? 0 : $temp[$key]);
        $retval[] = array("Lain-lain", $temp[0]);

        return $retval;
    }

    public static function modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin, $count = false, $search = null, $limit = null) {
        $retval = array();
        $pegawais = Doctrine_Query::create();

        if ($count)
            $pegawais->select("COUNT(id) as c");

        $pegawais->from("DPegawai")
                ->where("1");

        switch ($status_kerja) {
            case 0:
                $pegawais->andWhere("status_kerja = 0");
                break;
            case 1:
                $pegawais->andWhere("status_kerja = 1");
                break;
            case 2:
                $pegawais->andWhere("status_kerja = 2");
                break;
            case 3:
                $pegawais->andWhere("status_kerja != 2");
                break;
            case 4:
                $pegawais->andWhere("status_kerja != 1");
                break;
            case 5:
                $pegawais->andWhere("status_kerja != 0");
                break;
            default:
                break;
        }

        if ($golongan != "0")
            $pegawais->andWhere("duk LIKE '" . (99 - (int) $golongan) . "%'");

        if ($eselon != "0")
            $pegawais->andWhere("eselon LIKE '$eselon'");

        if ($unit_kerja) {
            $unit_kerja = Doctrine::getTable("MSatuanKerja")->findByNama_unit_kerja($unit_kerja)->getFirst();
            if ($unit_kerja) {
                $unit_kerja = $unit_kerja->id;
                $pegawais->andWhere("kelompok_pegawai_1 = $unit_kerja OR kelompok_pegawai_2 = $unit_kerja OR kelompok_pegawai_3 = $unit_kerja OR kelompok_pegawai_4 = $unit_kerja");
            }
        }

        if ($jenis_kelamin != 9) {
            $pegawais->andWhere("jenis_kelamin = $jenis_kelamin");
        }

        if ($search) {
            $pegawais->andWhere("(nama LIKE '%$search%' OR nip LIKE '%$search%' OR nip_lama LIKE '%$search%')");
        }

        $pegawais->orderBy("duk");

        if ($limit) {
            $pegawais->limit($limit[0]);
            $pegawais->offset($limit[1]);
        }

        //echo $pegawais->getSqlQuery();
//        if ($jabatan == 0)
//            $retval = $pegawais->execute();
//        else {
        $temp = $pegawais->execute();

        if ($count)
            return $temp->getFirst()->c;

        $retval = array();
        foreach ($temp as $t) {
            $retval[] = $t;
        }
//        }

        return $retval;
    }

    public static function options_array() {
        $retval = array();
        $pegawais = Doctrine_Query::create()
                ->select("id, nama")
                ->from("DPegawai")
                ->where("status_kerja = 0")
                ->orderBy("nama")
                ->execute();
        foreach ($pegawais as $pegawai)
            $retval[$pegawai->id] = $pegawai->nama;

        return $retval;
    }

    public static function eselon_options_array() {
        $retval = array();
        $pegawais = Doctrine_Query::create()
                ->select("DISTINCT eselon")
                ->from("DPegawai")
                ->where("eselon != '-'")
                ->orderBy("eselon")
                ->execute();
        foreach ($pegawais as $pegawai)
            if (trim($pegawai->eselon) != "")
                $retval[$pegawai->eselon] = $pegawai->eselon;

        return $retval;
    }

}
