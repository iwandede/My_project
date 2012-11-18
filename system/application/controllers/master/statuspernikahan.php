<?php

class StatusPernikahan extends Controller {

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

        $data["statuspernikahans"] = Doctrine::getTable("MStatusPernikahan")->findAll();

        $html = array();
        $html["title"] = "Daftar Status Pernikahan";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/status_pernikahan/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Tambah Status Pernikahan";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/status_pernikahan/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/statuspernikahan/add";
        if ($this->_add_process_validate()) {
            $statuspernikahan = new MStatusPernikahan();
            $statuspernikahan->status_pernikahan = $this->input->post("status_pernikahan");
            $statuspernikahan->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data status pernikahan berhasil ditambahkan."
            );
            $target = "master/statuspernikahan/manage";
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

        if (!$data["statuspernikahan"] = Doctrine::getTable("MStatusPernikahan")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Ubah Status Pernikahan";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/status_pernikahan/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/statuspernikahan/edit/$id";
        if ($this->_add_process_validate()) {
            if (($statuspernikahan = Doctrine::getTable("MStatusPernikahan")->find($id))) {
                $statuspernikahan->status_pernikahan = $this->input->post("status_pernikahan");
                $statuspernikahan->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data status pernikahan berhasil diubah."
                );
                $target = "master/statuspernikahan/manage";
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
            "content" => "Data status pernikahan gagal dihapus."
        );

        if (($statuspernikahan = Doctrine::getTable("MStatusPernikahan")->find($id)))
            if ($statuspernikahan->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data status pernikahan berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/statuspernikahan/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("status_pernikahan", "Status pernikahan", "trim|required");
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
