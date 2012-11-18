<?php

class Diklat extends Controller {

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

        $data["diklats"] = Doctrine::getTable("MDiklat")->findAll();

        $html = array();
        $html["title"] = "Daftar Jenis Diklat";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/diklat/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Jenis Diklat";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/diklat/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/diklat/add";
        if ($this->_add_process_validate()) {
            $diklat = new MDiklat();
            $diklat->jenis_diklat = $this->input->post("jenis_diklat");
            $diklat->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data jenis diklat berhasil ditambahkan."
            );
            $target = "master/diklat/manage";
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

        if (!$data["diklat"] = Doctrine::getTable("MDiklat")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Diklat Eselon";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/diklat/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/diklat/edit/$id";
        if ($this->_add_process_validate()) {
            if (($diklat = Doctrine::getTable("MDiklat")->find($id))) {
                $diklat->jenis_diklat = $this->input->post("jenis_diklat");
                $diklat->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis diklat berhasil diubah."
                );
                $target = "master/diklat/manage";
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
            "content" => "Data jenis diklat gagal dihapus."
        );

        if (($diklat = Doctrine::getTable("MDiklat")->find($id)))
            if ($diklat->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis diklat berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/diklat/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("jenis_diklat", "Jenis diklat", "trim|required");
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
