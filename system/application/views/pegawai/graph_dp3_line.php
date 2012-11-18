<script type="text/javascript" src="<?php echo site_url("js/swfobject.js"); ?>"></script>
<?php foreach ($graphs as $graph) {
 ?>
    <script type="text/javascript">
        swfobject.embedSWF(
        "<?php echo site_url("swf/open-flash-chart.swf"); ?>", "chart_<?php echo $graph; ?>",
        "<?php echo $chart_width ?>", "<?php echo $chart_height ?>",
        "9.0.0", "expressInstall.swf",
        {"data-file":"<?php echo site_url("ofc2/get_data_line/$id/$graph") ?>"}
    );
    </script>
<?php } ?>
<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Grafik DP3 <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
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
        
        $("#header").css("position", "absolute");
    });
</script>

