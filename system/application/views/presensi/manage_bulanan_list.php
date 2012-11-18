<?php
$next = next_month($date);
$prev = next_month($date, -1);

$opt_pegawai = array();
$opt_pegawai["0"] = "-- Semua Pegawai --";
foreach ($pegawais as $pegawai)
    $opt_pegawai["$pegawai->id"] = $pegawai->nama;

$n_status = sizeof($status);
$n_pegawais = sizeof($pegawais);
?>
<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("presensi/manage/$uid/" . date("Y/m/d", $date)); ?>">Absensi Harian</a></li>
        <li><a href="#tabs-1">Absensi Bulanan</a></li>
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
            <?php echo anchor("presensi/manage/$uid/" . date("Y/m", $prev), "<div class=\"left\"><span class=\"ui-icon ui-icon-circle-triangle-w\"></span></div>"); ?>
            <?php echo anchor("presensi/manage/$uid/" . date("Y/m", $next), "<div class=\"right\"><span class=\"ui-icon ui-icon-circle-triangle-e\"></span></div>"); ?>
            <?php echo anchor("presensi/manage/$uid/" . date("Y", $date), "<span class=\"ui-datepicker-title\">" . date_id("F Y", $date) . "</span>"); ?>
        </div>
        <table id="statistik" cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <?php
                    foreach ($status as $s)
                        echo "<th>" . ucwords($s) . "</th>";
                    ?>
                    <th>Libur</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $format = date_id("F Y", $date);
                $l_format = date("Y/m/", $date);
                $a_format = date("Y-m-", $date);
                $t = date("t", $date);
                for ($i = 1; $i <= $t; $i++) {
                    echo "
                <tr>
                    <td>" . anchor("presensi/manage/$uid/" . $l_format . $i, padding_date($i) . " $format") . "</td>";
                    for ($j = 0; $j <= $n_status; $j++)
                        echo "
                    <td>" . $tppd[$a_format . padding_date($i)][$j] . "</td>";
                    echo "
                </tr>";
                }
                ?>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" class="form">
            <tbody>
                <tr>
                    <td style="text-align: right;">
                        <?php echo form_dropdown("pegawai_id", $opt_pegawai, $uid, "class=\"ignore\""); ?>
                        <input id="submit" type="submit" value="Saring" />
                        <a class="more_link" href="<?php echo site_url("presensi/manage/$uid/" . date("Y/m/0", $date) . "/excel"); ?>">Download</a>
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
    $(function() {
        $("#main_content").tabs({
            selected: 1,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });
        
//        $(".ui-icon").parent().hover(function() {
//            $(this).addClass("ui-state-hover");
//        }, function() {
//            $(this).removeClass("ui-state-hover");
//        });
        
        $("#statistik").dataTable({            
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false
        });
        
        $("#submit").button().click(function () {
            var id = $("select[name=pegawai_id]").val();
            location = "<?php echo site_url("presensi/manage"); ?>/" + id  + "/<?php echo date("Y/m", $date); ?>";
        });
        
        $(".more_link").button();
        $("select[name=pegawai_id]").addClass("def");
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

    div.left {
        float: left;
        margin-left: 10px;
    }

    div.right {
        float: right;
        margin-right: 10px;
    }

    #statistik th {
        cursor: pointer;
    }

    select.def {
        padding-top: 5px;
        padding-bottom: 5px;
        border-radius: 5px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
    }
</style>
