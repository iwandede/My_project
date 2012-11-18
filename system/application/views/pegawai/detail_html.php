<div id="main_content">
    <ul>
        <?php if ($current_user->role == 1) {
        ?>
            <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
            <li><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <?php } ?>
        <li><a href="#tabs-2">Data <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-2">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <div id="detail_pegawai">
            <h3><a href="#">Data umum</a></h3>
            <div>
                <img class="pp" src="<?php echo site_url("files/u" . $pegawai->user_id . "/pp.jpg"); ?>" alt="" />
                <div style="float: left; width: 80%;">
                    <table style="width: 100%;" class="vertical-aligned">
                        <tbody>
                            <tr>
                                <td style="width: 200px;">Nama</td>
                                <td style="width: 5px;">:</td>
                                <td style="min-width: 50%;"><?php echo $pegawai->nama; ?></td>
                            </tr>
                            <?php echo write_data("NIP", $pegawai->nip, ":"); ?>
                            <?php echo write_data("NIP lama", $pegawai->nip_lama, ":"); ?>
                            <?php echo write_data("Nomor kartu pegawai", $pegawai->nomor_kartu_pegawai, ":"); ?>
                            <?php echo write_data("Instansi", $pegawai->MInstansi->nama_instansi, ":"); ?>
                            <?php echo write_data("Tempat, tanggal lahir", $pegawai->tempat_lahir . ", " . date_id("j F Y", strtotime($pegawai->tanggal_lahir)), ":"); ?>
                            <?php echo write_data("Agama", $pegawai->MAgama->nama_agama, ":"); ?>
                            <?php $last_pangkat = $pegawai->lastRiwayatPangkat() ? $pegawai->lastRiwayatPangkat()->MPangkat->nama_pangkat . " (" . $pegawai->lastRiwayatPangkat()->MPangkat->golongan_ruang . ")" : ""; ?>
                            <?php echo write_data("Pangkat", $last_pangkat, ":"); ?>
                            <?php $last_pangkat = $pegawai->lastRiwayatPangkat() ? date_id("j F Y", strtotime($pegawai->lastRiwayatPangkat()->tmt)) : ""; ?>
                            <?php echo write_data("TMT Pangkat", $last_pangkat, ":"); ?>
                            <?php $last_jabatan = $pegawai->lastRiwayatJabatan() ? $pegawai->lastRiwayatJabatan()->MJabatan->nama_eselon : ""; ?>
                            <?php echo write_data("Jabatan", $last_jabatan, ":"); ?>
                            <?php $masa_pangkat = $pegawai->lastRiwayatPangkat() ? time_diff(time(), strtotime($pegawai->lastRiwayatPangkat()->tmt)) : ""; ?>
                            <?php echo write_data("Masa kerja golongan", $masa_pangkat, ":"); ?>
                            <?php $masa_all = $pegawai->tanggal_cpns ? time_diff(time(), strtotime($pegawai->tanggal_cpns)) : ""; ?>
                            <?php echo write_data("Masa kerja keseluruhan", $masa_all, ":"); ?>
                            <?php echo write_data("Eselon", $pegawai->eselon, ":"); ?>
                            <?php echo write_data("Unit kerja eselon I", $pegawai->MSK1->nama_unit_kerja, ":"); ?>
                            <?php echo write_data("Unit kerja eselon II", $pegawai->MSK2->nama_unit_kerja, ":"); ?>
                            <?php echo write_data("Unit kerja eselon III", $pegawai->MSK3->nama_unit_kerja, ":"); ?>
                            <?php echo write_data("Unit kerja eselon IV", $pegawai->MSK4->nama_unit_kerja, ":"); ?>
                            <?php $status_kerja = array("Aktif", "Pensiun", "Dipekerjakan"); ?>
                            <?php echo write_data("Status kerja", $status_kerja[$pegawai->status_kerja] . ($pegawai->status_kerja == 1 ? date_id(", j F Y", strtotime($pegawai->tanggal_pensiun)) : "")); ?>
                            <?php echo write_data("Tinggi badan", (int) $pegawai->tinggi . " cm", ":"); ?>
                            <?php echo write_data("Berat badan", (int) $pegawai->berat . " kg", ":"); ?>
                            <?php echo write_data("Golongan darah", strtoupper($pegawai->golongan_darah), ":"); ?>
                            <?php echo write_data("Jenis kelamin", $pegawai->jenis_kelamin ? "Perempuan" : "Laki-laki", ":"); ?>
                            <?php echo write_data("Alamat", $pegawai->alamat, ":"); ?>
                            <?php echo write_data("Telepon rumah", $pegawai->telepon_rumah, ":"); ?>
                            <?php echo write_data("Telepon genggam", $pegawai->telepon_genggam, ":"); ?>
                            <?php echo write_data("Alamat email", $pegawai->alamat_email, ":"); ?>
                            <?php echo write_data("Kenaikan pangkat berkala", month_to_year($pegawai->kenaikan_pangkat_berkala), ":"); ?>
                            <?php echo write_data("Status pernikahan", $pegawai->MStatusPernikahan->status_pernikahan, ":"); ?>
                            <?php echo write_data("Nama istri / suami", $pegawai->pasangan, ":"); ?>
                            <?php echo write_data("Nomor Karis / Karsu (jika istri / suami sebagai PNS)", $pegawai->nomor_kartu_pasangan, ":"); ?>
                            <?php echo write_data("Nomor ASKES", $pegawai->nomor_askes, ":"); ?>
                            <?php echo write_data("NPWP", $pegawai->nomor_npwp, ":"); ?>
                            <?php echo write_data("Nomor Induk Kependudukan", $pegawai->nomor_induk_kependudukan, ":"); ?>
                            <?php echo write_data("Tanggal sumpah PNS", date_id("j F Y", strtotime($pegawai->tanggal_sumpah_pns)), ":"); ?>
                            <?php echo write_data("Tanggal CPNS", date_id("j F Y", strtotime($pegawai->tanggal_cpns)), ":"); ?>
                        </tbody>
                    </table>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="form" style="clear: both;">
                    <tbody class="fieldset">
                        <tr class="button">
                            <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/photo", "Ubah Foto"); ?>
                                <?php echo anchor("pegawai/edit/" . $pegawai->id, "Ubah Data"); ?>
                                <?php echo anchor("pegawai/detail/" . $pegawai->id . "/excel", "Download Data"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Data anak</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Anak</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DAnaks as $anak) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $anak->nama; ?></td>
                                        <td><?php echo $anak->jenis_kelamin ? "Perempuan" : "Laki-laki"; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($anak->tanggal_lahir)); ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$anak->id/anak"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/anak", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat kepangkatan</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pangkat</th>
                            <th>No SK</th>
                            <th>Tanggal SK</th>
                            <th>TMT</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRPs as $pangkat) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $pangkat->MPangkat->nama_pangkat . " (" . $pangkat->MPangkat->golongan_ruang . ")"; ?></td>
                                        <td><?php echo $pangkat->no_sk; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pangkat->tanggal_sk)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pangkat->tmt)); ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$pangkat->id/pangkat"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/pangkat", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat jabatan</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jabatan</th>
                            <th>No SK</th>
                            <th>Tanggal SK</th>
                            <th>TMT</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRJs as $jabatan) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $jabatan->MJabatan->nama_eselon; ?></td>
                                        <td><?php echo $jabatan->no_sk; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($jabatan->tanggal_sk)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($jabatan->tmt)); ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$jabatan->id/jabatan"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/jabatan", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!--
            <h3><a href="#">Riwayat mutasi</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Mutasi</th>
                            <th>Jabatan</th>
                            <th>Tanggal Mutasi</th>
                            <th>Keterangan</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRMs as $mutasi) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $mutasi->MMutasi->jenis_mutasi; ?></td>
                                        <td><?php echo $mutasi->MJabatan->nama_eselon; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($mutasi->tanggal)); ?></td>
                                        <td><?php echo $mutasi->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$mutasi->id/mutasi"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/mutasi", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>-->
            <h3><a href="#">Penggajian (KGB, KP4)</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan Jabatan</th>
                            <th>Tunjangan Istri/Suami</th>
                            <th>Tunjangan Anak</th>
                            <th>Kenaikan Berkala</th>
                            <th>Nilai Kenaikan</th>
                            <th>Tanggal Berlaku</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRGs as $gaji) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>Rp <?php echo number_format($gaji->gaji_pokok, 2, ",", "."); ?></td>
                                        <td>Rp <?php echo number_format($gaji->tunjangan_jabatan, 2, ",", "."); ?></td>
                                        <td>Rp <?php echo number_format($gaji->tunjangan_pasangan, 2, ",", "."); ?></td>
                                        <td>Rp <?php echo number_format($gaji->tunjangan_anak, 2, ",", "."); ?></td>
                                        <td><?php echo month_to_year($gaji->kenaikan_berkala); ?></td>
                                        <td><?php echo $gaji->nilai_kenaikan; ?>%</td>
                                        <td><?php echo date_id("j F Y", strtotime($gaji->tanggal)); ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$gaji->id/gaji"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/gaji", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat pendidikan formal</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lembaga Pendidikan</th>
                            <th>Jenjang</th>
                            <th>Jurusan</th>
                            <th>Nomor Ijazah</th>
                            <th>Tahun</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRPFs as $pendidikan) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $pendidikan->lembaga; ?></td>
                                        <td><?php echo $pendidikan->MPendidikanFormal->nama_pendidikan; ?></td>
                                        <td><?php echo $pendidikan->jurusan; ?></td>
                                        <td><?php echo $pendidikan->nomor_ijazah; ?></td>
                                        <td><?php echo date("Y", strtotime($pendidikan->tanggal_ijazah)); ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$pendidikan->id/pendidikan_formal"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/pendidikan_formal", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat diklat struktural</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Diklat</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Penyelenggara</th>
                            <th>Tempat</th>
                            <th>Keterangan</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRDs as $pendidikan) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $pendidikan->MDiklat->jenis_diklat; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pendidikan->tanggal_mulai)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pendidikan->tanggal_selesai)); ?></td>
                                        <td><?php echo $pendidikan->penyelenggara; ?></td>
                                        <td><?php echo $pendidikan->tempat; ?></td>
                                        <td><?php echo $pendidikan->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$pendidikan->id/diklat"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/diklat", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat diklat non struktural</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Diklat</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Penyelenggara</th>
                            <th>Tempat</th>
                            <th>Keterangan</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRPNFs as $pendidikan) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $pendidikan->jenis_kursus; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pendidikan->tanggal_mulai)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pendidikan->tanggal_selesai)); ?></td>
                                        <td><?php echo $pendidikan->penyelenggara; ?></td>
                                        <td><?php echo $pendidikan->tempat; ?></td>
                                        <td><?php echo $pendidikan->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$pendidikan->id/pendidikan_non_formal"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/pendidikan_non_formal", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat seminar / workshop</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Seminar / Workshop</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Penyelenggara</th>
                            <th>Tempat</th>
                            <th>Keterangan</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRSs as $pendidikan) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $pendidikan->jenis_seminar; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pendidikan->tanggal_mulai)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($pendidikan->tanggal_selesai)); ?></td>
                                        <td><?php echo $pendidikan->penyelenggara; ?></td>
                                        <td><?php echo $pendidikan->tempat; ?></td>
                                        <td><?php echo $pendidikan->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$pendidikan->id/seminar"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/seminar", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat cuti</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Keterangan</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRCs as $cuti) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $cuti->MCuti->jenis_cuti; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($cuti->tanggal_mulai)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($cuti->tanggal_selesai)); ?></td>
                                        <td><?php echo $cuti->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$cuti->id/cuti"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/cuti", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--<h3><a href="#">Riwayat psikotes</a></h3>
            <div>
                belum diimplementasi
            </div>
            <h3><a href="#">Riwayat prestasi</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Prestasi</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRPrs as $prestasi) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $prestasi->jenis_prestasi; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($prestasi->tanggal)); ?></td>
                                        <td><?php echo $prestasi->keterangan; ?></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/prestasi", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>-->
            <h3><a href="#">Riwayat kunjungan luar negeri</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Berangkat</th>
                            <th>Tanggal Kembali</th>
                            <th>Tujuan Kunjungan</th>
                            <th>Negara Tujuan</th>
                            <th>Penyelenggara</th>
                            <th>Sumber Pendanaan</th>
                            <th>Keterangan</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRKs as $kunjungan) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($kunjungan->tanggal_berangkat)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($kunjungan->tanggal_kembali)); ?></td>
                                        <td><?php echo $kunjungan->tujuan; ?></td>
                                        <td><?php echo $kunjungan->negara; ?></td>
                                        <td><?php echo $kunjungan->penyelenggara; ?></td>
                                        <td><?php echo $kunjungan->sumber_dana ? "Non APBN" : "APBN"; ?></td>
                                        <td><?php echo $kunjungan->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$kunjungan->id/kunjungan"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/kunjungan", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat organisasi</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Organisasi</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Keterangan</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DROs as $organisasi) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $organisasi->jenis_organisasi; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($organisasi->tanggal_mulai)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($organisasi->tanggal_selesai)); ?></td>
                                        <td><?php echo $organisasi->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$organisasi->id/organisasi"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/organisasi", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat tanda jasa</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Tanda Jasa</th>
                            <th>Tanggal Pemberian</th>
                            <th>Pemberi Tanda Jasa</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRTJs as $tanda_jasa) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $tanda_jasa->MTandaJasa->nama; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($tanda_jasa->tanggal)); ?></td>
                                        <td><?php echo $tanda_jasa->keterangan; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$tanda_jasa->id/tanda_jasa"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/tanda_jasa", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat DP3</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Penilaian</th>
                            <th>Jabatan</th>
                            <th>Nilai</th>
                            <th>Pejabat Penilai</th>
                            <th>Atasan Pejabat Penilai</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRDP3s as $dp3) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($dp3->tanggal)); ?></td>
                                        <td><?php echo $dp3->MJabatan->nama_eselon; ?></td>
                                        <td>
                                            Kesetiaan: <b><?php echo $dp3->kesetiaan; ?></b><br />
                                            Prestasi Kerja: <b><?php echo $dp3->prestasi; ?></b><br />
                                            Tanggung Jawab: <b><?php echo $dp3->tanggung_jawab; ?></b><br />
                                            Ketaatan: <b><?php echo $dp3->ketaatan; ?></b><br />
                                            Kejujuran: <b><?php echo $dp3->kejujuran; ?></b><br />
                                            Kerja Sama: <b><?php echo $dp3->kerja_sama; ?></b><br />
                                            Prakarsa: <b><?php echo $dp3->prakarsa; ?></b><br />
                                            Kepemimpinan: <b><?php echo $dp3->kepemimpinan; ?></b><br />
                                        </td>
                                        <td><?php echo $dp3->Penilai->nama . "<br />" . $dp3->PenilaiJabatan->nama_eselon; ?></td>
                                        <td><?php echo $dp3->AtasanPenilai->nama . "<br />" . $dp3->AtasanPenilaiJabatan->nama_eselon; ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$dp3->id/dp3"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/graph/" . $pegawai->id . "/dp3", "Grafik"); ?>
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/dp3", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat kesehatan</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Penyakit</th>
                            <th>Rawat Inap/Jalan</th>
                            <th>Tanggal</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRKes as $kesehatan) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $kesehatan->jenis_penyakit; ?></td>
                                        <td>Rawat <?php echo $kesehatan->rawat ? "Jalan" : "Inap"; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($kesehatan->tanggal)); ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$kesehatan->id/kesehatan"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/kesehatan", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3><a href="#">Riwayat hukuman disiplin</a></h3>
            <div>
                <table cellpadding="0" cellspacing="0" border="0" class="data">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Hukuman</th>
                            <th>No SK</th>
                            <th>Tanggal SK</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $i = 1;
                                foreach ($pegawai->DRHs as $hukuman) {
                        ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $hukuman->MHukuman->jenis_hukuman; ?></td>
                                        <td><?php echo $hukuman->no_sk; ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($hukuman->tanggal)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($hukuman->tanggal_mulai)); ?></td>
                                        <td><?php echo date_id("j F Y", strtotime($hukuman->tanggal_selesai)); ?></td>
                                        <td><a href="<?php echo site_url("surat/manage/$pegawai->user_id/$hukuman->id/hukuman"); ?>"><img src="<?php echo site_url("img/b_views.png"); ?>" alt="Berkas" title="Berkas" /></a></td>
                                    </tr>
                        <?php
                                    $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0" class="form">
                            <tbody class="fieldset">
                                <tr class="button">
                                    <td style="text-align: right;">
                                <?php echo anchor("pegawai/edit/" . $pegawai->id . "/hukuman", "Ubah"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- script section -->
<script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
<script>
    $(function() {
        $("#main_content").tabs({
            selected: 2,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });

        $( "#detail_pegawai" ).accordion({
            autoHeight: false
        });

        $("tr.button a").button();

        $("table.data").dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "oLanguage": {
                "sZeroRecords": "Maaf, data tidak ditemukan"
            }
        });
    });
</script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    table.vertical-aligned td {
        vertical-align: top;
    }
    
    table.data th {
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
    }
</style>