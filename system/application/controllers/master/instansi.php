<?php

class Instansi extends Controller {

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

        $data["instansis"] = Doctrine::getTable("MInstansi")->findAll();

        $html = array();
        $html["title"] = "Daftar Instansi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/instansi/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Instansi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/instansi/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/instansi/add";
        if ($this->_add_process_validate()) {
            $instansi = new MInstansi();
            $instansi->nama_instansi = $this->input->post("nama_instansi");
            $instansi->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data instansi berhasil ditambahkan."
            );
            $target = "master/instansi/manage";
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

        if (!$data["instansi"] = Doctrine::getTable("MInstansi")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Instansi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/instansi/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/instansi/edit/$id";
        if ($this->_add_process_validate()) {
            if (($instansi = Doctrine::getTable("MInstansi")->find($id))) {
                $instansi->nama_instansi = $this->input->post("nama_instansi");
                $instansi->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data instansi berhasil diubah."
                );
                $target = "master/instansi/manage";
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
            "content" => "Data instansi gagal dihapus."
        );

        if (($instansi = Doctrine::getTable("MInstansi")->find($id)))
            if ($instansi->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data instansi berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/instansi/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama_instansi", "Nama instansi", "trim|required");
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
