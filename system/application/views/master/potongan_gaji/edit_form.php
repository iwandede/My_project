<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("master/potongangaji/manage"); ?>">Daftar Potongan Gaji</a></li>
        <li><a href="#tabs-1">Ubah Potongan Gaji</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("master/potongangaji/edit_process"); ?>
        <?php echo form_hidden("id", $potongangaji->id); ?>
        <table class="form">
            <tbody id="potongangaji" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="nama_potongan">Nama jenis potongan</label></td>
                    <td><?php echo form_input("nama_potongan", $potongangaji->nama_potongan); ?></td>
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
