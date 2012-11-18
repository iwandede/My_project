<?php

class Cuti extends Controller {

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

        $data["cutis"] = Doctrine::getTable("MCuti")->findAll();

        $html = array();
        $html["title"] = "Daftar Jenis Cuti";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/cuti/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Jenis Cuti";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/cuti/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/cuti/add";
        if ($this->_add_process_validate()) {
            $cuti = new MCuti();
            $cuti->jenis_cuti = $this->input->post("jenis_cuti");
            $cuti->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data jenis cuti berhasil ditambahkan."
            );
            $target = "master/cuti/manage";
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

        if (!$data["cuti"] = Doctrine::getTable("MCuti")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Cuti Eselon";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/cuti/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/cuti/edit/$id";
        if ($this->_add_process_validate()) {
            if (($cuti = Doctrine::getTable("MCuti")->find($id))) {
                $cuti->jenis_cuti = $this->input->post("jenis_cuti");
                $cuti->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis cuti berhasil diubah."
                );
                $target = "master/cuti/manage";
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
            "content" => "Data jenis cuti gagal dihapus."
        );

        if (($cuti = Doctrine::getTable("MCuti")->find($id)))
            if ($cuti->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis cuti berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/cuti/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("jenis_cuti", "Jenis cuti", "trim|required");
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
