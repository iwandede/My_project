<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("surat/manage/$owner_id/$ref_id/$modul"); ?>">Daftar Berkas</a></li>
        <li><a href="#tabs-1">Tambah Berkas</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open_multipart("surat/add_process"); ?>
        <?php echo form_hidden("owner_id", $owner_id); ?>
        <?php echo form_hidden("cb_error", uri_string()); ?>
        <?php echo form_hidden("ref_id", $ref_id); ?>
        <?php echo form_hidden("modul", $modul); ?>
        <table class="form">
            <tbody id="upload_surat" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="userfile">Berkas</label></td>
                    <td><?php echo form_upload("userfile", ""); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px;"><label for="userfile_link">Berkas (link)</label></td>
                    <td><?php echo form_input("userfile_link", ""); ?></td>
                </tr>
                <tr>
                    <td><label for="judul">Judul</label></td>
                    <td><?php echo form_input("judul", ""); ?></td>
                </tr>
                <tr>
                    <td><label for="nomor">Nomor</label></td>
                    <td><?php echo form_input("nomor", ""); ?></td>
                </tr>
                <tr>
                    <td class="top_align"><label for="keterangan">Keterangan</label></td>
                    <td><textarea id="keterangan" name="keterangan" cols="60" rows="3"></textarea></td>
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
        
        $("table.form select").addClass("def");
        
        $("input:submit").button();
    });
</script>
