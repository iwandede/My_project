<?php

class Pegawai extends Controller {

    var $form_fields;
    var $form_fields_nullable;
    var $temp;

    public function __construct() {
        parent::Controller();

        $this->load->helper(array("url", "form", "mycalendar"));

        if (!CurrentUser::user())
            redirect("home/error/403");

        $this->load->library(array("form_validation", "session"));
        $this->form_validation->set_message("required", "Data <i>%s</i> harus diisi.");
        $this->form_validation->set_message("numeric", "Data <i>%s</i> hanya dapat diisi dengan karakter angka (numeric).");
        $this->form_validation->set_message("matches", "Data <i>%s</i> tidak sesuai dengan data <i>%s</i>.");

        $this->form_fields_nullable = array();
    }

    public function detail($id, $mode = "html") {
        $data = array();
        $data["msg"] = $this->_get_flashdata();

        if (!$data["pegawai"] = Doctrine::getTable("DPegawai")->find($id))
            redirect("home/error/404");

        $this->load->helper(array("mydata_helper", "mycalendar_helper"));

        $data["current_user"] = CurrentUser::user();

        $html = array();
        $html["title"] = "Data " . $data["pegawai"]->nama;
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/detail_$mode", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        if ($mode == "excel") {
            $html["filename"] = "detail.xls";
            $this->load->view("template/excel", $html);
        } else
            $this->load->view("template/page", $html);
    }

    public function graph($id, $modul = "dp3") {
        $data = array();
        $data["id"] = $id;
        $data["msg"] = $this->_get_flashdata();

        if (!$data["pegawai"] = Doctrine::getTable("DPegawai")->find($id))
            redirect("home/error/404");

        $data["chart_width"] = "100%";
        $data["chart_height"] = 200;
        $data["graphs"] = array(
            "kesetiaan",
            "prestasi",
            "tanggung_jawab",
            "ketaatan",
            "kejujuran",
            "kerja_sama",
            "prakarsa",
            "kepemimpinan"
        );

        $html = array();
        $html["title"] = "Data " . $data["pegawai"]->nama;
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/graph_" . $modul . "_line", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function manage() {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["pegawais"] = Doctrine::getTable("DPegawai")->findAll();

        $html = array();
        $html["title"] = "Daftar Pegawai";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add() {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["opt_instansi"] = MInstansi::options_array();
        $data["opt_agama"] = MAgama::options_array();
        $data["opt_kelompok_pegawai"] = MSatuanKerja::options_array();
        $data["opt_status_pernikahan"] = MStatusPernikahan::options_array();

        $html = array();
        $html["title"] = "Tambah Pegawai";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/add_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function add_process() {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $this->form_validation->set_rules("nip", "NIP", "trim|required|unique[DPegawai.Nip]");
        $this->form_validation->set_rules("password", "Password", "trim|required");
        $this->form_validation->set_rules("passconf", "Ketik ulang password", "trim|required|matches[password]");
        if ($this->_umum_validate()) {
            $user = new DUser();
            $user->username = $this->input->post("nip");
            $user->password = $this->input->post("password");
            $user->save();

            $pegawai = new DPegawai();
            $pegawai->DUser = $user;
            foreach ($this->form_fields as $key => $value)
                $pegawai->set($key, $this->input->post($key));
            foreach ($this->form_fields_nullable as $key => $value) {
                $value = ($key == "tanggal_lahir" || $key == "tanggal_sumpah_pns" || $key == "tanggal_pensiun") ? enformat_date($this->input->post($key)) : $this->input->post($key);
                $pegawai->set($key, $value);
            }
            $pegawai->kenaikan_pangkat_berkala = $this->input->post("kenaikan_pangkat_berkala_tahun") * 12;
            $pegawai->kenaikan_pangkat_berkala += $this->input->post("kenaikan_pangkat_berkala_bulan");
            $pegawai->save();

            if (mkdir("files/u" . $user->id, 0777)) {
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Data pegawai berhasil ditambahkan."
                );
            } else {
                $user->delete();
                $pegawai->delete();
                $msg = array(
                    "type" => "ui-state-error",
                    "content" => "Data pegawai tidak berhasil ditambahkan."
                );
            }
            $target = "pegawai/manage";

            $this->session->set_flashdata("process_msg", $msg);
            redirect($target);
        } else
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );

        $this->temp = $msg;
        $this->add();
    }

    public function edit($id, $modul = "umum") {
        if (!(CurrentUser::user()->role == 1 || CurrentUser::user()->DPegawai->id == $id))
            redirect("home/error/403");

        if (!(CurrentUser::user()->role == 1) && !($modul == "umum" || $modul == "photo"))
            redirect("home/error/403");

        $data = array();

        if (!$data["pegawai"] = Doctrine::getTable("DPegawai")->find($id))
            redirect("home/error/404");

        $data["msg"] = $this->_get_flashdata();

        if (CurrentUser::user()->DPegawai->id == $id)
            $data["hide_tab"] = true;
        else
            $data["hide_tab"] = false;

        if ($modul == "umum") {
            $data["opt_instansi"] = MInstansi::options_array();
            $data["opt_agama"] = MAgama::options_array();
            $data["opt_kelompok_pegawai"] = MSatuanKerja::options_array();
            $data["opt_status_pernikahan"] = MStatusPernikahan::options_array();
        }
        if ($modul == "pangkat")
            $data["opt_pangkat"] = MPangkat::options_array();
        if ($modul == "jabatan" || $modul == "mutasi" || $modul == "dp3")
            $data["opt_jabatan"] = MJabatan::options_array();
        if ($modul == "mutasi")
            $data["opt_mutasi"] = MMutasi::options_array();
        if ($modul == "pendidikan_formal")
            $data["opt_pendidikan"] = MPendidikanFormal::options_array();
        if ($modul == "pendidikan_non_formal")
            $data["opt_pendidikan"] = MPendidikanNonFormal::options_array();
        if ($modul == "diklat")
            $data["opt_diklat"] = MDiklat::options_array();
        if ($modul == "cuti")
            $data["opt_cuti"] = MCuti::options_array();
        if ($modul == "dp3")
            $data["opt_pegawai"] = DPegawai::options_array();
        if ($modul == "hukuman")
            $data["opt_hukuman"] = MHukuman::options_array();
        if ($modul == "hukuman")
            $data["opt_unit"] = MSatuanKerja::options_array();
        if ($modul == "tanda_jasa")
            $data["opt_tanda_jasa"] = MTandaJasa::options_array();

        $html = array();
        $html["title"] = "Ubah Data " . $data["pegawai"]->nama;
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/edit_" . $modul . "_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        $modul = $this->input->post("modul");
        if ($modul == "umum")
            $this->_edit_umum_process();
        else if ($modul == "photo") {
            $id = $this->input->post("id");
            $pegawai = Doctrine::getTable("DPegawai")->find($id);

            $dir = "files/u" . $pegawai->user_id . "/";
            $config["upload_path"] = $dir;
            $config["file_name"] = "pp";
            $config["allowed_types"] = "jpg|png|gif|bmp";
            $config["max_size"] = "1000";
            $config["max_width"] = "1024";
            $config["max_height"] = "768";
            $config["overwrite"] = true;

            $this->load->library("upload", $config);
            $target = "pegawai/detail/$id";
            if (!$this->upload->do_upload()) {
                $msg = array(
                    "type" => "ui-state-error",
                    "content" => $this->upload->display_errors()
                );
                $target = "pegawai/edit/$id/photo";
            } else {
                $data = array("upload_data" => $this->upload->data());
                $file = $data["upload_data"]["file_name"];

                $filenew = rename($dir . $file, $dir . "pp.jpg");
                $msg = array(
                    "type" => "ui-state-highlight",
                    "content" => "Foto berhasil diubah."
                );
            }

            $this->session->set_flashdata("process_msg", $msg);
            redirect($target);
        } else {
            $relation = array(
                "anak" => array("anak", "anak_id", "DAnak"),
                "pangkat" => array("riwayat pangkat", "drp_id", "DRiwayatPangkat"),
                "jabatan" => array("riwayat jabatan", "drj_id", "DRiwayatJabatan"),
                "mutasi" => array("riwayat mutasi", "drm_id", "DRiwayatMutasi"),
                "gaji" => array("penggajian (KGB, KP4)", "drg_id", "DRiwayatGaji"),
                "pendidikan_formal" => array("riwayat pendidikan", "drpf_id", "DRiwayatPendidikan"),
                "diklat" => array("riwayat diklat struktural", "drd_id", "DRiwayatDiklat"),
                "pendidikan_non_formal" => array("riwayat diklat non struktural", "drpnf_id", "DRiwayatPendidikanNonFormal"),
                "seminar" => array("riwayat seminar / workshop", "drs_id", "DRiwayatSeminar"),
                "cuti" => array("riwayat cuti", "drc_id", "DRiwayatCuti"),
                "prestasi" => array("riwayat prestasi", "drpr_id", "DRiwayatPrestasi"),
                "kunjungan" => array("riwayat kunjungan luar negeri", "drk_id", "DRiwayatKunjungan"),
                "organisasi" => array("riwayat organisasi", "dro_id", "DRiwayatOrganisasi"),
                "tanda_jasa" => array("riwayat tanda jasa", "drtj_id", "DRiwayatTandaJasa"),
                "dp3" => array("riwayat DP3", "drdp3_id", "DRiwayatDP3"),
                "kesehatan" => array("riwayat kesehatan", "drke_id", "DRiwayatKesehatan"),
                "hukuman" => array("riwayat hukuman disiplin", "drh_id", "DRiwayatHukuman")
            );

            $id = $this->input->post("id");
            $target = "pegawai/edit/$id/$modul";
            $msg = array(
                "type" => "ui-state-error",
                "content" => "Data " . $relation[$modul][0] . " tidak berhasil diubah, silahkan coba lagi."
            );
            $val_function = "_" . $modul . "_validate";
            if ($this->$val_function()) {
                if (($pegawai = Doctrine::getTable("DPegawai")->find($id))) {
                    $diff = array();
                    $diff["array"] = true;
                    $temp_form_fields = array();
                    foreach ($this->form_fields as $key => $value) {
                        $this->form_fields[$key] = substr($key, 0, -2);
                        $data[$this->form_fields[$key]] = $this->input->post($this->form_fields[$key]);
                        $temp_form_fields[] = $this->form_fields[$key];
                    }
                    foreach ($this->form_fields_nullable as $key => $value) {
                        $this->form_fields[$key] = substr($key, 0, -2);
                        $data[$this->form_fields[$key]] = $this->input->post($this->form_fields[$key]);
                    }
                    if ($modul == "gaji") {
                        $data["kenaikan_berkala_tahun"] = $this->input->post("kenaikan_berkala_tahun");
                        $data["kenaikan_berkala_bulan"] = $this->input->post("kenaikan_berkala_bulan");
                    }
                    $i = 0;
                    foreach ($data[$relation[$modul][1]] as $item_id) {
                        switch ($modul) {
                            case "anak":
                                $item = new DAnak();
                                break;
                            case "pangkat":
                                $item = new DRiwayatPangkat();
                                break;
                            case "jabatan":
                                $jabatan = Doctrine::getTable("MJabatan")->findByNama_eselon($data["jabatan_id"][$i])->getFirst();
                                $data["jabatan_id"][$i] = $jabatan ? $jabatan->id : 0;
                                $item = new DRiwayatJabatan();
                                break;
                            case "mutasi":
                                $item = new DRiwayatMutasi();
                                break;
                            case "gaji":
                                $item = new DRiwayatGaji();
                                break;
                            case "pendidikan_formal":
                                $item = new DRiwayatPendidikan();
                                break;
                            case "pendidikan_non_formal":
                                $item = new DRiwayatPendidikanNonFormal();
                                break;
                            case "diklat":
                                $item = new DRiwayatDiklat();
                                break;
                            case "seminar":
                                $item = new DRiwayatSeminar();
                                break;
                            case "cuti":
                                $item = new DRiwayatCuti();
                                break;
                            case "prestasi":
                                $item = new DRiwayatPrestasi();
                                break;
                            case "kunjungan":
                                $item = new DRiwayatKunjungan();
                                break;
                            case "organisasi":
                                $item = new DRiwayatOrganisasi();
                                break;
                            case "tanda_jasa":
                                $item = new DRiwayatTandaJasa();
                                break;
                            case "dp3":
                                $item = new DRiwayatDP3();
                                break;
                            case "kesehatan":
                                $item = new DRiwayatKesehatan();
                                break;
                            case "hukuman":
                                $item = new DRiwayatHukuman();
                                break;
                        }
                        if ($item_id > 0)
                            $item = Doctrine::getTable($relation[$modul][2])->find($item_id);

                        $save = true;
                        foreach ($this->form_fields as $key) {
                            if (in_array($key, $temp_form_fields) && $data[$key][$i] == "")
                                $save = false;
                            else if ($key != $relation[$modul][1] && $item->get($key) != $data[$key][$i]) {
                                if (
                                        $key == "tanggal_lahir" ||
                                        $key == "tanggal_sk" ||
                                        $key == "tmt" ||
                                        $key == "tanggal" ||
                                        $key == "tanggal_mulai" ||
                                        $key == "tanggal_selesai" ||
                                        $key == "tanggal_berangkat" ||
                                        $key == "tanggal_kembali" ||
                                        0
                                )
                                    $data[$key][$i] = enformat_date($data[$key][$i]);
                                else if ($key == "tanggal_ijazah")
                                    $data[$key][$i] = $data[$key][$i] . "-01-01";

                                $item->set($key, $data[$key][$i]);
                                if (empty($diff[$i])) {
                                    $diff[$i] = array();
                                    $diff[$i]["id"] = $item_id;
                                }
                                $diff[$i][$key] = $data[$key][$i];
                            }
                        }

                        if ($modul == "gaji") {
                            $kb = $data["kenaikan_berkala_tahun"][$i] * 12;
                            $kb += $data["kenaikan_berkala_bulan"][$i];
                            if ($kb != $item->kenaikan_berkala) {
                                if (empty($diff[$i])) {
                                    $diff[$i] = array();
                                    $diff[$i]["id"] = $item_id;
                                }
                                $item->kenaikan_berkala = $kb;
                                $diff["kenaikan_berkala"] = $kb;
                            }
                        }

                        $item->DPegawai = $pegawai;
                        if ($save)
                            $item->save();
                        $i++;
                    }

                    $msg = array(
                        "type" => "ui-state-highlight",
                        "content" => "Data " . $relation[$modul][0] . " berhasil diubah."
                    );
                    $target = "pegawai/detail/$id";

                    //atur duk
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
            } else
                $msg = array(
                    "type" => "ui-state-error",
                    "content" => validation_errors()
                );

            $this->session->set_flashdata("process_msg", $msg);

            redirect($target);
        }
    }

    public function _edit_umum_process() {
        $id = $this->input->post("id");
        $target = "pegawai/edit/$id";
        $msg = array(
            "type" => "ui-state-error",
            "content" => "Data pegawai tidak berhasil diubah, silahkan coba lagi."
        );
        if ($this->_umum_validate()) {
            if (($pegawai = Doctrine::getTable("DPegawai")->find($id))) {
                $diff = array();
                $diff["id"] = $id;
                foreach ($this->form_fields as $key => $value)
                    if ($pegawai->get($key) != $this->input->post($key)) {
                        $pegawai->set($key, $this->input->post($key));
                        $diff[$key] = $this->input->post($key);
                    }
                foreach ($this->form_fields_nullable as $key => $value)
                    if ($pegawai->get($key) != $this->input->post($key)) {
                        $value = ($key == "tanggal_lahir" || $key == "tanggal_sumpah_pns" || $key == "tanggal_pensiun") ? enformat_date($this->input->post($key)) : $this->input->post($key);
                        $pegawai->set($key, $value);
                        $diff[$key] = $value;
                    }
                $kpb = $this->input->post("kenaikan_pangkat_berkala_tahun") * 12;
                $kpb += $this->input->post("kenaikan_pangkat_berkala_bulan");
                if ($kpb != $pegawai->kenaikan_pangkat_berkala) {
                    $pegawai->kenaikan_pangkat_berkala = $kpb;
                    $diff["kenaikan_pangkat_berkala"] = $kpb;
                }
                if (CurrentUser::user()->role == 1) {
                    $pegawai->save();

                    $msg = array(
                        "type" => "ui-state-highlight",
                        "content" => "Data pegawai berhasil diubah."
                    );
                    $target = "pegawai/manage";
                } else {
                    $temp = DTemp::findByUserAndModel($pegawai->user_id, "DPegawai");
                    if ($temp->count() == 0)
                        $temp = new DTemp ();
                    else
                        $temp = $temp->getFirst();

                    $temp->user_id = $pegawai->user_id;
                    $temp->model = "DPegawai";
                    $temp->diff = serialize($diff);
                    $temp->save();

                    $msg = array(
                        "type" => "ui-state-highlight",
                        "content" => "Data perubahan pegawai berhasil disimpan dan menunggu persetujuan administrator."
                    );
                    $target = "pegawai/detail/$pegawai->id";
                }
            }
        } else
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function delete_process($id, $modul = "pegawai") {
        $relation = array(
            "pegawai" => array("pegawai", "DPegawai"),
            "anak" => array("anak", "DAnak"),
            "pangkat" => array("riwayat pangkat", "DRiwayatPangkat"),
            "jabatan" => array("riwayat jabatan", "DRiwayatJabatan"),
            "mutasi" => array("riwayat mutasi", "DRiwayatMutasi"),
            "gaji" => array("penggajian (KGB, KP4)", "DRiwayatGaji"),
            "pendidikan_formal" => array("riwayat pendidikan", "DRiwayatPendidikan"),
            "diklat" => array("riwayat diklat struktural", "DRiwayatDiklat"),
            "pendidikan_non_formal" => array("riwayat diklat non struktural", "DRiwayatPendidikanNonFormal"),
            "seminar" => array("riwayat seminar / workshop", "DRiwayatSeminar"),
            "cuti" => array("riwayat cuti", "DRiwayatCuti"),
            "prestasi" => array("riwayat prestasi", "DRiwayatPrestasi"),
            "kunjungan" => array("riwayat kunjungan luar negeri", "DRiwayatKunjungan"),
            "organisasi" => array("riwayat organisasi", "DRiwayatOrganisasi"),
            "tanda_jasa" => array("riwayat tanda jasa", "DRiwayatTandaJasa"),
            "dp3" => array("riwayat DP3", "DRiwayatDP3"),
            "kesehatan" => array("riwayat kesehatan", "DRiwayatKesehatan"),
            "hukuman" => array("riwayat hukuman disiplin", "DRiwayatHukuman")
        );
        $uri_target = "pegawai/manage";

        if (CurrentUser::user()->role == 1) {
            $msg = array(
                "type" => "ui-state-error",
                "content" => "Data " . $relation[$modul][0] . " gagal dihapus."
            );

            if (($item = Doctrine::getTable($relation[$modul][1])->find($id)))
                if ($item->delete())
                    $msg = array(
                        "type" => "ui-state-highlight",
                        "content" => "Data " . $relation[$modul][0] . " berhasil dihapus."
                    );

            if ($modul != "pegawai") {
                $args = func_get_args();
                $args[0] = null;
                $args[1] = null;
                $uri_target = implode("/", $args);
            }
        }

        $this->session->set_flashdata("process_msg", $msg);
        redirect($uri_target);
    }

    public function laporan($mode = "list", $judul_laporan='', $status_kerja = 0, $golongan = 0, $eselon = 0, $unit_kerja = 0, $jenis_kelamin = 9) {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        if ($this->input->post("is_post")) {
            $temp = $this->input->post("status_kerja");
            $status_kerja = 0;
            if (in_array(0, $temp)) {
                if (in_array(1, $temp)) {
                    if (in_array(2, $temp))
                        $status_kerja = 9;
                    else
                        $status_kerja = 3;
                } else if (in_array(2, $temp))
                    $status_kerja = 4;
                else
                    $status_kerja = 0;
            } else if (in_array(1, $temp)) {
                if (in_array(2, $temp))
                    $status_kerja = 5;
                else
                    $status_kerja = 1;
            } else if (in_array(2, $temp))
                $status_kerja = 2;
            $golongan = $this->input->post("golongan");
            $eselon = $this->input->post("eselon");
            $unit_kerja = $this->input->post("unit_kerja");
            $jenis_kelamin = $this->input->post("jenis_kelamin");
            $judul_laporan = $this->input->post("judul_laporan");
        }

        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["args"] = array(
            "laporan" => "laporan",
            "status_kerja" => $status_kerja,
            "golongan" => $golongan,
            "eselon" => $eselon,
            "unit_kerja" => $unit_kerja,
            "jenis_kelamin" => $jenis_kelamin,
            "judul_laporan" => $judul_laporan
        );

        if ($mode == "excel")
            $data["pegawais"] = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin);
        $data["judul_laporan"] = $judul_laporan;

        $html = array();
        $html["title"] = "Laporan Umum";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/laporan_umum_$mode", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        if ($mode == "excel") {
            $html["filename"] = "laporan_umum.xls";
            $this->load->view("template/excel", $html);
        } else
            $this->load->view("template/page", $html);
    }

    public function laporanduk($mode = "list") {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();

        if ($mode == "excel")
            $data["pegawais"] = DPegawai::modified_query(0, 0, 0, 0, 9);

        $html = array();
        $html["title"] = "Daftar Urut Kepangkatan";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/duk_$mode", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        if ($mode == "excel") {
            $html["filename"] = "daftar_urut_kepangkatan.xls";
            $this->load->view("template/excel", $html);
        } else
            $this->load->view("template/page", $html);
    }

    public function laporanduk_data($status_kerja = 0, $golongan = 0, $eselon = 0, $unit_kerja = 0, $jenis_kelamin = 9) {
        parse_str(str_replace("?", "&", $_SERVER["REQUEST_URI"]), $_GET);
        $search = $this->input->get("sSearch", true);
        $limit = array($this->input->get("iDisplayLength", true), $this->input->get("iDisplayStart", true));

        $iTotal = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin, true);
        $iTotalDisplay = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin, true, $search);
        $pegawais = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin, false, $search, $limit);

        $aaData = array();
        $i = 1;
        foreach ($pegawais as $pegawai) {
            $last_pangkat = $pegawai->lastRiwayatPangkat();
            $last_pendidikan = $pegawai->lastRiwayatPendidikan();
            if($last_pendidikan) {
                $pendidikan = $last_pendidikan->MPendidikanFormal->nama_pendidikan . "<br />";
                $pendidikan .= (empty($last_pendidikan->jurusan) ? "" : $last_pendidikan->jurusan . "<br />");
                $pendidikan .= (empty($last_pendidikan->lembaga) ? "" : $last_pendidikan->lembaga . "<br />");
                $pendidikan .= substr($last_pendidikan->tanggal_ijazah, 0, 4);
            } else
                $pendidikan = "-";
            $last_jabatan = $pegawai->lastRiwayatJabatan();

            $diklat = "<ol>";
            foreach ($pegawai->DRDs as $drd)
                $diklat .= "<li>" . $drd->MDiklat->jenis_diklat . "<br />" . date_id("j F Y", strtotime($drd->tanggal)) . "</li>";
            $diklat .= "</ol>";

            $aaData[] = array(
                (string) ($limit[1] + $i++),
                $pegawai->nama . "<br />" . (!empty($pegawai->nip) ? $pegawai->nip : "-") . "<br />" . $pegawai->tempat_lahir . "/" . enformat_date($pegawai->tanggal_lahir),
                "<center>" . enformat_date($pegawai->tanggal_sumpah_pns) . "</center>",
                "<center>" . ($last_pangkat ? $last_pangkat->MPangkat->golongan_ruang . "<br />" . enformat_date($last_pangkat->tmt) : "-") .  "</center>",
                ($last_jabatan ? $last_jabatan->MJabatan->nama_eselon : "-"),
                "<center>" . $pegawai->eselon . "</center>",
                $pendidikan
            );
        }

        $output = array(
            "sEcho" => $this->input->get("sEcho", true),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotalDisplay,
            "aaData" => $aaData
        );

        echo json_encode($output);
    }

    public function laporandp3($mode = "list", $judul_laporan = '', $status_kerja = 0, $golongan = 0, $eselon = 0, $unit_kerja = 0, $jenis_kelamin = 9) {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        if ($this->input->post("is_post")) {
            $temp = $this->input->post("status_kerja");
            $status_kerja = 0;
            if (in_array(0, $temp)) {
                if (in_array(1, $temp)) {
                    if (in_array(2, $temp))
                        $status_kerja = 9;
                    else
                        $status_kerja = 3;
                } else if (in_array(2, $temp))
                    $status_kerja = 4;
                else
                    $status_kerja = 0;
            } else if (in_array(1, $temp)) {
                if (in_array(2, $temp))
                    $status_kerja = 5;
                else
                    $status_kerja = 1;
            } else if (in_array(2, $temp))
                $status_kerja = 2;
            $golongan = $this->input->post("golongan");
            $eselon = $this->input->post("eselon");
            $unit_kerja = $this->input->post("unit_kerja");
            $jenis_kelamin = $this->input->post("jenis_kelamin");
            $judul_laporan = $this->input->post("judul_laporan");
        }

        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["args"] = array(
            "laporan" => "laporandp3",
            "status_kerja" => $status_kerja,
            "golongan" => $golongan,
            "eselon" => $eselon,
            "unit_kerja" => $unit_kerja,
            "jenis_kelamin" => $jenis_kelamin,
            "judul_laporan" => $judul_laporan,
        );

        if ($mode == "excel")
            $data["pegawais"] = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin);
        $data["judul_laporan"] = $judul_laporan;

        $html = array();
        $html["title"] = "Laporan DP3";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("pegawai/dp3_$mode", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        if ($mode == "excel") {
            $html["filename"] = "laporan_dp3.xls";
            $this->load->view("template/excel", $html);
        } else
            $this->load->view("template/page", $html);
    }

    public function laporandp3_data($status_kerja = 0, $golongan = 0, $eselon = 0, $unit_kerja = 0, $jenis_kelamin = 9) {
        parse_str(str_replace("?", "&", $_SERVER["REQUEST_URI"]), $_GET);
        $search = $this->input->get("sSearch", true);
        $limit = array($this->input->get("iDisplayLength", true), $this->input->get("iDisplayStart", true));

        $iTotal = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin, true);
        $iTotalDisplay = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin, true, $search);
        $pegawais = DPegawai::modified_query($status_kerja, $golongan, $eselon, $unit_kerja, $jenis_kelamin, false, $search, $limit);

        $aaData = array();
        $i = 1;
        foreach ($pegawais as $pegawai) {
            $last_dp3 = $pegawai->lastRiwayatDP3();
            $jumlah = 0;
            $pp = "";
            $app = "";
            if ($last_dp3) {
                $jumlah += $last_dp3->kesetiaan;
                $jumlah += $last_dp3->prestasi;
                $jumlah += $last_dp3->tanggung_jawab;
                $jumlah += $last_dp3->ketaatan;
                $jumlah += $last_dp3->kejujuran;
                $jumlah += $last_dp3->kerja_sama;
                $jumlah += $last_dp3->prakarsa;
                $jumlah += $last_dp3->kepemimpinan;
                $pp = Doctrine::getTable("DPegawai")->find($last_dp3->penilai_pegawai_id)->nama;
                $app = Doctrine::getTable("DPegawai")->find($last_dp3->atasan_penilai_pegawai_id)->nama;
            }
            $rata = $jumlah / 8;

            $aaData[] = array(
                (string) ($limit[1] + $i++),
                $pegawai->nama,
                number_format($jumlah),
                number_format($rata),
                $pp,
                $app
            );
        }

        $output = array(
            "sEcho" => $this->input->get("sEcho", true),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotalDisplay,
            "aaData" => $aaData
        );

        echo json_encode($output);
    }

    public function _umum_validate() {
        $this->form_fields = array(
            "nama" => "Nama"
        );
        $this->form_fields_nullable = array(
            "nip" => "NIP",
            "nip_lama" => "NIP lama",
            "nomor_kartu_pegawai" => "Nomor kartu pegawai",
            "instansi_id" => "Instansi",
            "tempat_lahir" => "Tempat lahir",
            "tanggal_lahir" => "Tanggal lahir",
            "agama_id" => "Agama",
            "eselon" => "Eselon",
            "kelompok_pegawai_1" => "Unit kerja eselon I",
            "kelompok_pegawai_2" => "Unit kerja eselon II",
            "kelompok_pegawai_3" => "Unit kerja eselon III",
            "kelompok_pegawai_4" => "Unit kerja eselon IV",
            "status_kerja" => "Status kerja",
            "tanggal_pensiun" => "Tanggal Pensiun",
            "tinggi" => "Tinggi badan",
            "berat" => "Berat badan",
            "golongan_darah" => "Golongan darah",
            "jenis_kelamin" => "Jenis kelamin",
            "alamat" => "Alamat",
            "telepon_rumah" => "Telepon rumah",
            "telepon_genggam" => "Telepon genggam",
            "alamat_email" => "Alamat email",
            "status_pernikahan_id" => "Status pernikahan",
            "pasangan" => "Nama istri / suami",
            "nomor_kartu_pasangan" => "Nomor Karis / Karsu (jika istri / suami sebagai PNS)",
            "nomor_askes" => "Nomor ASKES",
            "nomor_npwp" => "NPWP",
            "nomor_induk_kependudukan" => "Nomor Induk Kependudukan",
            "tanggal_sumpah_pns" => "Tanggal sumpah PNS"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        foreach ($this->form_fields_nullable as $name => $label)
            if ($name == "tinggi" || $name == "berat")
                $this->form_validation->set_rules($name, $label, "trim|numeric");
            else
                $this->form_validation->set_rules($name, $label, "trim");

        $this->form_validation->set_rules("kenaikan_pangkat_berkala_tahun", "", "trim|numeric");
        $this->form_validation->set_rules("kenaikan_pangkat_berkala_bulan", "", "trim|numeric");

        return $this->form_validation->run();
    }

    public function _anak_validate() {
        $this->form_fields = array(
            "anak_id[]" => "ID",
            "nama[]" => "Nama Anak",
            "jenis_kelamin[]" => "Jenis Kelamin",
            "tanggal_lahir[]" => "Tanggal Lahir"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _pangkat_validate() {
        $this->form_fields = array(
            "drp_id[]" => "ID",
            "pangkat_id[]" => "Pangkat",
            "tmt[]" => "TMT"
        );
        $this->form_fields_nullable = array(
            "no_sk[]" => "No SK",
            "tanggal_sk[]" => "Tanggal SK"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _jabatan_validate() {
        $this->form_fields = array(
            "drj_id[]" => "ID",
            "jabatan_id[]" => "Jabatan",
            "tanggal_sk[]" => "Tanggal SK",
            "tmt[]" => "TMT"
        );
        $this->form_fields_nullable = array(
            "no_sk[]" => "No SK"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _mutasi_validate() {
        $this->form_fields = array(
            "drm_id[]" => "ID",
            "mutasi_id[]" => "Jenis Mutasi",
            "jabatan_id[]" => "Jabatan",
            "tanggal[]" => "Tanggal Mutasi",
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _gaji_validate() {
        $this->form_fields = array(
            "drg_id[]" => "ID",
            "gaji_pokok[]" => "Gaji Pokok",
            "tunjangan_jabatan[]" => "Tunjangan Jabatan",
            "tunjangan_pasangan[]" => "Tunjangan Istri/Suami",
            "tunjangan_anak[]" => "Tunjangan Anak",
            "nilai_kenaikan[]" => "Nilai Kenaikan"
        );
        $this->form_fields_nullable = array(
            "tanggal[]" => "Tanggal Berlaku"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _pendidikan_formal_validate() {
        $this->form_fields = array(
            "drpf_id[]" => "ID",
            "pendidikan_id[]" => "Jenjang",
            "tanggal_ijazah[]" => "Tanggal Ijazah"
        );
        $this->form_fields_nullable = array(
            "lembaga[]" => "Lembaga Pendidikan",
            "jurusan[]" => "Jurusan",
            "nomor_ijazah[]" => "No Ijazah"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _pendidikan_non_formal_validate() {
        $this->form_fields = array(
            "drpnf_id[]" => "ID",
            "tanggal_mulai[]" => "Tanggal Mulai",
            "tanggal_selesai[]" => "Tanggal Selesai"
        );
        $this->form_fields_nullable = array(
            "jenis_kursus[]" => "Jenis Diklat",
            "penyelenggara[]" => "Penyelenggara",
            "tempat[]" => "Tempat",
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _seminar_validate() {
        $this->form_fields = array(
            "drs_id[]" => "ID",
            "tanggal_mulai[]" => "Tanggal Mulai",
            "tanggal_selesai[]" => "Tanggal Selesai"
        );
        $this->form_fields_nullable = array(
            "jenis_seminar[]" => "Jenis Seminar / Workshop",
            "penyelenggara[]" => "Penyelenggara",
            "tempat[]" => "Tempat",
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _diklat_validate() {
        $this->form_fields = array(
            "drd_id[]" => "ID",
            "diklat_id[]" => "Jabatan",
            "tanggal_mulai[]" => "Tanggal Mulai",
            "tanggal_selesai[]" => "Tanggal Selesai"
        );
        $this->form_fields_nullable = array(
            "penyelenggara[]" => "Penyelenggara",
            "tempat[]" => "Tempat",
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _cuti_validate() {
        $this->form_fields = array(
            "drc_id[]" => "ID",
            "cuti_id[]" => "Jenis Cuti",
            "tanggal_mulai[]" => "Tanggal Mulai",
            "tanggal_selesai[]" => "Tanggal Selesai"
        );
        $this->form_fields_nullable = array(
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _prestasi_validate() {
        $this->form_fields = array(
            "drpr_id[]" => "ID",
            "jenis_prestasi[]" => "Jenis Prestasi",
            "tanggal[]" => "Tanggal"
        );
        $this->form_fields_nullable = array(
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _kunjungan_validate() {
        $this->form_fields = array(
            "drk_id[]" => "ID",
            "sumber_dana[]" => "Sumber Pendanaan",
            "tanggal_berangkat[]" => "Tanggal Berangkat",
            "tanggal_kembali[]" => "Tanggal Kembali"
        );
        $this->form_fields_nullable = array(
            "jenis_kunjungan[]" => "Jenis Kunjungan",
            "tujuan[]" => "Tujuan Kunjungan",
            "negara[]" => "Negara Tujuan",
            "penyelenggara[]" => "Penyelenggara",
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _organisasi_validate() {
        $this->form_fields = array(
            "dro_id[]" => "ID",
            "jenis_organisasi[]" => "Jenis Organisasi",
            "tanggal_mulai[]" => "Tanggal Mulai",
            "tanggal_selesai[]" => "Tanggal Selesai"
        );
        $this->form_fields_nullable = array(
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _tanda_jasa_validate() {
        $this->form_fields = array(
            "drtj_id[]" => "ID",
            "tanda_jasa_id[]" => "Jenis Tanda Jasa",
            "tanggal[]" => "Tanggal"
        );
        $this->form_fields_nullable = array(
            "keterangan[]" => "Pemberi Tanda Jasa"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _dp3_validate() {
        $this->form_fields = array(
            "drdp3_id[]" => "ID",
            "tanggal[]" => "Tanggal Penilaian",
            "jabatan_id[]" => "Jabatan",
            "kesetiaan[]" => "Kesetiaan",
            "prestasi[]" => "Prestasi Kerja",
            "tanggung_jawab[]" => "Tanggung Jawab",
            "ketaatan[]" => "Ketaatan",
            "kejujuran[]" => "Kejujuran",
            "kerja_sama[]" => "Kerja Sama",
            "prakarsa[]" => "Prakarsa",
            "kepemimpinan[]" => "Kepemimpinan",
            "penilai_pegawai_id[]" => "Pejabat Penilai",
            "penilai_jabatan_id[]" => "Pejabat Penilai",
            "atasan_penilai_pegawai_id[]" => "Atasan Pejabat Penilai",
            "atasan_penilai_jabatan_id[]" => "Atasan Pejabat Penilai"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    public function _kesehatan_validate() {
        $this->form_fields = array(
            "drke_id[]" => "ID",
            "jenis_penyakit[]" => "Jenis Penyakit",
            "rawat[]" => "Rawat Inap/Jalan",
            "tanggal[]" => "Tanggal"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //->form_validation->run();
    }

    public function _hukuman_validate() {
        $this->form_fields = array(
            "drh_id[]" => "ID",
            "hukuman_id[]" => "Jenis Hukuman Disiplin",
            "no_sk[]" => "No SK",
            "tanggal[]" => "Tanggal SK",
            "tanggal_mulai[]" => "Tanggal Mulai",
            "tanggal_selesai[]" => "Tanggal Selesai",
        );
        $this->form_fields_nullable = array(
            "keterangan[]" => "Keterangan"
        );
        foreach ($this->form_fields as $name => $label)
            $this->form_validation->set_rules($name, $label, "trim|required");
        return true; //$this->form_validation->run();
    }

    private function _get_flashdata() {
        $msg = $this->session->flashdata("process_msg");
        if (empty($msg))
            if (empty($this->temp))
                return array("type" => "hidden", "content" => "");
            else
                return $this->temp;
        else
            return $msg;
    }

}
