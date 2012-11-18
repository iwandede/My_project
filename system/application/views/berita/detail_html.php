<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("berita/manage"); ?>">Daftar Berita</a></li>
        <?php if ($current_user->role == 1) {
            ?>
            <li><a href="<?php echo site_url("berita/add"); ?>">Tambah Berita</a></li>
        <?php } ?>
        <li><a href="#tabs-1">Detail Berita</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <div>
            <h2><?php echo $berita->judul; ?></h2>
            <?php
            $src = file_exists("files/news/p" . $berita->id . ".jpg") ? "files/news/p" . $berita->id . ".jpg" : "files/news/p0.jpg";
            echo "<img src='" . site_url($src) . "' alt='' align='left' style='width: 400px; margin: 10px 10px 10px 0;' />";
            ?>
        </div>
        <p >
        <div align="justify">
            <?php echo str_replace("\n", "<br />", $berita->isi); ?>
        </div>
        </p>
        <div class="clear"></div>
    </div>
</div>

<!-- script section -->
<script type="text/javascript">
    $(function() {
        $("#main_content").tabs({
            selected: <?php echo ($current_user->role == 1 ? 2 : 1); ?>,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });

        $("select").addClass("def");

        $("input:submit").button();
    });
</script>
