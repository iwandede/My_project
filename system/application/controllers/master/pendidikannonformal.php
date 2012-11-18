<?php

class PendidikanNonFormal extends Controller {

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

        $data["pendidikannonformals"] = Doctrine::getTable("MPendidikanNonFormal")->findAll();

        $html = array();
        $html["title"] = "Daftar Pendidikan Non Formal";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pendidikan_non_formal/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["opt_pangkat"] = MPangkat::options_array();

        $html = array();
        $html["title"] = "Tambah Pendidikan Non Formal";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pendidikan_non_formal/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/pendidikannonformal/add";
        if ($this->_add_process_validate()) {
            $pendidikannonformal = new MPendidikanNonFormal();
            $pendidikannonformal->nama_pendidikan = $this->input->post("nama_pendidikan");
            $pendidikannonformal->minimal_pangkat = $this->input->post("minimal_pangkat");
            $pendidikannonformal->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data pendidikan non formal berhasil ditambahkan."
            );
            $target = "master/pendidikannonformal/manage";
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

        if (!$data["pendidikannonformal"] = Doctrine::getTable("MPendidikanNonFormal")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $data["opt_pangkat"] = MPangkat::options_array();

        $html = array();
        $html["title"] = "Ubah Pendidikan Non Formal";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pendidikan_non_formal/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/pendidikannonformal/edit/$id";
        if ($this->_add_process_validate()) {
            if (($pendidikannonformal = Doctrine::getTable("MPendidikanNonFormal")->find($id))) {
                $pendidikannonformal->nama_pendidikan = $this->input->post("nama_pendidikan");
                $pendidikannonformal->minimal_pangkat = $this->input->post("minimal_pangkat");
                $pendidikannonformal->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data pendidikan non formal berhasil diubah."
                );
                $target = "master/pendidikannonformal/manage";
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
            "content" => "Data pendidikan non formal gagal dihapus."
        );

        if (($pendidikannonformal = Doctrine::getTable("MPendidikanNonFormal")->find($id)))
            if ($pendidikannonformal->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data pendidikan non formal berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/pendidikannonformal/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama_pendidikan", "Nama pendidikan", "trim|required");
        $this->form_validation->set_rules("minimal_pangkat", "Syarat minimal pangkat/golongan", "trim|required");
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
