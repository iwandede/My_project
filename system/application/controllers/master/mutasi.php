<?php

class Mutasi extends Controller {

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

        $data["mutasis"] = Doctrine::getTable("MMutasi")->findAll();

        $html = array();
        $html["title"] = "Daftar Jenis Mutasi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/mutasi/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Jenis Mutasi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/mutasi/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/mutasi/add";
        if ($this->_add_process_validate()) {
            $mutasi = new MMutasi();
            $mutasi->jenis_mutasi = $this->input->post("jenis_mutasi");
            $mutasi->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data jenis mutasi berhasil ditambahkan."
            );
            $target = "master/mutasi/manage";
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

        if (!$data["mutasi"] = Doctrine::getTable("MMutasi")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Mutasi Eselon";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/mutasi/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/mutasi/edit/$id";
        if ($this->_add_process_validate()) {
            if (($mutasi = Doctrine::getTable("MMutasi")->find($id))) {
                $mutasi->jenis_mutasi = $this->input->post("jenis_mutasi");
                $mutasi->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis mutasi berhasil diubah."
                );
                $target = "master/mutasi/manage";
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
            "content" => "Data jenis mutasi gagal dihapus."
        );

        if (($mutasi = Doctrine::getTable("MMutasi")->find($id)))
            if ($mutasi->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis mutasi berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/mutasi/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("jenis_mutasi", "Jenis mutasi", "trim|required");
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
