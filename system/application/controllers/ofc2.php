<?php

/**
 * OFC2 Chart Controller
 * 
 * @package CodeIgniter
 * @author  thomas (at) kbox . ch
 */
class Ofc2 extends Controller {

    /**
     * Constructor
     * 
     * @return void
     */
    function __construct() {
        parent::__construct();
        $this->load->helper(array('url_helper', 'mycalendar'));
    }

    /**
     * Generates data for OFC2 line chart in json format
     *
     * @return void
     */
    public function get_data_line($id, $graph, $bg = "#FFFFFF") {
        $this->load->plugin('ofc2');

        if (!$data["pegawai"] = Doctrine::getTable("DPegawai")->find($id))
            redirect("home/error/404");

        $colors = array(
            '#CBDC8E',
            '#DCC68E',
            '#DC9F8E',
            '#DC8EA4',
            '#A4DC8E',
            '#B6CE5F',
            '#5438B7',
            '#DC8ECB',
            '#8EDC9F',
            '#9CB738',
            '#775FCE',
            '#C68EDC',
            '#8EDCC6',
            '#8ECBDC',
            '#8EA4DC',
            '#9F8EDC'
        );
        $graphs = array(
            "kesetiaan",
            "prestasi",
            "tanggung_jawab",
            "ketaatan",
            "kejujuran",
            "kerja_sama",
            "prakarsa",
            "kepemimpinan"
        );
        $color = $colors[array_search($graph, $graphs) % sizeof($graphs)];

        $data = DRiwayatDP3::graphPerkembangan($id, $graph);

        $graph_title = ucwords(implode(" ", explode("_", $graph)));
        $title = new title("Perkembangan " . $graph_title);
        $title->set_style("{font-size: 12px; color:#0000FF; font-family: Verdana; font-weight: bold; text-align: center;}");

        $line = new line();
        $line->set_width(3);
        $line->set_colour($color);
        $line->set_values($data[0]);

        $y = new y_axis();
        $y->set_range(0, 100, 20);

        $x = new x_axis();
        $x_axis_labels = new x_axis_labels();
        //$x_axis_labels->rotate(270);
        $x_axis_labels->set_labels($data[1]);
        $x->set_labels($x_axis_labels);

        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($line);
        $chart->set_y_axis($y);
        $chart->set_x_axis($x);
        $chart->set_bg_colour($bg);

        echo $chart->toPrettyString();
    }

    /**
     * Generates data for OFC2 line chart in json format
     *
     * @return void
     */
    public function get_data_line2($graph, $bg = "FFFFFF", $date = null) {
        $bg = "#$bg";
        if($date == null)
            $date = date("Y-m-d");
        
        $this->load->plugin('ofc2');

        $colors = array(
            '#3B5998',
            '#229844',
            '#984226',
            '#983398',
            '#A4DC8E',
            '#B6CE5F',
            '#5438B7',
            '#DC8ECB',
            '#8EDC9F',
            '#9CB738',
            '#775FCE',
            '#C68EDC',
            '#8EDCC6',
            '#8ECBDC',
            '#8EA4DC',
            '#9F8EDC'
        );
        $graphs = array(
            "harian",
            "bulanan",
            "tahunan"
        );

        $now = strtotime($date);
        $n_pegawai = Doctrine_Query::create()
                        ->select("COUNT(id) c")
                        ->from("DPegawai")
                        ->execute()
                        ->getFirst()
                ->c;
        $l_title = DPresensi::getStatusArray();
        $l_title[] = "libur";
        $n_l_title = sizeof($l_title);
        $x_title = array();
        $l_data = array();
        for ($i = 0; $i < $n_l_title; $i++)
            $l_data[$i] = array();
        $y = new y_axis();
        if ($graph == "harian") {
            $y->set_range(0, $n_pegawai, (int) ($n_pegawai / 5));
            $start = mktime(0, 0, 0, date("m", $now), 1, date("Y", $now));
            $g_t_keterangan = date_id("F Y", $start);
            $end = next_month($start);
            $temp = DPresensi::getTotalPresensiPerDay($start, $end, $n_pegawai);
            foreach ($temp as $t_k => $t_v) {
                $x_title[] = substr($t_k, 8);
                for ($i = 0; $i < $n_l_title; $i++)
                    $l_data[$i][] = $t_v[$i];
            }
        } else if ($graph == "bulanan") {
            $y->set_range(0, $n_pegawai * 31, (int) ($n_pegawai * 31 / 5));
            $start = mktime(0, 0, 0, 1, 1, date("Y", $now));
            $g_t_keterangan = date("Y", $start);
            $end = next_year($start);
            $temp = DPresensi::getTotalPresensiPerMonth($start, $end, $n_pegawai);
            foreach ($temp as $t_k => $t_v) {
                $x_title[] = substr($t_k, 5, 2);
                for ($i = 0; $i < $n_l_title; $i++)
                    $l_data[$i][] = $t_v[$i];
            }
        } else {
            $g_t_keterangan = "";
            $y->set_range(0, $n_pegawai * 366, (int) ($n_pegawai * 366 / 5));
            $temp = DPresensiCounter::getTotalPresensiPerYear();
            foreach ($temp as $t_k => $t_v) {
                $x_title[] = substr($t_k, 0, 4);
                for ($i = 0; $i < $n_l_title; $i++)
                    $l_data[$i][] = $t_v[$i];
            }
        }

        $graph_title = ucwords(implode(" ", explode("_", $graph)));
        $title = new title("Absensi " . $graph_title . " " . $g_t_keterangan);
        $title->set_style("{font-size: 12px; color:#0000FF; font-family: Verdana; font-weight: bold; text-align: center;}");


        $x = new x_axis();
        $x_axis_labels = new x_axis_labels();
        //$x_axis_labels->rotate(270);
        $x_axis_labels->set_labels($x_title);
        $x->set_labels($x_axis_labels);

        $chart = new open_flash_chart();
        $chart->set_title($title);

        $i = 0;
        $lines = array();
        foreach ($l_data as $l_d) {
            $lines[$i] = new line();
            if ($i == $n_l_title - 1)
                $lines[$i]->set_width(1);
            else
                $lines[$i]->set_width(2);
            $lines[$i]->set_colour($colors[($i) % sizeof($colors)]);
            $lines[$i]->set_values($l_d);
            $lines[$i]->set_text($l_title[$i]);
            $chart->add_element($lines[$i]);
            $i++;
        }

        $chart->set_y_axis($y);
        $chart->set_x_axis($x);
        $chart->set_bg_colour($bg);

        echo $chart->toPrettyString();
    }

    /**
     * Generates data for OFC2 pie chart in json format
     *
     * @return void
     */
    public function get_data_pie($graph = "gender", $bg = "#FFFFFF") {
        $this->load->plugin('ofc2');

        $graph_title = array(
            "gender" => "Statistik Jenis Kelamin Pegawai",
            "pangkat" => "Statistik Pangkat Pegawai",
            "pendidikan" => "Statistik Pendidikan Pegawai"
        );

        $function = "ratio" . ucwords($graph);
        $data = $this->_jqplotToOfc2PieValue(DPegawai::$function());

        $title = new title($graph_title[$graph]);
        $title->set_style("{font-size: 20px; color:#0000FF; font-family: Verdana; text-align: center;}");

        $pie = new pie();
        $pie->set_alpha(0.6);
        $pie->set_start_angle(35);
        $pie->add_animation(new pie_fade());
        $pie->set_tooltip('#label#<br>#val# dari #total# (#percent#)');
//        $pie->set_colours(array(
//            '#CBDC8E',
//            '#DCC68E',
//            '#DC9F8E',
//            '#DC8EA4',
//            '#A4DC8E',
//            '#B6CE5F',
//            '#5438B7',
//            '#DC8ECB',
//            '#8EDC9F',
//            '#9CB738',
//            '#775FCE',
//            '#C68EDC',
//            '#8EDCC6',
//            '#8ECBDC',
//            '#8EA4DC',
//            '#9F8EDC'
//        ));
       $pie->set_colours(array(
            '#88A031',
            '#A08131',
            '#A04931',
            '#A03151',
            '#51A031',
            '#607123',
            '#1E1443',
            '#A03188',
           //lanjut pewarnaan
            '#8EDC9F',
            '#9CB738',
            '#775FCE',
            '#C68EDC',
            '#8EDCC6',
            '#8ECBDC',
            '#8EA4DC',
            '#9F8EDC'
        ));
        $pie->set_label_colour("#000000");
        $pie->set_values($data);

        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($pie);
        $chart->set_bg_colour($bg);


        $chart->x_axis = null;

        echo $chart->toPrettyString();
    }

    public function _jqplotToOfc2PieValue($data) {
        $retval = array();
        foreach ($data as $row) {
            if ($row[1] > 0) {
                $retval[] = new pie_value($row[1], $row[0]);
            }
        }
        return $retval;
    }

}
