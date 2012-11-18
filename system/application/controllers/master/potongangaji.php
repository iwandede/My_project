<?php

class PotonganGaji extends Controller {

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

        $data["potongangajis"] = Doctrine::getTable("MPotonganGaji")->findAll();

        $html = array();
        $html["title"] = "Daftar Potongan Gaji";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/potongan_gaji/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Potongan Gaji";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/potongan_gaji/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/potongangaji/add";
        if ($this->_add_process_validate()) {
            $potongangaji = new MPotonganGaji();
            $potongangaji->nama_potongan = $this->input->post("nama_potongan");
            $potongangaji->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data potongan gaji berhasil ditambahkan."
            );
            $target = "master/potongangaji/manage";
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

        if (!$data["potongangaji"] = Doctrine::getTable("MPotonganGaji")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Potongan Gaji";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/potongan_gaji/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/potongangaji/edit/$id";
        if ($this->_add_process_validate()) {
            if (($potongangaji = Doctrine::getTable("MPotonganGaji")->find($id))) {
                $potongangaji->nama_potongan = $this->input->post("nama_potongan");
                $potongangaji->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data potongan gaji berhasil diubah."
                );
                $target = "master/potongangaji/manage";
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
            "content" => "Data potongan gaji gagal dihapus."
        );

        if (($potongangaji = Doctrine::getTable("MPotonganGaji")->find($id)))
            if ($potongangaji->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data potongan gaji berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/potongangaji/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama_potongan", "Nama jenis potongan", "trim|required");
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
