<script type="text/javascript" src="<?php echo site_url("js/swfobject.js"); ?>"></script>
<?php foreach ($graphs as $graph) {
    ?>
    <script type="text/javascript">
        swfobject.embedSWF(
        "<?php echo site_url("swf/open-flash-chart.swf"); ?>", "chart_<?php echo $graph; ?>",
        "<?php echo $chart_width ?>", "<?php echo $chart_height ?>",
        "9.0.0", "expressInstall.swf",
        {"data-file":"<?php echo site_url("ofc2/get_data_pie/$graph") ?>"}
    );
    </script>
<?php } ?>
<div id="main_content">
    <div id="buffer"></div>
    <?php
    $i = 1;
    foreach ($graphs as $graph) {
        echo "<div style=\"float: " . ($i % 2 ? "left" : "right") . ";\"><div id=\"chart_$graph\"></div></div>";
        $i++;
        if ($i % 2)
            echo "<div style=\"clear: both; height: 20px;\"></div>";
    }
    ?>
    <div style="clear: both;"></div>
</div>

<!-- script section -->
<script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript">
    $(function() {
        $("#header").css("position", "absolute");
        
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
        if($n[0] == 0 && $n[1] == 0)
            echo "$('h2').html('Dashboard');";
        else
            echo "$('#count_pangkat').html('" . $n[0] . "');$('#count_sanksi').html('" . $n[1] . "');";
        ?>
    });
    
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
    table.data th {
        cursor: pointer;
    }
    
    table.data a {
        text-decoration: none;
    }

    .dataTables_wrapper {
        min-height: 0;
    }
</style>
