<?php

class SatuanKerja extends Controller {

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

        $data["satuankerjas"] = Doctrine::getTable("MSatuanKerja")->findAll();

        $html = array();
        $html["title"] = "Daftar Unit Kerja";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/satuan_kerja/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Unit Kerja";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/satuan_kerja/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/satuankerja/add";
        if ($this->_add_process_validate()) {
            $satuankerja = new MSatuanKerja();
            $satuankerja->kode_bidang = $this->input->post("kode_bidang");
            $satuankerja->kode_unit = $this->input->post("kode_unit");
            $satuankerja->nama_unit_kerja = $this->input->post("nama_unit_kerja");
            $satuankerja->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data unit kerja berhasil ditambahkan."
            );
            $target = "master/satuankerja/manage";
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

        if (!$data["satuankerja"] = Doctrine::getTable("MSatuanKerja")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Unit Kerja";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/satuan_kerja/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/satuankerja/edit/$id";
        if ($this->_add_process_validate()) {
            if (($satuankerja = Doctrine::getTable("MSatuanKerja")->find($id))) {
                $satuankerja->kode_bidang = $this->input->post("kode_bidang");
                $satuankerja->kode_unit = $this->input->post("kode_unit");
                $satuankerja->nama_unit_kerja = $this->input->post("nama_unit_kerja");
                $satuankerja->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data unit kerja berhasil diubah."
                );
                $target = "master/satuankerja/manage";
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
            "content" => "Data unit kerja gagal dihapus."
        );

        if (($satuankerja = Doctrine::getTable("MSatuanKerja")->find($id)))
            if ($satuankerja->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data unit kerja berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/satuankerja/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("kode_bidang", "Kode bidang", "trim|required");
        $this->form_validation->set_rules("kode_unit", "Kode unit", "trim|required");
        $this->form_validation->set_rules("nama_unit_kerja", "Nama unit kerja", "trim|required");
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
