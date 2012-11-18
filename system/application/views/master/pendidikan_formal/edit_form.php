<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("master/pendidikanformal/manage"); ?>">Daftar Pendidikan Formal</a></li>
        <li><a href="#tabs-1">Ubah Pendidikan Formal</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("master/pendidikanformal/edit_process"); ?>
        <?php echo form_hidden("id", $pendidikanformal->id); ?>
        <table class="form">
            <tbody id="pendidikanformal" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="nama_pendidikan">Nama tingkat pendidikan</label></td>
                    <td><?php echo form_input("nama_pendidikan", $pendidikanformal->nama_pendidikan); ?></td>
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
