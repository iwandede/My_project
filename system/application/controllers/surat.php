<?php

class Surat extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->helper(array("url", "form", "mycalendar", "download", "file"));

        if (!CurrentUser::user())
            redirect("home/error/403");
    }

    public function manage($owner_id = 0, $ref_id = 0, $modul = "all") {
        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["owner_id"] = empty($owner_id) ? CurrentUser::user()->id : $owner_id;
        $data["ref_id"] = $ref_id;
        $data["modul"] = $modul == "umum" ? "all" : $modul;

        $data["title"] = "";
        if ($modul == "anak") {
            $item = Doctrine::getTable("DAnak")->find($ref_id);
            $data["title"] = "Daftar berkas terkait data anak $item->nama";
        } else if ($modul == "pangkat") {
            $item = Doctrine::getTable("DRiwayatPangkat")->find($ref_id);
            $pangkat = $item->MPangkat;
            $data["title"] = "Daftar berkas terkait kenaikan pangkat menjadi $pangkat->nama_pangkat ($pangkat->golongan_ruang)";
        } else if ($modul == "jabatan") {
            $item = Doctrine::getTable("DRiwayatJabatan")->find($ref_id);
            $jabatan = $item->MJabatan;
            $data["title"] = "Daftar berkas terkait kenaikan jabatan menjadi $jabatan->nama_eselon";
        } else if ($modul == "kunjungan") {
            $data["title"] = "Daftar berkas terkait kunjungan luar negeri";
        } else if ($modul == "hukuman") {
            $data["title"] = "Daftar berkas terkait hukuman disiplin";
        } else if ($modul == "mutasi") {
            $item = Doctrine::getTable("DRiwayatMutasi")->find($ref_id)->MMutasi;
            $data["title"] = "Daftar berkas terkait jenis mutasi $item->jenis_mutasi";
        } else if ($modul != "all") {
            $data["title"] = "Daftar berkas terkait " . implode(" ", explode("_", $modul));
        }

        $data["surats"] = DSurat::findByOwnerAndRef($owner_id, $ref_id);

        $html = array();
        $html["title"] = "Daftar Berkas";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("surat/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add($owner_id = 0, $ref_id = 0, $modul = "umum") {
        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["owner_id"] = empty($owner_id) ? CurrentUser::user()->id : $owner_id;
        $data["ref_id"] = $ref_id;
        $data["modul"] = $modul;

        $data["opt_sk"] = MSuratKeputusan::options_array();

        $html = array();
        $html["title"] = "Tambah Berkas";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("surat/add_" . $modul . "_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        $owner_id = $this->input->post("owner_id");
        $ref_id = $this->input->post("ref_id");
        $modul = $this->input->post("modul");
        $target = "surat/manage/$owner_id/$ref_id/$modul";
        $userfile_link = $this->input->post("userfile_link");

        $config["file_name"] = substr($modul, 0, 2) . time() . substr($modul, -2);
        $config["upload_path"] = "files/u$owner_id/";
        $config["allowed_types"] = "*";
        $config["max_size"] = "20000";
        $config["overwrite"] = true;

        $this->load->library("upload", $config);

        $keterangan = array();
        $keterangan["modul"] = $modul;
        $keterangan["link"] = $userfile_link;
        $keterangan["judul"] = $this->input->post("judul");
        $keterangan["keterangan"] = $this->input->post("keterangan");

        $fields = array();
        if ($modul == "umum"
                || $modul == "pangkat"
                || $modul == "jabatan"
        )
            $fields = array("nomor", "sk_id");
        else if ($modul == "anak"
                || $modul == "mutasi"
                || $modul == "gaji"
                || $modul == "pendidikan_formal"
                || $modul == "pendidikan_non_formal"
                || $modul == "diklat"
                || $modul == "seminar"
                || $modul == "cuti"
                || $modul == "kunjungan"
                || $modul == "organisasi"
                || $modul == "tanda_jasa"
                || $modul == "dp3"
                || $modul == "kesehatan"
                || $modul == "hukuman"
        )
            $fields = array("nomor");

        if (empty($userfile_link)) {
            $keterangan["is_internal_link"] = true;
            if (!$this->upload->do_upload()) {
                $msg = array(
                    "type" => "ui-state-error",
                    "content" => $this->upload->display_errors()
                );
                $target = $this->input->post("cb_error");
            } else {
                $upload_data = $this->upload->data();
                $keterangan["link"] = $upload_data["file_name"];
                foreach ($fields as $field)
                    $keterangan[$field] = $this->input->post($field);

                $surat = new DSurat();
                $surat->owner_id = $owner_id;
                $surat->Uploader = CurrentUser::user();
                $surat->ref_id = $ref_id;
                $surat->keterangan = serialize($keterangan);
                $surat->save();

                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Berkas berhasil ditambahkan."
                );
            }
        } else {
            $keterangan["is_internal_link"] = false;
            foreach ($fields as $field)
                $keterangan[$field] = $this->input->post($field);

            $surat = new DSurat();
            $surat->owner_id = $owner_id;
            $surat->Uploader = CurrentUser::user();
            $surat->ref_id = $ref_id;
            $surat->keterangan = serialize($keterangan);
            $surat->save();

            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Berkas berhasil ditambahkan."
            );
        }

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function delete_process($id) {
        if (!(CurrentUser::user()->role == 1))
            redirect("home/error/403");

        $msg = array(
            "type" => "ui-state-error",
            "content" => "Berkas tidak berhasil dihapus."
        );
        if (($item = Doctrine::getTable("DSurat")->find($id))) {
            $uid = $item->owner_id;
            $keterangan = unserialize($item->keterangan);
            if ($item->delete())
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Berkas berhasil dihapus."
                );

            if ($keterangan["is_internal_link"])
                unlink("files/u$uid/" . $keterangan["link"]);
        }

        $this->session->set_flashdata("process_msg", $msg);
        $args = func_get_args();
        unset($args[0]);
        redirect(implode("/", $args));
    }

    public function download($id) {
        $surat = Doctrine::getTable("DSurat")->find($id);
        $uid = $surat->owner_id;
        $keterangan = unserialize($surat->keterangan);
        $link = $keterangan["is_internal_link"] ?
                site_url("files/u$uid/" . $keterangan["link"]) : $keterangan["link"];

        $data = file_get_contents($link);
        $name = explode("/", $link);
        $name = $name[sizeof($name) - 1];

        force_download($name, $data);
    }

    private function _get_flashdata() {
        $msg = $this->session->flashdata("process_msg");
        if (empty($msg))
            return array("type" => "hidden", "content" => "");
        else
            return $msg;
    }

}
