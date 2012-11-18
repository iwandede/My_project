<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("berita/manage"); ?>">Daftar Berita</a></li>
        <li><a href="#tabs-1">Tambah Berita</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open_multipart("berita/add_process"); ?>
        <table class="form">
            <tbody id="berita" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="judul">Judul berita</label></td>
                    <td><?php echo form_input("judul", ""); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px;"><label for="userfile">Foto</label></td>
                    <td><?php echo form_upload("userfile"); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px;"><label for="isi">Isi berita</label></td>
                    <td><?php echo form_textarea("isi", ""); ?></td>
                </tr>
            </tbody>
            <tbody class="fieldset">
                <tr class="button">
                    <td>&nbsp;</td>
                    <td>
                        <input id="submit" type="submit" value="Simpan" />
                    </td>
                </tr>
            </tbody>
        </table>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- script section -->
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
        
        $("select").addClass("def");
        
        $("input:submit").button();
    });
</script>
