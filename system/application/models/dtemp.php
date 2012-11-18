<?php

class DTemp extends Doctrine_Record {

    public function setTableDefinition() {
        $this->hasColumn("user_id", "integer", 4, array("default" => 0));
        $this->hasColumn("model", "string", 32);
        $this->hasColumn("diff", "string", 2048);
    }

    public function setUp() {
        $this->setTableName("klh_temp");
        $this->actAs("Timestampable");

        $this->hasOne("DUser", array(
            "local" => "user_id",
            "foreign" => "id"
        ));
    }

    public static function findByUserAndModel($user, $model) {
        return Doctrine_Query::create()
                ->from("DTemp")
                ->where("user_id = ?", $user)
                ->andWhere("model = ?", $model)
                ->execute();
    }

}
