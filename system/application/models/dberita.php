<?php

class DBerita extends Doctrine_Record {

    public function setTableDefinition() {

        $this->hasColumn("id", "integer", 10, array("default" => 0));

        $this->hasColumn("judul", "text");
        $this->hasColumn("isi", "text");
        $this->hasColumn("tgl_dibuat", "timestamp");
    }

    public function setUp() {
        $this->setTableName("klh_berita");

    }


}
