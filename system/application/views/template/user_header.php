<?php
$user = CurrentUser::user();
$current = explode("/", uri_string());
if (isset($current[1]) && $current[1] == "presensi")
    $current[1] = "pegawai";

function class_current($header, $current) {
    if ($header == "home" && sizeof($current) <= 1)
        return "current";

    if (in_array("error", $current))
        return "";

    if (in_array($header, $current))
        return "current";

    return "";
}
?>
<!--<div id="avatar"><img src="<?php echo site_url("favicon.ico"); ?>" alt="" /></div>-->
<div id="menu">
    <ul class="menu">
        <li class="dummy"></li>
        <li class="<?php echo class_current("home", $current); ?>">
            <a href="<?php echo site_url(""); ?>">
                <span style="display: inline-block" class="menu-icons icon-home"></span>
                <span>Home</span>
            </a>
        </li>
        <li class="<?php echo class_current("berita", $current); ?>">
            <a href="<?php echo site_url("berita"); ?>">
                <span style="display: inline-block" class="menu-icons icon-berita"></span>
                <span>Berita</span>
            </a>
        </li>
        <?php if (!empty($user) && $user->role == 1) { ?>
        <li class="<?php echo class_current("master", $current); ?>">
            <a href="#" class="parent">              
                <span>
                    <span style="display: inline-block" class="menu-icons icon-master"></span>
                    Master Data
                </span>
            </a>
            <div>
                <ul>
                    <li><a href="<?php echo site_url("master/agama/manage"); ?>"><span>Agama</span></a></li>
                    <li><a href="<?php echo site_url("master/cuti/manage"); ?>"><span>Cuti</span></a></li>
                    <li><a href="<?php echo site_url("master/diklat/manage"); ?>"><span>Diklat</span></a></li>
                    <li><a href="<?php echo site_url("master/gaji/manage"); ?>"><span>Gaji</span></a></li>
                    <li><a href="<?php echo site_url("master/libur/manage"); ?>"><span>Hari Libur</span></a></li>
                    <li><a href="<?php echo site_url("master/hukuman/manage"); ?>"><span>Hukuman Disiplin</span></a></li>
                    <li><a href="<?php echo site_url("master/instansi/manage"); ?>"><span>Instansi</span></a></li>
                    <li><a href="<?php echo site_url("master/jabatan/manage"); ?>"><span>Jabatan</span></a></li>
                    <li><a href="<?php echo site_url("master/pangkat/manage"); ?>"><span>Pangkat</span></a></li>
                    <li><a href="<?php echo site_url("master/pendidikanformal/manage"); ?>"><span>Pendidikan Formal</span></a></li>
                    <li><a href="<?php echo site_url("master/statuspernikahan/manage"); ?>"><span>Status Pernikahan</span></a></li>
                    <li><a href="<?php echo site_url("master/suratkeputusan/manage"); ?>"><span>Surat Keputusan</span></a></li>
                    <li><a href="<?php echo site_url("master/tandajasa/manage"); ?>"><span>Tanda Jasa</span></a></li>
                    <li><a href="<?php echo site_url("master/satuankerja/manage"); ?>"><span>Unit Kerja</span></a></li>
                    <!--<li><a href="<?php echo site_url("master/potongangaji/manage"); ?>"><span>Potongan Gaji</span></a></li>-->
                    <!--<li><a href="<?php echo site_url("master/pendidikannonformal/manage"); ?>"><span>Pendidikan Non Formal</span></a></li>-->
                    <!--<li><a href="<?php echo site_url("master/mutasi/manage"); ?>"><span>Jenis Mutasi</span></a></li>-->
                </ul>
            </div>
        </li>
        <?php } ?>
<?php if (!empty($user)) { ?>
        <li class="<?php echo class_current("pegawai", $current); ?>">
            <a href="#" class="parent">
                <span style="display: inline-block" class="menu-icons icon-pegawai"></span>
                <span>Pegawai</span>
            </a>
            <div>
                <ul>
<?php if (!empty($user->DPegawai->nama)) { ?>
                    <li>
                        <a href="<?php echo site_url("pegawai/detail/" . $user->DPegawai->id); ?>">
                            <span><?php echo $user->DPegawai->nama; ?></span>
                        </a>
                    </li>
                    <?php } ?>
<?php if ($user->role == 1) { ?>
                    <li><a href="<?php echo site_url("pegawai/manage"); ?>"><span>Daftar Pegawai</span></a></li>
                    <li><a href="<?php echo site_url("presensi"); ?>"><span>Absensi</span></a></li>
                    <li><a href="<?php echo site_url("pegawai/laporanduk"); ?>"><span>Daftar Urut Kepangkatan</span></a></li>
                    <li><a href="<?php echo site_url("pegawai/laporan"); ?>"><span>Laporan Umum</span></a></li>
                    <li><a href="<?php echo site_url("pegawai/laporandp3"); ?>"><span>Laporan DP3</span></a></li>
<?php } ?>
                </ul>
            </div>
        </li>
        <li class="<?php echo class_current("surat", $current); ?>">
            <a href="<?php echo site_url("surat/manage"); ?>">
                <span style="display: inline-block" class="menu-icons icon-berkas"></span>
                <span>Berkas</span>
            </a>
        </li>
        <li class="<?php echo class_current("grafik", $current); ?>">
            <a href="#" class="parent">
                <span style="display: inline-block" class="menu-icons icon-grafik"></span>
                <span>Grafik</span>
            </a>
            <div>
                <ul>
                    <li><a href="<?php echo site_url("grafik/gender"); ?>"><span>Statistik Jenis Kelamin Pegawai</span></a></li>
                    <li><a href="<?php echo site_url("grafik/pangkat"); ?>"><span>Statistik Pangkat Pegawai</span></a></li>
                    <li><a href="<?php echo site_url("grafik/pendidikan"); ?>"><span>Statistik Pendidikan Pegawai</span></a></li>
                </ul>
            </div>
        </li>
<?php } ?>
            <li class="<?php echo class_current("user", $current); ?>">
                <a href="<?php echo empty($user) ? site_url("user") : "#"; ?>" class="<?php echo empty($user) ? "" : "parent" ?>">
                    <span style="display: inline-block" class="menu-icons icon-user"></span>
                    <span><?php echo empty($user) ? "Login" : "User" ?></span>
                </a>
<?php if (!empty($user)) { ?>
                <div>
                    <ul>
<?php if ($user->role == 1) { ?>
                    <li><a href="<?php echo site_url("user/manage"); ?>"><span>Daftar User</span></a></li>
<?php } ?>
                    <li><a href="<?php echo site_url("user"); ?>"><span>Pengaturan</span></a></li>
                    <li><a href="#" class="parent"><span>Theme</span></a>
                        <div>
                            <ul>
                                <?php
                                $themes = CurrentUser::listThemes();
                                $i = 0;
                                foreach ($themes as $theme) {
                                    echo "<li>" . anchor("user/theme_process/$i" . uri_string(), "<span>" . ucwords(implode(" ", explode("-", $theme))) . "</span>") . "</li>";
                                    $i++;
                                }
                                ?>
                            </ul>
                        </div>
                    </li>
<?php echo "<li>" . anchor("user/logout", "<span>Logout</span>") . "</li>"; ?>
                            </ul>
                        </div>
<?php } ?>
        </li>
    </ul>
</div>
