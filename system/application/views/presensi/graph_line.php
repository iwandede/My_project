<script type="text/javascript" src="<?php echo site_url("js/swfobject.js"); ?>"></script>
<?php foreach ($graphs as $graph) {
    ?>
    <script type="text/javascript">
        swfobject.embedSWF(
        "<?php echo site_url("swf/open-flash-chart.swf"); ?>", "chart_<?php echo $graph; ?>",
        "<?php echo $chart_width ?>", "<?php echo $chart_height ?>",
        "9.0.0", "expressInstall.swf",
        {"data-file":"<?php echo site_url("ofc2/get_data_line2/$graph/FFFFFF/$date") ?>"}
    );
    </script>
<?php } ?>
<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y/m/d")); ?>">Absensi Harian</a></li>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y/m")); ?>">Absensi Bulanan</a></li>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y")); ?>">Absensi Tahunan</a></li>
        <li><a href="#tabs-1">Grafik Absensi</a></li>
        <li><a href="<?php echo site_url("presensi/akumulasi/"); ?>">Akumulasi Absensi</a></li>
        <li><a href="<?php echo site_url("presensi/upload"); ?>">Upload Absensi</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <div>
            <?php
            echo form_open("presensi/graph");
            $bulan = array(
                "01" => "Januari",
                "02" => "Februari",
                "03" => "Maret",
                "04" => "April",
                "05" => "Mei",
                "06" => "Juni",
                "07" => "Juli",
                "08" => "Agustus",
                "09" => "September",
                "10" => "Oktober",
                "11" => "November",
                "12" => "Desember",
            );
            $tahun = array();
            for ($i = 2000; $i <= date("Y"); $i++)
                $tahun[$i] = $i;
            ?>
            <table class="form">
                <tbody class="fieldset">
                    <tr>
                        <td>Bulan</td>
                        <td><?php echo form_dropdown("bulan", $bulan, substr($date, 5, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Tahun</td>
                        <td><?php echo form_dropdown("tahun", $tahun, substr($date, 0, 4)); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Atur" /></td>
                    </tr>
                </tbody>
            </table>
            <?php echo form_close(); ?>
        </div>
        <?php
        foreach ($graphs as $graph)
            echo "<div id=\"chart_$graph\"></div><br /><br />";
        ?>
        <div style="clear: both;"></div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $("#main_content").tabs({
            selected: 3,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });
        
        $("input:submit").button();
        
        $("#header").css("position", "absolute");
    });
</script>

