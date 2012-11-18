<?php

class Gaji extends Controller {

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

        $data["gajis"] = Doctrine::getTable("MGaji")->findAll();

        $html = array();
        $html["title"] = "Daftar Gaji";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/tunjangan_gaji/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["opt_pangkat"] = MPangkat::options_array();

        $html = array();
        $html["title"] = "Tambah Gaji";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/tunjangan_gaji/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/gaji/add";
        if ($this->_add_process_validate()) {
            $gaji = new MGaji();
            $gaji->kode = $this->input->post("kode");
            $gaji->pangkat_id = $this->input->post("pangkat_id");
            $gaji->masa_kerja = $this->input->post("masa_kerja");
            $gaji->gaji = $this->input->post("gaji");
            $gaji->kenaikan = $this->input->post("kenaikan");
            $gaji->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data gaji berhasil ditambahkan."
            );
            $target = "master/gaji/manage";
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

        if (!$data["gaji"] = Doctrine::getTable("MGaji")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();
        $data["opt_pangkat"] = MPangkat::options_array();

        $html = array();
        $html["title"] = "Ubah Gaji";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/tunjangan_gaji/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/gaji/edit/$id";
        if ($this->_add_process_validate()) {
            if (($gaji = Doctrine::getTable("MGaji")->find($id))) {
                $gaji->kode = $this->input->post("kode");
                $gaji->pangkat_id = $this->input->post("pangkat_id");
                $gaji->masa_kerja = $this->input->post("masa_kerja");
                $gaji->gaji = $this->input->post("gaji");
                $gaji->kenaikan = $this->input->post("kenaikan");
                $gaji->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data gaji berhasil diubah."
                );
                $target = "master/gaji/manage";
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
            "content" => "Data gaji gagal dihapus."
        );

        if (($gaji = Doctrine::getTable("MGaji")->find($id)))
            if ($gaji->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data gaji berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/gaji/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("kode", "Kode gaji", "trim");
        $this->form_validation->set_rules("pangkat_id", "Pangkat", "trim|required");
        $this->form_validation->set_rules("masa_kerja", "Masa kerja", "trim|required");
        $this->form_validation->set_rules("gaji", "Nilai gaji", "trim|required");
        $this->form_validation->set_rules("kenaikan", "Nilai kenaikan gaji", "trim|required");
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
