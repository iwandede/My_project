<?php

class Grafik extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->helper("url");

        if (!CurrentUser::user())
            redirect("home/error/403");
    }
    
    public function gender() {
        $this->load->helper("mycalendar");
        
        $data = array();
        $data["chart_width"] = 900;
        $data["chart_height"] = 400;
        $data["graphs"] = array(
            "gender"
        );
        
        $html = array();
        $html["title"] = "Statistik Jenis Kelamin Pegawai";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("grafik", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }
    
    public function pangkat() {
        $this->load->helper("mycalendar");
        
        $data = array();
        $data["chart_width"] = 900;
        $data["chart_height"] = 400;
        $data["graphs"] = array(
            "pangkat"
        );
        
        $html = array();
        $html["title"] = "Statistik Pangkat Pegawai";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("grafik", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }
    
    public function pendidikan() {
        $this->load->helper("mycalendar");
        
        $data = array();
        $data["chart_width"] = 900;
        $data["chart_height"] = 400;
        $data["graphs"] = array(
            "pendidikan"
        );
        
        $html = array();
        $html["title"] = "Statistik Pendidikan Pegawai";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("grafik", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

}
