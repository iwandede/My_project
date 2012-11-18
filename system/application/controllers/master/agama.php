<?php

class Agama extends Controller {

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
        $data["agamas"] = Doctrine::getTable("MAgama")->findAll();

        $html = array();
        $html["title"] = "Daftar Agama";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/agama/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Agama";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/agama/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/agama/add";
        if ($this->_add_process_validate()) {
            $agama = new MAgama();
            $agama->nama_agama = $this->input->post("nama_agama");
            $agama->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data agama berhasil ditambahkan."
            );
            $target = "master/agama/manage";
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

        if (!$data["agama"] = Doctrine::getTable("MAgama")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Agama";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/agama/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/agama/edit/$id";
        if ($this->_add_process_validate()) {
            if (($agama = Doctrine::getTable("MAgama")->find($id))) {
                $agama->nama_agama = $this->input->post("nama_agama");
                $agama->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data agama berhasil diubah."
                );
                $target = "master/agama/manage";
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
            "content" => "Data agama gagal dihapus."
        );

        if (($agama = Doctrine::getTable("MAgama")->find($id)))
            if ($agama->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data agama berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/agama/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama_agama", "Nama agama", "trim|required");
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
