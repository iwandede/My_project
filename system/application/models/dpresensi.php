<?php

class DPresensi extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("tanggal", "date");
        $this->hasColumn("pegawai_id", "integer", 4);
        $this->hasColumn("status", "integer", 1);
        $this->hasColumn("masuk_j_h", "integer", 1, array("notnull" => false));
        $this->hasColumn("masuk_m_h", "integer", 1, array("notnull" => false));
        $this->hasColumn("keluar_j_h", "integer", 1, array("notnull" => false));
        $this->hasColumn("keluar_m_h", "integer", 1, array("notnull" => false));
        $this->hasColumn("masuk_j", "integer", 1, array("notnull" => false));
        $this->hasColumn("masuk_m", "integer", 1, array("notnull" => false));
        $this->hasColumn("keluar_j", "integer", 1, array("notnull" => false));
        $this->hasColumn("keluar_m", "integer", 1, array("notnull" => false));
        $this->hasColumn("total_m", "integer", 2, array("default" => 0));
        $this->hasColumn("keterangan", "string", 255, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_presensi");

        $this->hasOne("DPegawai", array(
            "local" => "pegawai_id",
            "foreign" => "id"
        ));
    }

    public function setTotal() {
        if ($this->status == 0)
            $this->total_m = ($this->keluar_j * 60 + $this->keluar_m) - ($this->masuk_j * 60 + $this->masuk_m);
    }

    public static function findByTanggalAndPegawaiID($tanggal, $pegawai_id) {
        $res = Doctrine_Query::create()
                ->from("DPresensi")
                ->where("tanggal = ?", $tanggal)
                ->andWhere("pegawai_id = ?", $pegawai_id)
                ->execute()
                ->getFirst();
        if(empty($res)) {
            return new DPresensi();
        } else {
            return $res;
        }
    }

    public static function getAccTotal($pegawai_id, $mulai, $akhir) {
        if ($pegawai_id == 0 || $mulai == null || $akhir == null)
            return 0;
        $mulai = enformat_date($mulai);
        $akhir = enformat_date($akhir);

        return Doctrine_Query::create()
                ->select("SUM(total_m) acc")
                ->from("DPresensi")
                ->where("pegawai_id = ?", $pegawai_id)
                ->andWhere("? <= tanggal", $mulai)
                ->andWhere("tanggal <= ?", $akhir)
                ->execute()->getFirst()->get("acc");
    }

    public static function getStatusArray() {
        return array("hadir", "cuti", "absen");
    }

    public function getStatusStr() {
        $status = self::getStatusArray();
        return $status[$this->status];
    }

    public function statusStrToInt($str) {
        $status = self::getStatusArray();
        return array_search($str, $status);
    }

    public static function getTotalPresensiPerDay($start, $end, $n_pegawai, $where = 1) {
        $status = self::getStatusArray();
        $n = sizeof($status);
        $n_days = ($end - $start) / 86400;
        $liburs = MLibur::getLibur($start, $end);

        $q = Doctrine_Query::create()
                        ->select("tanggal, pegawai_id, status")
                        ->from("DPresensi")
                        ->where($where)
                        ->andWhere("tanggal >= ?", date("Y-m-d", $start))
                        ->andWhere("tanggal < ?", date("Y-m-d", $end));
        $res = $q->execute();

        $tppd = array();
        $now = $start;
        while ($now < $end) {
            $key = date("Y-m-d", $now);
            $tppd[$key] = array();
            for ($i = 0; $i <= $n; $i++)
                $tppd[$key][] = 0;
            $now = next_day($now);
        }

        foreach ($res as $r) {
            if (empty($tppd[$r->tanggal])) {
                $tppd[$r->tanggal] = array();
                for ($i = 0; $i <= $n; $i++)
                    $tppd[$r->tanggal][] = 0;
            }
            $tppd[$r->tanggal][$r->status] += 1;
        }

        $now = $start;
        while ($now < $end) {
            $key = date("Y-m-d", $now);
            $total = 0;
            for ($i = 0; $i < $n; $i++)
                $total += $tppd[$key][$i];

            if (in_array($key, $liburs))
                $tppd[$key][$n] = $n_pegawai - $total;
            else
                $tppd[$key][array_search("absen", $status)] += $n_pegawai - $total;

            $now = next_day($now);
        }

        return $tppd;
    }

    public static function getTotalPresensiPerMonth($start, $end, $n_pegawai, $where = 1) {
        $status = self::getStatusArray();
        $n = sizeof($status);

        $tppm = array();

        $now = $start;
        while ($now < $end) {
            $temp_end = next_month($now);
            if ($temp_end > $end)
                $temp_end = $end;

            $key = date("Y-m", $now) . "-01";
            $tppm[$key] = array();
            for ($j = 0; $j <= $n; $j++)
                $tppm[$key][$j] = 0;

            $tppd = self::getTotalPresensiPerDay($now, $temp_end, $n_pegawai, $where);
            foreach ($tppd as $ppd)
                for ($j = 0; $j <= $n; $j++)
                    $tppm[$key][$j] += $ppd[$j];

            $now = $temp_end;
        }

        return $tppm;
    }

}
