<?php

class MDiklat extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("jenis_diklat", "string", 128);
    }

    public function setUp() {
        $this->setTableName("klh_diklat");

        $this->hasMany("DRiwayatDiklat as DRDs", array(
            "local" => "id",
            "foreign" => "diklat_id",
            "cascade" => array("delete")
        ));
    }

    public static function options_array() {
        $retval = array();

        $diklats = Doctrine::getTable("MDiklat")->findAll();
        foreach ($diklats as $diklat)
            $retval[$diklat->id] = $diklat->jenis_diklat;

        return $retval;
    }

}
