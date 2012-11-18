<?php

class MHukuman extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("jenis_hukuman", "string", 256);
        $this->hasColumn("tingkat", "integer", 1);
    }

    public function setUp() {
        $this->setTableName("klh_hukuman");

        $this->hasMany("DRiwayatHukuman as DRHs", array(
            "local" => "id",
            "foreign" => "hukuman_id",
            "cascade" => array("delete")
        ));
    }

    public static function options_array() {
        $retval = array();

//        $hukumans = Doctrine::getTable("MHukuman")->findAll();
        $hukumans = Doctrine_Query::create()
                        ->from("MHukuman")
                        ->orderBy("tingkat")
                        ->execute();
        $tingkatan = self::options_array_tingkat();
        foreach ($hukumans as $hukuman)
            $retval[$hukuman->id] = $tingkatan[$hukuman->tingkat] . ": " . $hukuman->jenis_hukuman;

        return $retval;
    }

    public static function options_array_tingkat() {
        return array("Ringan", "Sedang", "Berat");
    }

}
