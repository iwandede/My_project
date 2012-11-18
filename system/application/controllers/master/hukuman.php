<?php

class Hukuman extends Controller {

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

        $data["hukumans"] = Doctrine::getTable("MHukuman")->findAll();
        $data["opt_hukuman_disiplin_tingkat"] = MHukuman::options_array_tingkat();

        $html = array();
        $html["title"] = "Daftar Jenis Hukuman";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/hukuman/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["opt_hukuman_disiplin_tingkat"] = MHukuman::options_array_tingkat();

        $html = array();
        $html["title"] = "Tambah Jenis Hukuman";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/hukuman/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/hukuman/add";
        if ($this->_add_process_validate()) {
            $hukuman = new MHukuman();
            $hukuman->jenis_hukuman = $this->input->post("jenis_hukuman");
            $hukuman->tingkat = $this->input->post("tingkat");
            $hukuman->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data jenis hukuman berhasil ditambahkan."
            );
            $target = "master/hukuman/manage";
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

        if (!$data["hukuman"] = Doctrine::getTable("MHukuman")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $data["opt_hukuman_disiplin_tingkat"] = MHukuman::options_array_tingkat();

        $html = array();
        $html["title"] = "Ubah Hukuman Eselon";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/hukuman/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/hukuman/edit/$id";
        if ($this->_add_process_validate()) {
            if (($hukuman = Doctrine::getTable("MHukuman")->find($id))) {
                $hukuman->jenis_hukuman = $this->input->post("jenis_hukuman");
                $hukuman->tingkat = $this->input->post("tingkat");
                $hukuman->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis hukuman berhasil diubah."
                );
                $target = "master/hukuman/manage";
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
            "content" => "Data jenis hukuman gagal dihapus."
        );

        if (($hukuman = Doctrine::getTable("MHukuman")->find($id)))
            if ($hukuman->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data jenis hukuman berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/hukuman/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("jenis_hukuman", "Jenis hukuman", "trim|required");
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
