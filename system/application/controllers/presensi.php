<?php

class Presensi extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->helper(array("url", "form", "mycalendar"));

        if (!CurrentUser::user() || !CurrentUser::user()->role == 1)
            redirect("home/error/403");
    }

    public function index() {
        $this->manage();
    }

    public function manage($uid = 0, $year = null, $month = null, $day = null, $viewer = "list") {
        $data = array();
        $data["uid"] = $uid;
        $data["msg"] = $this->_get_flashdata();

        $data["mode"] = 4;
        if (empty($day)) {
            $day = (int) date("d");
            $data["mode"] = 1;
        }
        if (empty($month)) {
            $month = (int) date("m");
            $data["mode"] = 2;
        }
        if (empty($year)) {
            $year = (int) date("Y");
            $data["mode"] = 3;
        }
        $data["date"] = mktime(0, 0, 0, $month, $day, $year);

        $data["pegawais"] = Doctrine::getTable("DPegawai")->findAll();
        $data["status"] = DPresensi::getStatusArray();
        switch ($data["mode"]) {
            case 1:
                $view = "bulanan";
                $start = mktime(0, 0, 0, $month, 1, $year);
                $end = next_month($start);
                $n_pegawai = ($uid == 0) ? $data["pegawais"]->count() : 1;
                //if ($uid > 0) {
                $where = ($uid == 0) ? 1 : "pegawai_id = $uid";
                $data["tppd"] = DPresensi::getTotalPresensiPerDay($start, $end, $n_pegawai, $where);
//                } else
//                    $data["tppd"] = DPresensiCounter::getTotalPresensiPerDay($start, $end, $n_pegawai);
                break;
            case 2:
                $view = "tahunan";
                $start = mktime(0, 0, 0, 1, 1, $year);
                $end = next_year($start);
                $n_pegawai = ($uid == 0) ? $data["pegawais"]->count() : 1;
                //if ($uid > 0) {
                $where = ($uid == 0) ? 1 : "pegawai_id = $uid";
                $data["tppm"] = DPresensi::getTotalPresensiPerMonth($start, $end, $n_pegawai, $where);
//                } else
//                    $data["tppm"] = DPresensiCounter::getTotalPresensiPerMonth($start, $end, $n_pegawai);
                break;
            default:
                $view = "harian";
                $data["presensies"] = Doctrine::getTable("DPresensi")->findByTanggal(date("Y-m-d", $data["date"]));
                if ($data["msg"]["type"] == "hidden" && ($keterangan = MLibur::isLibur($data["date"])))
                    $data["msg"] = array(
                        "type" => "ui-state-highlight",
                        "content" => "Tanggal " . date_id("j F Y", $data["date"]) . " adalah hari libur. $keterangan"
                    );
                break;
        }

        $html = array();
        $html["title"] = "Absensi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("presensi/manage_" . $view . "_" . $viewer, $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        if ($viewer == "excel") {
            $html["filename"] = "presensi_" . $view . ".xls";
            $this->load->view("template/excel", $html);
        } else
            $this->load->view("template/page", $html);
    }

    public function graph() {
        $year = $this->input->post("tahun");
        $month = $this->input->post("bulan");
        if(empty($year))
            $year = date("Y");
        if(empty($month))
            $month = date("m");
        
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["chart_width"] = "100%";
        $data["chart_height"] = 200;
        $data["graphs"] = array(
            "harian",
            "bulanan",
            "tahunan"
        );
        $data["date"] = "$year-$month-01";

        $html = array();
        $html["title"] = "Grafik Absensi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("presensi/graph_line", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function akumulasi() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["mulai"] = $this->input->post("mulai");
        $data["akhir"] = $this->input->post("akhir");
        $data["pegawais"] = Doctrine::getTable("DPegawai")->findAll();
        foreach ($data["pegawais"] as $pegawai)
            $data["acc"][$pegawai->id] = round(DPresensi::getAccTotal($pegawai->id, $data["mulai"], $data["akhir"]) / 60) . " jam, "
                . (DPresensi::getAccTotal($pegawai->id, $data["mulai"], $data["akhir"]) % 60) . " menit";

        $html = array();
        $html["title"] = "Akumulasi Absensi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("presensi/akumulasi_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function upload() {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $html = array();
        $html["title"] = "Upload Absensi";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("presensi/upload_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function upload_process() {
        $config["file_name"] = "absen";
        $config["upload_path"] = "files/";
        $config["allowed_types"] = "*";
        $config["max_size"] = "20000";
        $config["overwrite"] = true;

        $this->load->library("upload", $config);

        if (!$this->upload->do_upload()) {
            $msg = array(
                "type" => "ui-state-error",
                "content" => $this->upload->display_errors()
            );
            $target = "presensi/upload";
        } else {
            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data presensi handkey berhasil ditambahkan."
            );
            $target = "tables/import_absen";
        }

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function add_process() {
        $ids = $this->input->post("id");
        $tanggal = $this->input->post("tanggal");
        $pegawai_ids = $this->input->post("pegawai_id");
        $statuss = $this->input->post("status");
        $masuk_js = $this->input->post("masuk_j");
        $masuk_ms = $this->input->post("masuk_m");
        $keluar_js = $this->input->post("keluar_j");
        $keluar_ms = $this->input->post("keluar_m");
        $keterangans = $this->input->post("keterangan");

        $i = 0;
        foreach ($pegawai_ids as $pegawai_id) {
            if (!empty($pegawai_id)) {
                $presensi = new DPresensi();
                if (!empty($ids[$i]))
                    $presensi = Doctrine::getTable("DPresensi")->find($ids[$i]);
                $presensi->tanggal = $tanggal;
                $presensi->pegawai_id = $pegawai_id;
                $presensi->status = $statuss[$i];
                $presensi->masuk_j = $masuk_js[$i];
                $presensi->masuk_m = $masuk_ms[$i];
                $presensi->keluar_j = $keluar_js[$i];
                $presensi->keluar_m = $keluar_ms[$i];
                $presensi->setTotal();
                $presensi->keterangan = $keterangans[$i];
                $presensi->save();
            } else if (!empty($ids[$i])) {
                $presensi = Doctrine::getTable("DPresensi")->find($ids[$i]);
                $presensi->delete();
            }
            $i++;
        }

        $p_counter = Doctrine_Query::create()
                ->from("DPresensiCounter")
                ->where("tipe = ?", 1)
                ->andWhere("tanggal = ?", $tanggal)
                ->execute();
        if ($p_counter->count() == 0) {
            $p_counter = new DPresensiCounter();
            $p_counter->tanggal = $tanggal;
            $p_counter->tipe = 1;
        } else
            $p_counter = $p_counter->getFirst();
        $n_pegawai = Doctrine_Query::create()
                        ->select("COUNT(id) c")
                        ->from("DPegawai")
                        ->execute()
                        ->getFirst()
                ->c;
        $counter = DPresensi::getTotalPresensiPerDay(strtotime($tanggal), next_day(strtotime($tanggal)), $n_pegawai);
        $p_counter->keterangan = serialize($counter[$tanggal]);
        $p_counter->save();

        $tanggal_m = substr($tanggal, 0, 8) . "01";
        $p_counter_m = Doctrine_Query::create()
                ->from("DPresensiCounter")
                ->where("tipe = ?", 2)
                ->andWhere("tanggal = ?", $tanggal_m)
                ->execute();
        if ($p_counter_m->count() == 0) {
            $p_counter_m = new DPresensiCounter();
            $p_counter_m->tanggal = $tanggal_m;
        } else
            $p_counter_m = $p_counter_m->getFirst();
        $p_counter_m->setTotalPresensiPerMonth(strtotime($tanggal_m), $n_pegawai);
        $p_counter_m->save();

        $tanggal_y = substr($tanggal, 0, 5) . "01-01";
        $p_counter_y = Doctrine_Query::create()
                ->from("DPresensiCounter")
                ->where("tipe = ?", 3)
                ->andWhere("tanggal = ?", $tanggal_y)
                ->execute();
        if ($p_counter_y->count() == 0) {
            $p_counter_y = new DPresensiCounter();
            $p_counter_y->tanggal = $tanggal_y;
        } else
            $p_counter_y = $p_counter_y->getFirst();
        $p_counter_y->setTotalPresensiPerYear(strtotime($tanggal_y), $n_pegawai);
        $p_counter_y->save();

        $msg = array(
            "type" => "ui-state-highlight",
            "content" => "Data presensi berhasil disimpan."
        );
        $this->session->set_flashdata("process_msg", $msg);
        redirect("presensi/manage/0/" . date("Y/m/d", strtotime($tanggal)));
    }

    private function _get_flashdata() {
        $msg = $this->session->flashdata("process_msg");
        if (empty($msg))
            return array("type" => "hidden", "content" => "");
        else
            return $msg;
    }

}
