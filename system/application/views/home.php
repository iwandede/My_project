<script type="text/javascript" src="<?php echo site_url("js/swfobject.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("js/easySlider1.5.js"); ?>"></script>
<div id="main_content">
    <h2>Dashboard</h2><br />
    <h3>Notifikasi kenaikan pangkat (<a id="count_pangkat" href="javascript:;" onclick="notif_switch(0);">0</a>)</h3><br />
    <div id="notif_pangkat" style="display: none;">
        <ul>
            <li><a href="#tabs-1">Kenaikan Pangkat</a></li>
        </ul>
        <div id="tabs-1">
            <table cellpadding="0" cellspacing="0" border="0" class="data">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Pangkat</th>
                        <th>TMT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($pegawais as $pegawai) {
                        if (($pangkat = $pegawai->lastRiwayatPangkat()) && ($diff = _date_diff(strtotime($pangkat->tmt), time())) && ($diff["y"] * 12 + $diff["m"]) >= $pegawai->kenaikan_pangkat_berkala) {
                            ?>
                            <tr>
                                <td><?php echo anchor("pegawai/detail/" . $pegawai->id, $pegawai->nama); ?></td>
                                <td><?php echo anchor("pegawai/detail/" . $pegawai->id, $pangkat->MPangkat->nama_pangkat . " (" . $pangkat->MPangkat->golongan_ruang . ")"); ?></td>
                                <td><?php echo anchor("pegawai/detail/" . $pegawai->id, date_id("j F Y", strtotime($pangkat->tmt))); ?></td>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                    $n[0] = $i - 1;
                    ?>
                </tbody>
            </table>
            <table cellpadding="0" cellspacing="0" border="0" class="form">
                <tbody class="fieldset">
                    <tr class="button">
                        <td style="text-align: right;">
                            <a href="javascript:;" onclick="closeNotif()">Tutup</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h3>Notifikasi hukuman disiplin (<a id="count_sanksi" href="javascript:;" onclick="notif_switch(1);">0</a>)</h3><br />
    <div id="notif_sanksi" style="display: none;">
        <ul>
            <li><a href="#tabs-2">Hukuman Disiplin</a></li>
        </ul>
        <div id="tabs-2">
            <table cellpadding="0" cellspacing="0" border="0" class="data">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jenis Hukuman</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($pegawais as $pegawai) {
                        if (($pangkat = $pegawai->lastRiwayatPangkat())) {
                            $tmt = strtotime($pangkat->tmt);
                            foreach ($pegawai->DRHs as $drh) {
                                if ($tmt <= strtotime($drh->tanggal)) {
                                    ?>
                                    <tr>
                                        <td><?php echo anchor("pegawai/detail/" . $pegawai->id, $pegawai->nama); ?></td>
                                        <td><?php echo anchor("pegawai/detail/" . $pegawai->id, $drh->MHukuman->jenis_hukuman); ?></td>
                                        <td><?php echo anchor("pegawai/detail/" . $pegawai->id, date_id("j F Y", strtotime($drh->tanggal))); ?></td>
                                        <td><?php echo anchor("pegawai/detail/" . $pegawai->id, $drh->keterangan); ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                        }
                    }
                    $n[1] = $i - 1;
                    ?>
                </tbody>
            </table>
            <table cellpadding="0" cellspacing="0" border="0" class="form">
                <tbody class="fieldset">
                    <tr class="button">
                        <td style="text-align: right;">
                            <a href="javascript:;" onclick="closeNotif()">Tutup</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h2>Berita</h2>
    <div id="berita">
        <?php
        foreach ($beritas as $berita) {
            $src = file_exists("files/news/p" . $berita->id . ".jpg") ? "files/news/p" . $berita->id . ".jpg" : "files/news/p0.jpg";
            echo "
        <div class=\"berita_cell\">
            <div class=\"berita_img\">
                <img src=\"$src\" alt=\" \" />
            </div>
            <div class=\"berita_data\">
            <h3 class=berita-judul>" . $berita->judul . "</h3>
                " . write_some($berita->isi) . ' ... ' . anchor('berita/detail/' . $berita->id, '[selengkapnya]', array('class' => 'berita-selengkapnya')) . "
            </div>
            <div class=\"clear\"></div>
        </div>
";
        }
        ?>
        <div class="clear"></div>
    </div>
    <hr>
    <div class="content-list" >
        <ul>
            <li>
                <h2>Berulang Tahun</h2>
                <div id="slider-ultah">
                    <ul>
                        <?php
                        foreach ($ultahs as $ultah) {
                            $src = file_exists("files/u" . $ultah->id . "/pp.jpg") ? "files/u" . $ultah->id . "/pp.jpg" : "files/u1/pp.jpg";
                            echo "<li><div class=\"ultah_cell\">
                            <div class=\"ultah_img\">
                                <img src=\"$src\" alt=\" \" />
                            </div>
                            <div class=\"ultah_data\">
                                " . $ultah->nama .
                            '<p>' . date_id("j F Y", strtotime($ultah->tanggal_lahir)) . '</p>' . "
                            </div>
                            <div class=\"clear\"></div>
                        </div>
                </li>";
                        }
                        ?>
                    </ul>
                </div>


            </li>
            <li>
                <h2>Pensiun</h2>
                <div id="slider-pensiun">
                    <ul>
                        <?php
                        foreach ($pensiuns as $pensiun) {
                            $src = file_exists("files/u" . $pensiun->id . "/pp.jpg") ? "files/u" . $pensiun->id . "/pp.jpg" : "files/u1/pp.jpg";
                            echo "<li><div class=\"ultah_cell\">
                            <div class=\"ultah_img\">
                                <img src=\"$src\" alt=\" \" />
                            </div>
                            <div class=\"ultah_data\">
                                " . $pensiun->nama .
                            '<p>' . date_id("j F Y", strtotime($pensiun->tanggal_lahir)) . '</p>' . "
                            </div>
                            <div class=\"clear\"></div>
                        </div>
                </li>";
                        }
                        ?>
                    </ul>
                </div>

            </li>

        </ul>


    </div>

</div>

<!-- script section -->
<script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("js/carousel.js"); ?>"></script>
<script type="text/javascript">
    $(function() {
        $("#slider-ultah").carousel( {
            autoSlide: true ,
            dispItems: 3,
            loop: true,
            animSpeed: "slow",
            effect: "fade"
        });
        $("#slider-pensiun").carousel( {
            autoSlide: true ,
            dispItems: 3,
            loop: true,
            animSpeed: "slow",
            effect: "fade"
        });


        $("#notif_pangkat").tabs({
            selected: 0,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });

        $("#notif_sanksi").tabs({
            selected: 0,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });

        $("table.data").dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "oLanguage": {
                "sZeroRecords": ""
            }
        });

        $("tr.button a").button();

<?php
if ($n[0] == 0 && $n[1] == 0)
    echo "$('h2').html('Dashboard');";
else
    echo "$('#count_pangkat').html('" . $n[0] . "');$('#count_sanksi').html('" . $n[1] . "');";
?>
    });

    var stat = null;

    var notif_switch = function(sender) {
        if(sender == 0 && stat != "pangkat") {
            stat = "pangkat"
            notif_pangkat();
        } else if(sender == 1 && stat != "sanksi") {
            stat = "sanksi"
            notif_sanksi();
        } else {
            stat = null;
            closeNotif();
        }
    }

    var notif_pangkat = function() {
        $("#notif_sanksi").slideUp(500);
        $("#notif_pangkat").slideDown(500);
        $("#buffer").css({
            marginTop: 10,
            marginBottom: 10
        });
    }

    var notif_sanksi = function() {
        $("#notif_pangkat").slideUp(500);
        $("#notif_sanksi").slideDown(500);
        $("#buffer").css({
            marginTop: 10,
            marginBottom: 10
        });
    }

    var closeNotif = function() {
        $("#notif_pangkat").slideUp(500);
        $("#notif_sanksi").slideUp(500);
        $("#buffer").css({
            marginTop: 0,
            marginBottom: 0
        });
    }
</script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    .content-list{
        margin-top: 20px;
        width: 960px;
    }
    .content-list ul{
        margin: 0 auto;
    }
    .content-list li{
        display: inline-block;
        list-style: none;
        height: 200px;
        margin-right: 70px;

    }
    table.data th {
        cursor: pointer;
    }

    table.data a {
        text-decoration: none;
    }

    .dataTables_wrapper {
        min-height: 0;
    }

    .clear {
        clear: both;
    }

    #berita {
        margin-top: 5px;
    }

    .berita_cell {
        width: 285px;
        margin-right: 15px;
        margin-bottom: 5px;
        float: left;
    }

    .berita_img {
        width: 100px;
        height: 150px;
        margin-right: 5px;
        float: left;
    }

    .berita_img img {
        width: 100%;
    }

    .berita_data {
        float: left;
        max-width: 180px;
    }

    #ultah {
        margin-top: 5px;
    }

    .ultah_cell {
        width: 100px;
        margin-right: 15px;
        margin-bottom: 5px;
        float: left;
    }

    .ultah_img {
        width: 100px;
        height: 150px;
        margin-right: 5px;
        float: left;
    }

    .ultah_img img {
        width: 100%;
    }

    .ultah_data {
        float: left;
        max-width: 180px;
    }
    /*
    style berita
    */
    .berita-judul{
        font-weight: bolder;
        padding-bottom: 10px;
    }
    .berita-selengkapnya{
        color: #ff0000;
    }
    /*
    style ultah
    */
    #slider-pensiun{
        width: 400px;
    }
    #slider-ultah{
        width: 400px;
    }
    #slider-ultah li{
        list-style: none;
        display: inline-block;
        /*        width: 100px;*/
        margin: 0;
    }
    #slider-pensiun li{
        list-style: none;
        display: inline-block;
        /*        width: 100px;*/
        margin: 0;
    }
    #slider-ultah li img{
        width: 100px;
        height: 150px;
    }
    #slider-pensiun li img{
        width: 100px;
        height: 150px;
    }
    .carousel-wrap{
        display: inline-block;
        vertical-align: middle;
        width: 330px;
    }
    .carousel-control{
        border: 0 none;
        cursor: pointer;
        display: inline-block;
        height: 34px;
        line-height: 999px;
        overflow: hidden;
        text-indent: -9999px;
        vertical-align: middle;
        width: 34px;
    }
    .carousel-control.previous{
        background: url(img/button_prev.gif);
        background-repeat: no-repeat;
    }
    .carousel-control.next{
        background: url(img/button_next.gif);
        background-repeat: no-repeat;
    }
</style>
