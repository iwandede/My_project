<?php
$next = next_day($date);
$prev = next_day($date, -1);

$opt_pegawai = array();
$opt_pegawai["0"] = "-- Pegawai --";
foreach ($pegawais as $pegawai)
    $opt_pegawai["$pegawai->id"] = $pegawai->nama;

$opt_jam = array();
for($i = 0; $i < 24; $i++)
    $opt_jam[] = $i;

$opt_menit = array();
for($i = 0; $i < 60; $i++)
    $opt_menit[] = $i;
?>
<div id="main_content">
    <ul>
        <li><a href="#tabs-1">Absensi Harian</a></li>
        <li><a href="<?php echo site_url("presensi/manage/$uid/" . date("Y/m", $date)); ?>">Absensi Bulanan</a></li>
        <li><a href="<?php echo site_url("presensi/manage/$uid/" . date("Y", $date)); ?>">Absensi Tahunan</a></li>
        <li><a href="<?php echo site_url("presensi/graph"); ?>">Grafik Absensi</a></li>
        <li><a href="<?php echo site_url("presensi/akumulasi/"); ?>">Akumulasi Absensi</a></li>
        <li><a href="<?php echo site_url("presensi/upload"); ?>">Upload Absensi</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <div class="calendar_pagination ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all">
            <?php echo anchor("presensi/manage/$uid/" . date("Y/m/d", $prev), "<div class=\"left\"><span class=\"ui-icon ui-icon-circle-triangle-w\"></span></div>"); ?>
            <?php echo anchor("presensi/manage/$uid/" . date("Y/m/d", $next), "<div class=\"right\"><span class=\"ui-icon ui-icon-circle-triangle-e\"></span></div>"); ?>
            <?php echo anchor("presensi/manage/$uid/" . date("Y/m", $date), "<span class=\"ui-datepicker-title\">" . date_id("j F Y", $date) . "</span>"); ?>
        </div>
        <?php echo form_open("presensi/add_process"); ?>
        <?php echo form_hidden("tanggal", date("Y-m-d", $date)); ?>
        <table cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Jam Masuk<br />(Hand Key)</th>
                    <th>Jam Keluar<br />(Hand Key)</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody id="daftar_pegawai" class="fieldset">
                <?php
                $i = 1;
                foreach($presensies as $presensi) {
                    $hasRow = true;
                    $presensi->masuk_j_h = empty($presensi->masuk_j_h) ? "00" : $presensi->masuk_j_h;
                    $presensi->masuk_m_h = empty($presensi->masuk_m_h) ? "00" : $presensi->masuk_m_h;
                    $presensi->keluar_j_h = empty($presensi->keluar_j_h) ? "00" : $presensi->keluar_j_h;
                    $presensi->keluar_m_h = empty($presensi->keluar_m_h) ? "00" : $presensi->keluar_m_h;
                    echo "
                <tr>
                    <td>" . $i . form_hidden("id[]", $presensi->id) . "</td>
                    <td>" . form_dropdown("pegawai_id[]", $opt_pegawai, $presensi->pegawai_id, "class=\"ignore\"") . "</td>
                    <td>" . form_dropdown("status[]", $status, $presensi->status, "class=\"ignore\"") . "</td>
                    <td>$presensi->masuk_j_h:$presensi->masuk_m_h</td>
                    <td>$presensi->keluar_j_h:$presensi->keluar_m_h</td>
                    <td>" . form_dropdown("masuk_j[]", $opt_jam, $presensi->masuk_j, "class=\"ignore\"") . ":" . form_dropdown("masuk_m[]", $opt_menit, $presensi->masuk_m, "class=\"ignore\"") . "</td>
                    <td>" . form_dropdown("keluar_j[]", $opt_jam, $presensi->keluar_j, "class=\"ignore\"") . ":" . form_dropdown("keluar_m[]", $opt_menit, $presensi->keluar_m, "class=\"ignore\"") . "</td>
                    <td><input type=\"text\" name=\"keterangan[]\" class=\"medium\" value=\"$presensi->keterangan\" /></td>
                </tr>";
                    $i++;
                }

                if($i == 1) {
                    echo "
                <tr>
                    <td>" . $i . form_hidden("id[]", 0) . "</td>
                    <td>" . form_dropdown("pegawai_id[]", $opt_pegawai, 0, "class=\"ignore\"") . "</td>
                    <td>" . form_dropdown("status[]", $status, 0, "class=\"ignore\"") . "</td>
                    <td>00:00</td>
                    <td>00:00</td>
                    <td>" . form_dropdown("masuk_j[]", $opt_jam, 0, "class=\"ignore\"") . ":" . form_dropdown("masuk_m[]", $opt_menit, 0, "class=\"ignore\"") . "</td>
                    <td>" . form_dropdown("keluar_j[]", $opt_jam, 0, "class=\"ignore\"") . ":" . form_dropdown("keluar_m[]", $opt_menit, 0, "class=\"ignore\"") . "</td>
                    <td><input type=\"text\" name=\"keterangan[]\" class=\"medium\" value=\"\" /></td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" class="form">
            <tbody class="fieldset">
                <tr class="button">
                    <td style="text-align: right;">
                        <a class="more_link" href="javascript:;" onclick="addPegawai();">Tambah Data Pegawai</a>
                        <a class="more_link" href="<?php echo site_url("presensi/manage/$uid/" . date("Y/m/d", $date) . "/excel"); ?>">Download</a>
                        <input id="submit" type="submit" value="Simpan" />
                    </td>
                </tr>
            </tbody>
        </table>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- script section -->
<script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript">
    var next_i = <?php echo $i; ?>;
    var n = next_i - 1;
    var opt_pegawai = $("tbody select:eq(0)").html();
    var opt_status = $("tbody select:eq(1)").html();
    var opt_jam = $("tbody select:eq(2)").html();
    var opt_menit = $("tbody select:eq(3)").html();
    var addPegawai = function() {
        $("table.data").dataTable().fnAddData( [
            next_i + "<input type=\"hidden\" name=\"id[]\" value=\"0\" />",
            "<select name=\"pegawai_id[]\">" + opt_pegawai + "</select>",
            "<select name=\"status[]\">" + opt_status + "</select>",
            "00:00",
            "00:00",
            "<select name=\"masuk_j[]\", class=\"ignore\">" + opt_jam + "</select>:<select name=\"masuk_m[]\", class=\"ignore\">" + opt_menit+ "</select>",
            "<select name=\"keluar_j[]\", class=\"ignore\">" + opt_jam + "</select>:<select name=\"keluar_m[]\", class=\"ignore\">" + opt_menit+ "</select>",
            "<input type=\"text\" name=\"keterangan[]\" class=\"medium\" value=\"\" />" ] );

        $("table.data tr:last select").val(0);
        next_i++;
        n++;
    }

    $(function() {
        $("#main_content").tabs({
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
            "bSort": false,
            "bFilter": false,
            "bInfo": false,
            "oLanguage": {
                "sZeroRecords": "Maaf, data tidak ditemukan"
            }
        });

//        $(".ui-icon").parent().hover(function() {
//            $(this).addClass("ui-state-hover");
//        }, function() {
//            $(this).removeClass("ui-state-hover");
//        });

//        $("select").addClass("def");

        $(".more_link").button();
        $("#submit").button();
    });
</script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    div.calendar_pagination {
        border: 1px solid #CCCCCC;
        text-align: center;
        margin: 5px auto;
        padding: 5px 0;
    }

    table.data tr {
        vertical-align: middle;
    }

    table.data th {
        padding: 10px 5px;
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
    }

    div.left {
        float: left;
        margin-left: 10px;
    }

    div.right {
        float: right;
        margin-right: 10px;
    }
</style>
