<?php

class Home extends Controller {

    public function __construct() {
        parent::Controller();
		$this->load->helper("mycalendar");
        $this->load->helper("url");
        $this->load->helper("mydata");
    }

    public function index() {
        if (!CurrentUser::user())
            redirect("user/login");

        $data = array();

        if (CurrentUser::user()->role == 1)
            $data["pegawais"] = Doctrine_Query::
                    create()
                    ->from("DPegawai")
                    ->where("status_kerja != ?", 1)
                    ->execute();
//            $data["pegawais"] = Doctrine::getTable("DPegawai")->findByStatus_kerja(0);
        else
            $data["pegawais"] = Doctrine::getTable("DPegawai")->findById(CurrentUser::user()->id);

        if (date("d") + 7 >= 30) {
            $m = date("m") + 1;
            $data["ultahs"] = Doctrine_Query::create()
                    ->from("DPegawai")
                    ->where("day(tanggal_lahir) >= ?", date("d"))
                    ->andWhere("month(tanggal_lahir) = ?", date("m"))
                    ->orWhere("month(tanggal_lahir) = ?", $m)
                    ->andWhere("day(tanggal_lahir) <= ?", (date("d") + 7) - 30)
//                            ->limit(2)
                    ->orderBy("month(tanggal_lahir) ASC,day(tanggal_lahir) ASC")
                    ->execute();
        } else {
            $data["ultahs"] = Doctrine_Query::create()
                    ->from("DPegawai")
                    ->where("day(tanggal_lahir) >= ?", date("d"))
                    ->andWhere("day(tanggal_lahir) <= ?", date("d") + 7)
                    ->andWhere("month(tanggal_lahir) = ?", date("m"))
                    ->where("month(tanggal_lahir) = ?", date("m"))
//                            ->limit(2)
                    ->orderBy("month(tanggal_lahir) ASC,day(tanggal_lahir) ASC")
                    ->execute();
        }
//        $data["ultahs"]->orderBy("tanggal_lahir")->execute();
//        $data["beritas"] = array(1, 2, 3);
        $data["beritas"] = Doctrine_Query::create()
                ->from("DBerita")
                ->orderBy("tgl_dibuat DESC")
                ->limit(3)
                ->execute();
        $data["pensiuns"] = Doctrine_Query::create()
                ->from("DPegawai")
                ->where("status_kerja = ?", '1')
                ->execute();

        $html = array();
        $html["title"] = "Beranda";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("home", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function error($code = 404) {
        $html = array();
        $html["title"] = "Error " . $code;
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("template/p$code", "", true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

}
