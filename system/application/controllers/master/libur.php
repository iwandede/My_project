<?php

class Libur extends Controller {

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

        $data["periodik"] = array();
        $periodik = MLibur::getPeriodikArray();
        foreach ($periodik as $p)
            $data["periodik"][] = ucfirst($p);

        $data["liburs"] = Doctrine::getTable("MLibur")->findAll();

        $html = array();
        $html["title"] = "Daftar Hari Libur";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/libur/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["periodik"] = array();
        $periodik = MLibur::getPeriodikArray();
        foreach ($periodik as $p)
            $data["periodik"][] = ucfirst($p);

        $data["minggu"] = day_id();

        $html = array();
        $html["title"] = "Tambah Hari Libur";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/libur/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $target = "master/libur/add";
        if ($this->_add_process_validate()) {
            $libur = new MLibur();
            $libur->periodik = $this->input->post("periodik");
            $idx = (int) $libur->periodik + 1;
            $libur->waktu = $this->input->post("waktu$idx");
            $libur->keterangan = $this->input->post("keterangan");
            $libur->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data hari libur berhasil ditambahkan."
            );
            $target = "master/libur/manage";
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

        if (!$data["libur"] = Doctrine::getTable("MLibur")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        $data["periodik"] = array();
        $periodik = MLibur::getPeriodikArray();
        foreach ($periodik as $p)
            $data["periodik"][] = ucfirst($p);

        $data["minggu"] = day_id();

        $html = array();
        $html["title"] = "Ubah Hari Libur";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("master/libur/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $id = $this->input->post("id");
        $target = "master/libur/edit/$id";
        if ($this->_add_process_validate()) {
            if (($libur = Doctrine::getTable("MLibur")->find($id))) {
                $libur->periodik = $this->input->post("periodik");
                $idx = (int) $libur->periodik + 1;
                $libur->waktu = $this->input->post("waktu$idx");
                $libur->keterangan = $this->input->post("keterangan");
                $libur->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data hari libur berhasil diubah."
                );
                $target = "master/libur/manage";
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
            "content" => "Data hari libur gagal dihapus."
        );

        if (($libur = Doctrine::getTable("MLibur")->find($id)))
            if ($libur->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data hari libur berhasil dihapus."
                );

        $this->session->set_flashdata("process_msg", $msg);
        redirect("master/libur/manage");
    }

    public function _add_process_validate() {
        $this->form_validation->set_rules("periodik", "Periode", "trim|required|numeric");
        $this->form_validation->set_rules("waktu1", "Waktu", "trim|date_format");
        $this->form_validation->set_rules("waktu2", "Waktu", "trim|callback__minggu");
        $this->form_validation->set_rules("waktu3", "Waktu", "trim|callback__bulanan");
        $this->form_validation->set_rules("waktu4", "Waktu", "trim|callback__tahunan");
        $this->form_validation->set_rules("keterangan", "Keterangan", "trim|max_length[64]");
        return $this->form_validation->run();
    }

    public function _minggu($val) {
        if ($val == "")
            return true;

        $this->form_validation->set_message("_minggu", "Data <i>%s</i> tidak sesuai format (0 - 6).");
        return ($val >= 0 && $val < 7);
    }

    public function _bulanan($val) {
        if ($val == "")
            return true;

        $val = (int) $val;
        $this->form_validation->set_message("_bulanan", "Data <i>%s</i> tidak sesuai format (dd).");
        return ($val > 0 && $val <= 31);
    }

    public function _tahunan($val) {
        if ($val == "")
            return true;

        $val = explode("-", $val);
        $val[0] = (int) $val[0];
        $val[1] = (int) $val[1];
        $this->form_validation->set_message("_tahunan", "Data <i>%s</i> tidak sesuai format (mm-dd).");
        return ($val[0] > 0 && $val[0] <= 12 && $val[1] > 0 && $val[1] <= 31);
    }

    private function _get_flashdata() {
        $msg = $this->session->flashdata("process_msg");
        if (empty($msg))
            return array("type" => "hidden", "content" => "");
        else
            return $msg;
    }

}
