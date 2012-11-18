<?php

class PendidikanFormal extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->helper(array("url", "form", "mycalendar"));

        if (!CurrentUser::user() || !CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $this->load->library(array("form_validation", "session"));
        $this->form_validation->set_message("required", "Data <i>%s</i> harus diisi.");
        $this->form_validation->set_message("numeric", "Data <i>%s</i> hanya dapat diisi dengan karakter angka (numeric).");
    }

    public function manage() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["pendidikanformals"] = Doctrine::getTable("MPendidikanFormal")->findAll();

        $html = array();
        $html["title"] = "Daftar Pendidikan Formal";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pendidikan_formal/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Pendidikan Formal";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pendidikan_formal/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/pendidikanformal/add";
        if ($this->_add_process_validate()) {
            $pendidikanformal = new MPendidikanFormal();
            $pendidikanformal->nama_pendidikan = $this->input->post("nama_pendidikan");
            $pendidikanformal->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data pendidikan formal berhasil ditambahkan."
            );
            $target = "master/pendidikanformal/manage";
        } else
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function edit($id) {
        $data = array();

        if (!$data["pendidikanformal"] = Doctrine::getTable("MPendidikanFormal")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Pendidikan Formal";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pendidikan_formal/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/pendidikanformal/edit/$id";
        if ($this->_add_process_validate()) {
            if (($pendidikanformal = Doctrine::getTable("MPendidikanFormal")->find($id))) {
                $pendidikanformal->nama_pendidikan = $this->input->post("nama_pendidikan");
                $pendidikanformal->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data pendidikan formal berhasil diubah."
                );
                $target = "master/pendidikanformal/manage";
            }
        } else
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function delete_process($id) {
        $msg = array(
            "type" => "ui-state-error",
            "content" => "Data pendidikan formal gagal dihapus."
        );

        if (($pendidikanformal = Doctrine::getTable("MPendidikanFormal")->find($id)))
            if ($pendidikanformal->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data pendidikan formal berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/pendidikanformal/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama_pendidikan", "Nama tingkat pendidikan", "trim|required");
        return $this->form_validation->run();
    }

    private function _get_flashdata() {
        $msg = $this->session->flashdata("process_msg");
        if (empty($msg))
            return array("type" => "hidden", "content" => "");
        else
            return $msg;
    }

}
