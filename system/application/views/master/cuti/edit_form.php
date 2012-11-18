<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("master/cuti/manage"); ?>">Daftar Jenis Cuti</a></li>
        <li><a href="#tabs-1">Ubah Jenis Cuti</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("master/cuti/edit_process"); ?>
        <?php echo form_hidden("id", $cuti->id); ?>
        <table class="form">
            <tbody id="cuti" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="jenis_cuti">Jenis cuti</label></td>
                    <td><?php echo form_input("jenis_cuti", $cuti->jenis_cuti); ?></td>
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
