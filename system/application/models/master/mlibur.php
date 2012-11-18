<?php

class MLibur extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("periodik", "integer", 1);
        $this->hasColumn("waktu", "string", 10);
        $this->hasColumn("keterangan", "string", 64, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_libur");
    }

    public static function getPeriodikArray() {
        return array("non periodik", "mingguan", "bulanan", "tahunan");
    }

    public function getPeriodikStr() {
        $periodik = self::getPeriodikArray();
        return $periodik[$this->status];
    }

    public function periodikStrToInt($str) {
        $periodik = self::getPeriodikArray();
        return array_search($str, $periodik);
    }

    public static function isLibur($now) {
        $q = Doctrine_Query::create()
                ->from("MLibur")
                ->where("periodik = ? AND waktu = ?", array(0, date("Y-m-d", $now)))
                ->orWhere("periodik = ? AND waktu = ?", array(1, date("w", $now)))
                ->orWhere("periodik = ? AND waktu = ?", array(2, date("d", $now)))
                ->orWhere("periodik = ? AND waktu = ?", array(3, date("m-d", $now)));
        
        $res = $q->execute();
        
        if ($res->count() == 0)
            return false;
        
        $keterangan = "";
        foreach ($res as $r)
            $keterangan .= $r->keterangan . ", ";
        $keterangan = substr($keterangan, 0, -2);
        $keterangan .= ".";
        
        return $keterangan;
    }

    public static function getLibur($start, $end) {
        $liburs = array();
        while ($start <= $end) {
            if (self::isLibur($start))
                $liburs[] = date("Y-m-d", $start);
            $start = next_day($start);
        }
        return $liburs;
    }

}
