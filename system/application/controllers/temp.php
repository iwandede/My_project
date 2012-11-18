<?php

class Temp extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->helper(array("url"));

        if (!CurrentUser::user() || !CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $this->load->library(array("session"));
    }

    public function accept($id) {
        if (!($temp = Doctrine::getTable("DTemp")->find($id)))
            redirect("home/error/404");

        $diff = unserialize($temp->diff);
        if (empty($diff["array"])) {
            if (($item = Doctrine::getTable($temp->model)->find($diff["id"]))) {
                foreach ($diff as $key => $value)
                    if ($key != "id")
                        $item->set($key, $value);
                $item->save();
            }
        }

        $temp->delete();

        $msg = array(
            "type" => "ui-state-highlight",
            "content" => "Data perubahan berhasil disetujui"
        );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("pegawai/manage");
    }

    public function reject($id) {
        if (!($temp = Doctrine::getTable("DTemp")->find($id)))
            redirect("home/error/404");

        $temp->delete();

        $msg = array(
            "type" => "ui-state-highlight",
            "content" => "Data perubahan berhasil ditolak"
        );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("pegawai/manage");
    }

}
