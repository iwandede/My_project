<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y/m/d")); ?>">Absensi Harian</a></li>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y/m")); ?>">Absensi Bulanan</a></li>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y")); ?>">Absensi Tahunan</a></li>
        <li><a href="<?php echo site_url("presensi/graph"); ?>">Grafik Absensi</a></li>
        <li><a href="<?php echo site_url("presensi/akumulasi"); ?>">Akumulasi Absensi</a></li>
        <li><a href="#tabs-1">Upload Absensi</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open_multipart("presensi/upload_process"); ?>
            <table class="form">
                <tbody id="handkey" class="fieldset">
                    <tr>
                        <td>Berkas presensi handkey</td>
                        <td><?php echo form_upload("userfile", ""); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input id="submit" type="submit" value="Upload" /></td>
                    </tr>
                </tbody>
            </table>
        <?php echo form_close(); ?>
            <br />
            <hr />
            <br />
        <?php echo form_open("presensi/upload", "id=\"form_sinkron\""); ?>
            <table class="form">
                <tbody id="sinkron" class="fieldset">
                    <tr>
                        <td>Nip</td>
                        <td><?php echo form_input("nip", ""); ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td><?php echo form_input("tgl1", "", "class=\"date\""); ?> - <?php echo form_input("tgl2", "", "class=\"date\""); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input id="submit" type="submit" value="Sinkronkan" /></td>
                    </tr>
                </tbody>
            </table>
        <?php echo form_close(); ?>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $("#main_content").tabs({
                selected: 5,
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
            $("input.date").datepicker({
                dateFormat: "dd-mm-yy",
                dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
                monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                changeMonth: true,
                changeYear: true
            });

            $("#form_sinkron").submit(function() {
                var nip = parseInt($("input[name=nip]").val());
                var tgl1 = $("input[name=tgl1]").val();
                var tgl2 = $("input[name=tgl2]").val();
                window.location = "<?php echo base_url(); ?>tables/import_absen2/1/" + nip + "/" + tgl1 + "/" + tgl2;
                return false;
            });
    });
</script>
