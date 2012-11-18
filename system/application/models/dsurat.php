<?php

class DSurat extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("owner_id", "integer", 4);
        $this->hasColumn("uploader_id", "integer", 4);
        $this->hasColumn("ref_id", "integer", 4);
        $this->hasColumn("keterangan", "string", 1024, array("notnull" => false));
    }

    public function setUp() {
        $this->setTableName("klh_surat");

        $this->hasOne("DUser as Owner", array(
            "local" => "owner_id",
            "foreign" => "id"
        ));

        $this->hasOne("DUser as Uploader", array(
            "local" => "uploader_id",
            "foreign" => "id"
        ));
    }

    public static function findByOwnerAndRef($owner_id = 0, $ref_id = 0) {
        $q = Doctrine_Query::create()->from("DSurat")->where("1");
        if ($owner_id > 0)
            $q->andWhere("owner_id = ?", $owner_id);
        if ($ref_id > 0)
            $q->andWhere("ref_id = ?", $ref_id);
        return $q->execute();
    }

}
