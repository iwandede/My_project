<?php

class MSuratKeputusan extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("jenis_sk", "string", 32);
    }

    public function setUp() {
        $this->setTableName("klh_suratkeputusan");
    }
    
    public static function options_array() {
        $retval = array();
        $surat_keputusans = Doctrine::getTable("MSuratKeputusan")->findAll();
        foreach ($surat_keputusans as $surat_keputusan)
            $retval[$surat_keputusan->id] = $surat_keputusan->jenis_sk;
        
        return $retval;
    }

}
