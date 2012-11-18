<?php

class Pangkat extends Controller {

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

        $data["pangkats"] = Doctrine::getTable("MPangkat")->findAll();

        $html = array();
        $html["title"] = "Daftar Pangkat";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pangkat/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Pangkat";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pangkat/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/pangkat/add";
        if ($this->_add_process_validate()) {
            $pangkat = new MPangkat();
            $pangkat->nama_pangkat = $this->input->post("nama_pangkat");
            $pangkat->golongan_ruang = $this->input->post("golongan_ruang");
            $pangkat->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data pangkat berhasil ditambahkan."
            );
            $target = "master/pangkat/manage";
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

        if (!$data["pangkat"] = Doctrine::getTable("MPangkat")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Pangkat";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/pangkat/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/pangkat/edit/$id";
        if ($this->_add_process_validate()) {
            if (($pangkat = Doctrine::getTable("MPangkat")->find($id))) {
                $pangkat->nama_pangkat = $this->input->post("nama_pangkat");
                $pangkat->golongan_ruang = $this->input->post("golongan_ruang");
                $pangkat->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data pangkat berhasil diubah."
                );
                $target = "master/pangkat/manage";
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
            "content" => "Data pangkat gagal dihapus."
        );

        if (($pangkat = Doctrine::getTable("MPangkat")->find($id)))
            if ($pangkat->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data pangkat berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/pangkat/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("nama_pangkat", "Nama pangkat", "trim|required");
        $this->form_validation->set_rules("golongan_ruang", "Golongan/ruang", "trim|required");
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
