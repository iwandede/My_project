<?php

class DPresensiCounter extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("tipe", "integer", 1, array("default" => 1));
        $this->hasColumn("tanggal", "date");
        $this->hasColumn("keterangan", "string", 255, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_presensicounter");
    }

    public static function getTotalPresensiPerDay($start, $end, $n_pegawai) {
        $status = DPresensi::getStatusArray();
        $n = sizeof($status);
        $n_days = ($end - $start) / 86400;
        $liburs = MLibur::getLibur($start, $end);

        $q = Doctrine_Query::create()
                        ->from("DPresensiCounter")
                        ->where("tipe = ?", 1)
                        ->andWhere("tanggal >= ?", date("Y-m-d", $start))
                        ->andWhere("tanggal < ?", date("Y-m-d", $end));
        $res = $q->execute();

        $tppd = array();
        foreach ($res as $r)
            $tppd[$r->tanggal] = unserialize($r->keterangan);

        $now = $start;
        while ($now <= $end) {
            $key = date("Y-m-d", $now);
            if (empty($tppd[$key])) {
                $temp = DPresensi::getTotalPresensiPerDay($now, next_day($now), $n_pegawai);
                $tppd[$key] = $temp[$key];
            }
            $now = next_day($now);
        }

        return $tppd;
    }

    public function setTotalPresensiPerMonth($start, $n_pegawai) {
        $status = DPresensi::getStatusArray();
        $n = sizeof($status);

        $tppm = array();

        $now = $start;
        $temp_end = next_month($now);

        $key = date("Y-m", $now);
        $tppm[$key] = array();
        for ($j = 0; $j <= $n; $j++)
            $tppm[$key][$j] = 0;

        $tppd = self::getTotalPresensiPerDay($now, $temp_end, $n_pegawai);
        foreach ($tppd as $ppd)
            for ($j = 0; $j <= $n; $j++)
                $tppm[$key][$j] += $ppd[$j];

        $this->tipe = 2;
        $this->keterangan = serialize($tppm[$key]);
    }

    public static function getTotalPresensiPerMonth($start, $end, $n_pegawai) {
        $status = DPresensi::getStatusArray();
        $n = sizeof($status);
        $n_days = ($end - $start) / 86400;
        $liburs = MLibur::getLibur($start, $end);

        $q = Doctrine_Query::create()
                        ->from("DPresensiCounter")
                        ->where("tipe = ?", 2)
                        ->andWhere("tanggal >= ?", date("Y-m-d", $start))
                        ->andWhere("tanggal < ?", date("Y-m-d", $end));
        $res = $q->execute();

        $tppm = array();
        foreach ($res as $r)
            $tppm[$r->tanggal] = unserialize($r->keterangan);

        $now = $start;
        while ($now < $end) {
            $key = date("Y-m-d", $now);
            if (empty($tppm[$key])) {
                $temp = DPresensi::getTotalPresensiPerMonth($now, next_month($now), $n_pegawai);
                $tppm[$key] = $temp[$key];
            }
            $now = next_month($now);
        }

        return $tppm;
    }

    public function setTotalPresensiPerYear($start, $n_pegawai) {
        $status = DPresensi::getStatusArray();
        $n = sizeof($status);

        $tppm = array();

        $now = $start;
        $temp_end = next_year($now);

        $key = date("Y", $now);
        $tppy[$key] = array();
        for ($j = 0; $j <= $n; $j++)
            $tppy[$key][$j] = 0;

        $tppm = self::getTotalPresensiPerMonth($now, $temp_end, $n_pegawai);
        foreach ($tppm as $ppm)
            for ($j = 0; $j <= $n; $j++)
                $tppy[$key][$j] += $ppm[$j];

        $this->tipe = 3;
        $this->keterangan = serialize($tppy[$key]);
    }

    public static function getTotalPresensiPerYear() {
        $q = Doctrine_Query::create()
                        ->from("DPresensiCounter")
                        ->where("tipe = ?", 3)
                        ->orderBy("tanggal ASC");
        $res = $q->execute();

        $tppy = array();
        foreach ($res as $r)
            $tppy[$r->tanggal] = unserialize($r->keterangan);

        return $tppy;
    }

}
