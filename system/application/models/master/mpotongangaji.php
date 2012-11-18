<?php

class MPotonganGaji extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("nama_potongan", "string", 32);
    }

    public function setUp() {
        $this->setTableName("klh_potongangaji");
    }

}
