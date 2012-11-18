<div id="main_content">
    <ul>
        <li class="<?php if ($hide_tab)
    echo "hidden"; ?>"><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li class="<?php if ($hide_tab)
                echo "hidden"; ?>"><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Ubah Foto <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open_multipart("pegawai/edit_process"); ?>
        <?php echo form_hidden("modul", "photo"); ?>
        <?php echo form_hidden("id", $pegawai->id); ?>
        <table class="form">
            <tbody id="upload_photo" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="userfile">Foto</label></td>
                    <td><?php echo form_upload("userfile", ""); ?></td>
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
    });
</script>
