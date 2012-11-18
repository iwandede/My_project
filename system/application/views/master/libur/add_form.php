<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("master/libur/manage"); ?>">Daftar Hari Libur</a></li>
        <li><a href="#tabs-1">Tambah Hari Libur</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("master/libur/add_process"); ?>
        <table class="form">
            <tbody id="hari_libur" class="fieldset">
                <tr>
                    <td style="width: 200px;"><label for="periodik">Periode</label></td>
                    <td><?php echo form_dropdown("periodik", $periodik); ?></td>
                </tr>
                <tr>
                    <td><label for="waktu1">Waktu</label></td>
                    <td><?php echo form_input("waktu1", ""); ?></td>
                </tr>
                <tr>
                    <td><label for="waktu2">Waktu</label></td>
                    <td><?php echo form_dropdown("waktu2", $minggu, 0); ?></td>
                </tr>
                <tr>
                    <td><label for="waktu3">Waktu</label></td>
                    <td><?php echo form_input("waktu3", ""); ?></td>
                </tr>
                <tr>
                    <td><label for="waktu4">Waktu</label></td>
                    <td><?php echo form_input("waktu4", ""); ?></td>
                </tr>
                <tr>
                    <td><label for="keterangan">Keterangan</label></td>
                    <td><input id="keterangan" name="keterangan" type="text" length="64" /></td>
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
        
        var periodikChanged = function() {
            var index = parseInt($("select[name=periodik]").val());
            for(var i = 1; i < 5; i++)
                if(i != index + 1)
                    $("#hari_libur tr:eq(" + i + ")").hide();
            else
                $("#hari_libur tr:eq(" + i + ")").show();
        }
        $("select[name=periodik]").change(periodikChanged);
        
        $("input[name=waktu1]").datepicker({
            dateFormat: "yy-mm-dd",
            dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            changeMonth: true,
            changeYear: true
        });
        
        $("input[name=waktu3]").datepicker({
            dateFormat: "dd",
            dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"]
        });
        
        $("input[name=waktu4]").datepicker({
            dateFormat: "mm-dd",
            dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            changeMonth: true
        });
        
        $("input:submit").button();
        
        periodikChanged();
    });
</script>
