<?php

class MSatuanKerja extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("kode_bidang", "integer", 4);
        $this->hasColumn("kode_unit", "integer", 4);
        $this->hasColumn("nama_unit_kerja", "string", 1024);
    }

    public function setUp() {
        $this->setTableName("klh_satuankerja");
    }

    public static function options_array() {
        $retval = array();

        $units = Doctrine::getTable("MSatuanKerja")->findAll();
        foreach ($units as $unit)
            $retval[$unit->id] = $unit->nama_unit_kerja;

        return $retval;
    }

}
