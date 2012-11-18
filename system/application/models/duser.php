<?php

class DUser extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("username", "string", 255, array("unique" => "true"));
        $this->hasColumn("password", "string", 255);
        $this->hasColumn("role", "integer", 1, array("default" => 0));
        $this->hasColumn("theme", "integer", 1, array("default" => 0));
    }

    public function setUp() {
        $this->setTableName("klh_user");
        $this->hasMutator("password", "_encrypt_password");

        $this->hasOne("DPegawai", array(
            "local" => "id",
            "foreign" => "user_id"
        ));

        $this->hasMany("DSurat as DOSurats", array(
            "local" => "id",
            "foreign" => "owner_id"
        ));

        $this->hasMany("DSurat as DUSurats", array(
            "local" => "id",
            "foreign" => "uploader_id"
        ));

        $this->hasMany("DTemp as DTemps", array(
            "local" => "id",
            "foreign" => "user_id"
        ));
    }

    protected function _encrypt_password($value) {
        $salt = "#*seCrEt!@-*%";
        $this->_set("password", md5($salt . $value));
    }

}
