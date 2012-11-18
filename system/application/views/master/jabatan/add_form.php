<?php
$opt_eselon = array("I" => "I", "II" => "II", "III" => "III", "IV" => "IV", "Staff" => "Staff");
?>
<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("master/jabatan/manage"); ?>">Daftar Jabatan</a></li>
        <li><a href="#tabs-1">Tambah Jabatan</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("master/jabatan/add_process"); ?>
        <table class="form">
            <tbody id="jabatan" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="nama_eselon">Nama jabatan</label></td>
                    <td><?php echo form_textarea("nama_eselon", ""); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px;"><label for="minimal_pangkat">Minimal pangkat</label></td>
                    <td><?php echo form_dropdown("minimal_pangkat", $opt_pangkat); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px;"><label for="eselon">Eselon</label></td>
                    <td><?php echo form_dropdown("eselon", $opt_eselon); ?></td>
                </tr>
                <tr>
                    <td style="width: 200px;"><label for="urutan_duk">Urutan DUK</label></td>
                    <td><?php echo form_input("urutan_duk"); ?></td>
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
