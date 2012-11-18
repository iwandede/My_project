<?php

class Berita extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->helper(array("form", "url", "mycalendar"));
        $this->load->library(array("form_validation", "session"));
        $this->form_validation->set_message("required", "Data <i>%s</i> harus diisi.");
        $this->form_validation->set_message("alpha_numeric", "Data <i>%s</i> hanya dapat diisi dengan karakter huruf dan angka (alpha-numeric).");
        $this->form_validation->set_message("min_length", "Data <i>%s</i> minimal berisi <i>%s</i> karakter.");
        $this->form_validation->set_message("max_length", "Data <i>%s</i> maksimal berisi <i>%s</i> karakter.");
        $this->form_validation->set_message("matches", "Data <i>%s</i> tidak sesuai dengan data <i>%s</i>.");
    }

    public function index() {
        if (!CurrentUser::user())
            redirect("home/error/403");

        $this->manage();
    }

    public function manage() {
        if (!$user = CurrentUser::user())
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["beritas"] = Doctrine_Query::create()
                ->from("DBerita")
                ->orderBy("tgl_dibuat DESC")
                ->execute();
        $data["current_user"] = CurrentUser::user();
        $html = array();

        $html["title"] = "Daftar Berita";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("berita/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        if (!CurrentUser::user() || !CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Berita";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("berita/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        if (!CurrentUser::user() || !CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $target = "berita/add";
        if ($this->_add_process_validate()) {
            $berita = new DBerita();
            $berita->judul = $this->input->post("judul");
            $berita->isi = $this->input->post("isi");
            $berita->tgl_dibuat = date("Y-m-d H:i:s");
            $berita->save();

            $config["file_name"] = "p" . $berita->id;
            $config["upload_path"] = "files/news/";
            $config["allowed_types"] = "*";
            $config["max_size"] = "20000";
            $config["overwrite"] = true;

            $this->load->library("upload", $config);
            $this->upload->do_upload();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data berita berhasil ditambahkan."
            );
            $target = "berita/manage";
        } else
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function edit($id='') {
        if (!CurrentUser::user())
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["berita"] = Doctrine::getTable("DBerita")->find($id);

        $html = array();
        $html["title"] = "Ubah Berita";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("berita/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        if (!CurrentUser::user())
            redirect("home/error/403");

        $msg = array(
            "type" => "ui-state-error",
            "content" => "Perubahan berita tidak berhasil."
        );
        $target = "berita/edit";

        if ($this->_edit_validate()) {
            if (($berita = Doctrine::getTable("DBerita")->find($this->input->post("id")))) {
                $berita->judul = $this->input->post("judul");
                $berita->isi = $this->input->post("isi");
                $berita->save();

                $config["file_name"] = "p" . $berita->id;
                $config["upload_path"] = "files/news/";
                $config["allowed_types"] = "*";
                $config["max_size"] = "20000";
                $config["overwrite"] = true;

                $this->load->library("upload", $config);
                $this->upload->do_upload();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data berita berhasil diubah."
                );
                $target = "berita/manage";
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
        if (!CurrentUser::user())
            redirect("home/error/403");

        $msg = array(
            "type" => "ui-state-error",
            "content" => "Data berita gagal dihapus."
        );

        if (($berita = Doctrine::getTable("DBerita")->find($id)))
            if ($berita->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data berita berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("berita/manage");
    }

    public function detail($id, $mode = "html") {
        if (!CurrentUser::user())
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["berita"] = Doctrine::getTable("DBerita")->find($id);
        $data["current_user"] = CurrentUser::user();

        $html = array();
        $html["title"] = "Detail Berita";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("berita/detail_$mode", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    private function _get_flashdata() {
        $msg = $this->session->flashdata("process_msg");
        if (empty($msg))
            return array("type" => "hidden", "content" => "");
        else
            return $msg;
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("judul", "Judul Berita", "trim|required");
        $this->form_validation->set_rules("isi", "Isi Berita", "trim|required");
        return $this->form_validation->run();
    }

    public function _edit_validate() {
        $this->form_validation->set_rules("judul", "Judul Berita", "trim|required");
        $this->form_validation->set_rules("isi", "Isi Berita", "trim|required");
        return $this->form_validation->run();
    }

}
