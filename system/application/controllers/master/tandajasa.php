<?php

class TandaJasa extends Controller {

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

        $data["tandajasas"] = Doctrine::getTable("MTandaJasa")->findAll();

        $html = array();
        $html["title"] = "Daftar Tanda Jasa";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/tandajasa/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Tanda Jasa";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/tandajasa/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/tandajasa/add";
        if ($this->_add_process_validate()) {
            $tandajasa = new MTandaJasa();
            $tandajasa->nama = $this->input->post("nama");
            $tandajasa->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data tanda jasa berhasil ditambahkan."
            );
            $target = "master/tandajasa/manage";
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

        if (!$data["tandajasa"] = Doctrine::getTable("MTandaJasa")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Tanda Jasa";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/tandajasa/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/tandajasa/edit/$id";
        if ($this->_add_process_validate()) {
            if (($tandajasa = Doctrine::getTable("MTandaJasa")->find($id))) {
                $tandajasa->nama = $this->input->post("nama");
                $tandajasa->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data tanda jasa berhasil diubah."
                );
                $target = "master/tandajasa/manage";
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
            "content" => "Data tanda jasa gagal dihapus."
        );

        if (($tandajasa = Doctrine::getTable("MTandaJasa")->find($id)))
            if ($tandajasa->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data tanda jasa berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/tandajasa/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama", "Nama Tanda Jasa", "trim|required");
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
