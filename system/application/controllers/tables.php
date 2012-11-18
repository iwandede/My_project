<?php

class Tables extends Controller {

    function index() {
        echo 'Reminder: Make sure the tables do not exist already.<br />
		<form action="" method="POST">
		<input type="submit" name="action" value="Create Tables"><br /><br />';

        if ($this->input->post('action')) {
            $this->load->helper("url");
            Doctrine::createTablesFromModels();
            echo "Done!<br />";
            echo anchor("tables/load_fixtures", "Load Fixture");
        }
    }

    function load_fixtures() {
        echo 'This will delete all existing data!<br />
		<form action="" method="POST">
		<input type="submit" name="action" value="Load Fixtures"><br /><br />';

        if ($this->input->post('action')) {
            $this->load->helper("url");

            Doctrine_Manager::connection()->execute(
                    'SET FOREIGN_KEY_CHECKS = 0');

            Doctrine::loadData(APPPATH . '/fixtures');
            echo "Done!<br />";
            echo anchor("tables", "Create Tables");
        }
    }

    function backup_fixtures() {
        echo 'This will back up all existing data!<br />
		<form action="" method="POST">
		<input type="submit" name="action" value="Back Up Fixtures"><br /><br />';

        if ($this->input->post('action')) {
            $this->load->helper("url");

            Doctrine_Manager::connection()->execute(
                    'SET FOREIGN_KEY_CHECKS = 0');

            Doctrine::dumpData(APPPATH . '/fixtures', true);
            echo "Done!<br />";
        }
    }

    function import_gaji() {
        $array = $this->_parseCsvFile('tb_gaji.csv', true);

        foreach ($array as $key => $value) {
            $gaji = new MGaji();
            $gaji->fromArray($value);
            $gaji->save();
        }
    }

    function import_anggota($file = 'karyawan.csv') {
        $this->load->helper("mycalendar_helper");
        $array = $this->_parseCsvFile($file, true);

        foreach ($array as $key => $value) {
            echo $key . "<br />";
            if (!empty($value["nip"]))
                $value["nip"] = implode("", explode(" ", $value["nip"]));

            $user = new DUser();
            $user->username = $value["nip"];
            $user->password = "rahasia";
            $user->save();

            $anggota = new DPegawai();
            $anggota->fromArray($value);
            $anggota->DUser = $user;
            $anggota->save();

            mkdir("files/u" . $user->id, 0777);
        }
        echo "done";
    }

    function import_anggota_2($file = 'data_pegawai_klh_rev.csv') {
        $this->load->helper("mycalendar_helper");
        $array = $this->_parseCsvFile($file, true);

//        echo "<pre>";
//        print_r($array);
//        echo "</pre>";
        foreach ($array as $key => $value) {
            echo "$key -> " . $value["nama"] . "<br />";
//            echo "<pre>";
//            print_r($value);
//            echo "</pre>";
            //nama (tidak berubah)
            $value["nip"] = $this->remove_space($value["nip"]);
            $value["nip_lama"] = $this->remove_space($value["nip_lama"]);
            $value["pin"] = $this->remove_space($value["pin"]);
            //nomor_kartu_pegawai (kosong)
            $instansi = Doctrine::getTable("MInstansi")->findOneByNama_instansi($value["instansi"]);
            if (empty($instansi)) {
                $instansi = new MInstansi();
                $instansi->nama_instansi = $value["instansi"];
                $instansi->save();
            }
            $value["instansi_id"] = $instansi->id;
            $temp = explode(",", $value["ttl"], 2);
            $value["tempat_lahir"] = $temp[0];
            if (!empty($temp[1]))
                $value["tanggal_lahir"] = $temp[1];
            else
                $value["tanggal_lahir"] = "00-00-0000";
            $value["tanggal_lahir"] = enformat_date($this->remove_space($value["tanggal_lahir"]));
            unset($value["ttl"]);
            if (empty($value["agama"]))
                $value["agama_id"] = 0;
            else
                switch (substr(strtolower($value["agama"]), 0, 1)) {
                    case "i":
                        $value["agama_id"] = 1;
                        break;
                    case "p":
                        $value["agama_id"] = 2;
                        break;
                    case "k":
                        $value["agama_id"] = 3;
                        break;
                    case "h":
                        $value["agama_id"] = 4;
                        break;
                    case "b":
                        $value["agama_id"] = 5;
                        break;
                    default:
                        $value["agama_id"] = 0;
                        break;
                }
            unset($value["agama"]);
            $value["eselon"] = trim($value["eselon"]);
            $value["eselon"] = ($value["eselon"] == "-") ? "" : implode(" ", explode(".", strtoupper($value["eselon"])));
            for ($kk = 1; $kk <= 4; $kk++) {
                $kelompok_kerja = Doctrine::getTable("MSatuanKerja")->findOneByNama_unit_kerja($value["kelompok_kerja_$kk"]);
                if (empty($kelompok_kerja)) {
                    $kelompok_kerja = new MSatuanKerja();
                    $kelompok_kerja->kode_bidang = 0;
                    $kelompok_kerja->kode_unit = 0;
                    $kelompok_kerja->nama_unit_kerja = $value["kelompok_kerja_$kk"];
                    $kelompok_kerja->save();
                }
                $value["kelompok_pegawai_$kk"] = $kelompok_kerja->id;
                unset($value["kelompok_kerja_$kk"]);
            }
            $value["status_kerja"] = (trim(strtolower($value["status_kerja"])) == "aktif") ? 0 : 1;
            //tinggi (kosong)
            //berat (kosong)
            //golongan_darah (kosong)
            $value["jenis_kelamin"] = ($value["jenis_kelamin"] == "L") ? 0 : 1;
            //alamat (tidak berubah)
            //telepon_rumah (kosong)
            //telepon_genggam (kosong)
            //alamat_email (kosong)
            //tmt (kosong)
            $value["kenaikan_pangkat_berkala"] = 48;
            $value["status_pernikahan_id"] = (strtolower(substr($value["status_pernikahan_id"], 0, 1)) == "k") ? 2 : 1;
            //pasangan (tidak berubah)
            //nomor_kartu_pasangan (kosong)
            //nomor_askes (kosong)
            //nomor_npwp (kosong)
            //nomor_induk_kependudukan (kosong)
            $value["tanggal_sumpah_pns"] = enformat_date($value["tanggal_sumpah_pns"]);
            $value["tanggal_cpns"] = enformat_date($value["tanggal_cpns"]);
            //masa_kerja_tambahan (kosong)

            unset($value["Jabatan"]);
            unset($value["Plt"]);
            unset($value["Eselon"]);
            unset($value["Pangkat"]);
            unset($value["pf_null"]);
            unset($value["diklat_null"]);
            unset($value["Kode tmt Pkt"]);

            $golongan = array();
            $golongan[0] = Doctrine::getTable("MPangkat")->findOneByGolongan_ruang(strtoupper($value["golongan"]));
            $golongan[1] = enformat_date($value["tmt"]);

            if (trim($value["jabatan"]) != "") {
                $jabatan = Doctrine::getTable("MJabatan")->findOneByNama_eselon(strtoupper($value["jabatan"]));
                if (empty($jabatan)) {
                    $jabatan = new MJabatan();
                    $jabatan->nama_eselon = $value["jabatan"];
                    $jabatan->save();
                }
            }

            $pfs = array();
            for ($pi = 1; $pi <= 9; $pi++) {
                if (!empty($value["Pf_$pi"])) {
                    $pfs[] = array(
                        "pendidikan_id" => $pi,
                        "tanggal_ijazah" => $value["Pf_$pi"] . "-01-01"
                    );
                }
                unset($value["Pf_$pi"]);
            }

            $anaks = array();
            for ($ai = 1; $ai <= 6; $ai++) {
                if (!empty($value["anak_$ai"]))
                    $anaks[] = explode(",", $value["anak_$ai"], 2);
                unset($value["anak_$ai"]);
            }

            $user = new DUser();
            $user->username = empty($value["nip"]) ? (empty($value["nip_lama"]) ? $key . time() : $value["nip_lama"]) : $value["nip"];
            $user->password = "rahasia";
            $user->save();

            $anggota = new DPegawai();
            $anggota->fromArray($value);
            $anggota->DUser = $user;
            try {
                $anggota->save();

                $new = new DRiwayatPangkat();
                $new->MPangkat = $golongan[0];
                $new->tmt = $golongan[1];
                $new->DPegawai = $anggota;
                $new->save();

                if (trim($value["jabatan"]) != "") {
                    $new = new DRiwayatJabatan();
                    $new->MJabatan = $jabatan;
                    $new->tanggal_sk = enformat_date($value["tmt_jabatan"]);
                    $new->DPegawai = $anggota;
                    $new->save();
                }

                foreach ($pfs as $pf) {
                    $new = new DRiwayatPendidikan();
                    $new->fromArray($pf);
                    $new->DPegawai = $anggota;
                    $new->save();
                }

                foreach ($anaks as $anak) {
                    $new = new DAnak();
                    $new->nama = $anak[0];
                    $new->tanggal_lahir = enformat_date($this->remove_space($anak[1]));
                    $new->DPegawai = $anggota;
                    $new->save();
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            //mkdir("files/u" . $user->id, 0777);
        }
        echo "done";
    }

    function remove_space($string) {
        if (empty($string))
            return null;

        return implode("", explode(" ", $string));
    }

    function import_absen($file = 'files/absen.csv') {
        $this->load->helper("mycalendar_helper");
        $this->load->helper("url");
        //echo "Sistem hanya dapat mengolah hingga 2000 baris data.";
        $array = $this->_parseCsvFileForAbsen($file);

        //echo "done";
        redirect("presensi/upload");
    }

    function import_absen2($redirect = 0, $nip = 0, $tgl1 = null, $tgl2 = null) {
        $first = null;
        $last = null;
        $n_pegawai = Doctrine_Query::create()
                        ->select("COUNT(id) c")
                        ->from("DPegawai")
                        ->execute()
                        ->getFirst()
                ->c;
        $this->load->helper(array("database_odbc", "mycalendar"));
        $db = new database_odbc("klh");
        $db->connect();
        if ($nip == 0)
            $pin_c = 1;
        else {
            $pin_c = Doctrine::getTable("DPegawai")->findOneByNip($nip);
            if (empty($pin_c))
                $pin_c = 1;
            else
                $pin_c = "PIN = '" . $pin_c->pin . "'";
        }
        if (empty($tgl1))
            $tgl1 = date("Y-m-d");
        else
            $tgl1 = enformat_date($tgl1);
        if (empty($tgl2))
            $tgl2 = date("Y-m-d");
        else
            $tgl2 = enformat_date($tgl2);
        $res = $db->executeQuery("SELECT * FROM Absen WHERE $pin_c AND TGL_MASUK >= {d '$tgl1'} AND TGL_MASUK <= {d '$tgl2'}");
        while (($value = $db->fetch($res))) {
            $tanggal = explode(" ", $value["TGL_MASUK"]);

            if (!empty($value["PIN"]))
                $pegawai = Doctrine::getTable("DPegawai")->findOneByPin(str_pad($value["PIN"], 10, "0"));
            else {
                $pegawai = new DPegawai();
                $pegawai->id = 0;
            }
            $pegawai_id = $pegawai->id;

            if (!empty($value["JAM_MASUK"])) {
                $masuk = explode(" ", $value["JAM_MASUK"]);
                if (isset($masuk[1])) {
                    $masuk[1] = explode(":", $masuk[1]);
                } else {
                    $masuk[1] = array(0, 0, 0);
                }
            } else
                $masuk = array(0, array(0, 0, 0), 0);

            if (!empty($value["JAM_KELUAR"])) {
                $keluar = explode(" ", $value["JAM_KELUAR"]);
                if (isset($keluar[1])) {
                    $keluar[1] = explode(":", $keluar[1]);
                } else {
                    $keluar[1] = array(0, 0, 0);
                }
            } else
                $keluar = array(0, array(0, 0, 0), 0);

            $presensi = DPresensi::findByTanggalAndPegawaiID($tanggal[0], $pegawai_id);
            $presensi->tanggal = $tanggal[0];
            if (empty($first) || $first > $presensi->tanggal)
                $first = $presensi->tanggal;
            if (empty($last) || $last < $presensi->tanggal)
                $last = $presensi->tanggal;
            $presensi->pegawai_id = $pegawai_id;
            $presensi->status = 0;

            $presensi->masuk_j_h = $masuk[1][0];
            $presensi->masuk_m_h = $masuk[1][1];
            $presensi->keluar_j_h = $keluar[1][0];
            $presensi->keluar_m_h = $keluar[1][1];

            $presensi->masuk_j = $masuk[1][0];
            $presensi->masuk_m = $masuk[1][1];
            $presensi->keluar_j = $keluar[1][0];
            $presensi->keluar_m = $keluar[1][1];
            $presensi->setTotal();
            try {
                $presensi->save();
            } catch (Exception $e) {
                
            }
        }

        $first = date("Y-01-01", strtotime($first));
        $last = date("Y-12-31", strtotime($last));
        while ($first <= $last) {
            $dpc = new DPresensiCounter();
            $dpc->tanggal = $first;
            $dpc->setTotalPresensiPerYear(strtotime($first), $n_pegawai);
            $dpc->save();
            $first = date("Y-m-d", next_year(strtotime($first)));
        }

        if ($redirect) {
            $this->load->library('session');
            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Data presensi handkey berhasil disinkronisasi."
            );
            $this->session->set_flashdata("process_msg", $msg);
            $this->load->helper("url");
            redirect("presensi/upload");
        }
    }

    function _parseCsvFile($file, $columnheadings = false, $delimiter = ',', $enclosure = "\"", $sentinel_stat = false, $length = 10000) {
        $row = 1;
        $rows = array();
        $handle = fopen($file, 'r');

        $sentinel = array("sentinel", "1");
        while (($data = fgetcsv($handle, $length, $delimiter, $enclosure)) !== FALSE) {

            if (!($columnheadings == false) && ($row == 1)) {
                $headingTexts = $data;
            } elseif (!($columnheadings == false)) {
                foreach ($data as $key => $value) {
                    unset($data[$key]);
                    $data[$headingTexts[$key]] = $value;
                }
                if (!$sentinel_stat && $data[$sentinel[0]] == $sentinel[1])
                    $sentinel_stat = true;

                if ($sentinel_stat)
                    $rows[] = $data;
            } else {
                $rows[] = $data;
            }
            $row++;
        }

        fclose($handle);
        return $rows;
    }

    function _parseCsvFileForAbsen($file, $columnheadings = true, $delimiter = ',', $enclosure = "\"") {
        $row = 1;
        $handle = fopen($file, 'r');
        $first = null;
        $last = null;
        $n_pegawai = Doctrine_Query::create()
                        ->select("COUNT(id) c")
                        ->from("DPegawai")
                        ->execute()
                        ->getFirst()
                ->c;

        while (($data = fgetcsv($handle, 10000, $delimiter, $enclosure)) !== FALSE) {

            if (!($columnheadings == false) && ($row == 1)) {
                $headingTexts = $data;
            } elseif (!($columnheadings == false)) {
                foreach ($data as $key => $value) {
                    unset($data[$key]);
                    $data[$headingTexts[$key]] = $value;
                }

                $tanggal = explode("/", $data["TGL_MASUK"]);
                $temp = $tanggal[2] < 10 ? "0" . $tanggal[2] : $tanggal[2];
                $tanggal[2] = $tanggal[1] < 10 ? "0" . $tanggal[1] : $tanggal[1];
                $tanggal[1] = $tanggal[0] < 10 ? "0" . $tanggal[0] : $tanggal[0];
                $tanggal[0] = $temp;

                if (!empty($data["PIN"]))
                    $pegawai = Doctrine::getTable("DPegawai")->findOneByPin(str_pad($data["PIN"], 10, "0"));
                else {
                    $pegawai = new DPegawai();
                    $pegawai->id = 0;
                }
                $pegawai_id = $pegawai->id;

                if (!empty($data["JAM_MASUK"])) {
                    $masuk = explode(" ", $data["JAM_MASUK"]);
                    if (isset($masuk[1])) {
                        $masuk[1] = explode(":", $masuk[1]);
                        if ($masuk[2] == "PM" && $masuk[1][0] != 12)
                            $masuk[1][0] += 12;
                    } else {
                        $masuk[1] = array(0, 0, 0);
                    }
                } else
                    $masuk = array(0, array(0, 0, 0), 0);

                if (!empty($data["JAM_KELUAR"])) {
                    $keluar = explode(" ", $data["JAM_KELUAR"]);
                    if (isset($keluar[1])) {
                        $keluar[1] = explode(":", $keluar[1]);
                        if ($keluar[2] == "PM" && $keluar[1][0] != 12)
                            $keluar[1][0] += 12;
                    } else {
                        $keluar[1] = array(0, 0, 0);
                    }
                } else
                    $keluar = array(0, array(0, 0, 0), 0);

                $presensi = DPresensi::findByTanggalAndPegawaiID(implode("-", $tanggal), $pegawai_id);
                $presensi->tanggal = implode("-", $tanggal);
                if (empty($first) || $first > $presensi->tanggal)
                    $first = $presensi->tanggal;
                if (empty($last) || $last < $presensi->tanggal)
                    $last = $presensi->tanggal;
                $presensi->pegawai_id = $pegawai_id;
                $presensi->status = 0;

                $presensi->masuk_j_h = $masuk[1][0];
                $presensi->masuk_m_h = $masuk[1][1];
                $presensi->keluar_j_h = $keluar[1][0];
                $presensi->keluar_m_h = $keluar[1][1];

                $presensi->masuk_j = $masuk[1][0];
                $presensi->masuk_m = $masuk[1][1];
                $presensi->keluar_j = $keluar[1][0];
                $presensi->keluar_m = $keluar[1][1];
                $presensi->setTotal();
                try {
                    $presensi->save();
                } catch (Exception $e) {
                    
                }
            }
            $row++;
        }

        fclose($handle);

        $first = date("Y-01-01", strtotime($first));
        $last = date("Y-12-31", strtotime($last));
        while ($first <= $last) {
            $dpc = new DPresensiCounter();
            $dpc->tanggal = $first;
            $dpc->setTotalPresensiPerYear(strtotime($first), $n_pegawai);
            $dpc->save();
            $first = date("Y-m-d", next_year(strtotime($first)));
        }
    }

    function build_duk() {
        $pegawais = Doctrine::getTable("DPegawai")->findAll();
        foreach ($pegawais as $pegawai) {
            //pangkat
            $key1 = "999999-99-99";
            if (($riwayat = $pegawai->lastRiwayatPangkat()))
                $key1 = str_pad((99 - $riwayat->pangkat_id), 2, "0", STR_PAD_LEFT) . $riwayat->tmt;

            //jabatan
            $key2 = "99999999-99-99";
            if (($riwayat = $pegawai->lastRiwayatJabatan()))
                $key2 = str_pad(($riwayat->MJabatan->urutan_duk), 4, "9", STR_PAD_LEFT) . $riwayat->tmt;

            //masa kerja
            $key3 = $pegawai->tanggal_cpns ? $pegawai->tanggal_cpns : "9999-99-99";
            
            //@TODO: latihan jabatan
            $key4 = "9999-99-99";
            
            //pendidikan
            $key5 = "999999-99-99";
            if (($riwayat = $pegawai->lastRiwayatPendidikan()))
                $key5 = str_pad((99 - $riwayat->pendidikan_id), 2, STR_PAD_LEFT) . $riwayat->tanggal_ijazah;
            
            $pegawai->duk = $key1 . $key2 . $key3 . $key4 . $key5;

            $pegawai->save();
        }
    }

}
