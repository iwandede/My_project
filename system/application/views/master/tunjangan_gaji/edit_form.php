<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("master/gaji/manage"); ?>">Daftar Gaji</a></li>
        <li><a href="#tabs-1">Ubah Gaji</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("master/gaji/edit_process"); ?>
        <?php echo form_hidden("id", $gaji->id); ?>
        <table class="form">
            <tbody id="gaji" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="kode">Kode</label></td>
                    <td><?php echo form_input("kode", $gaji->kode); ?></td>
                </tr>
                <tr>
                    <td><label for="pangkat_id">Pangkat</label></td>
                    <td><?php echo form_dropdown("pangkat_id", $opt_pangkat, $gaji->pangkat_id); ?></td>
                </tr>
                <tr>
                    <td><label for="masa_kerja">Masa Kerja</label></td>
                    <td><?php echo form_input("masa_kerja", $gaji->masa_kerja, "id='masa_kerja'"); ?> tahun</td>
                </tr>
                <tr>
                    <td><label for="gaji">Nilai Gaji</label></td>
                    <td>Rp <?php echo form_input("gaji", $gaji->gaji, "id='gaji'"); ?></td>
                </tr>
                <tr>
                    <td><label for="kenaikan">Nilai Kenaikan Gaji</label></td>
                    <td>Rp <?php echo form_input("kenaikan", $gaji->kenaikan, "id='kenaikan'"); ?></td>
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
