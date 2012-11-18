<?php

class Jabatan extends Controller {

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

        $data["jabatans"] = Doctrine::getTable("MJabatan")->findAll();

        $html = array();
        $html["title"] = "Daftar Jabatan Eselon";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/jabatan/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["opt_pangkat"] = MPangkat::options_array();

        $html = array();
        $html["title"] = "Tambah Jabatan Eselon";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/jabatan/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/jabatan/add";
        if ($this->_add_process_validate()) {
            $jabatan = new MJabatan();
            $jabatan->nama_eselon = $this->input->post("nama_eselon");
            $jabatan->minimal_pangkat = $this->input->post("minimal_pangkat");
            $jabatan->eselon = $this->input->post("eselon");
            $jabatan->urutan_duk = $this->input->post("urutan_duk");
            $jabatan->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data jabatan eselon berhasil ditambahkan."
            );
            $target = "master/jabatan/manage";
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

        if (!$data["jabatan"] = Doctrine::getTable("MJabatan")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $data["opt_pangkat"] = MPangkat::options_array();

        $html = array();
        $html["title"] = "Ubah Jabatan Eselon";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/jabatan/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/jabatan/edit/$id";
        if ($this->_add_process_validate()) {
            if (($jabatan = Doctrine::getTable("MJabatan")->find($id))) {
                $jabatan->nama_eselon = $this->input->post("nama_eselon");
                $jabatan->minimal_pangkat = $this->input->post("minimal_pangkat");
                $jabatan->eselon = $this->input->post("eselon");
            $jabatan->urutan_duk = $this->input->post("urutan_duk");
                $jabatan->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jabatan eselon berhasil diubah."
                );
                $target = "master/jabatan/manage";
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
            "content" => "Data jabatan eselon gagal dihapus."
        );

        if (($jabatan = Doctrine::getTable("MJabatan")->find($id)))
            if ($jabatan->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jabatan eselon berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/jabatan/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama_eselon", "Nama eselon", "trim|required");
        $this->form_validation->set_rules("minimal_pangkat", "Syarat minimal pangkat/golongan", "trim");
        $this->form_validation->set_rules("eselon", "Eselon", "trim");
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
