<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Ubah Data DP3 <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/edit_process"); ?>
        <?php echo form_hidden("modul", "dp3"); ?>
        <?php echo form_hidden("id", $pegawai->id); ?>
            <table cellpadding="0" cellspacing="0" border="0" class="data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Penilaian</th>
                        <th>Jabatan</th>
                        <th>Nilai</th>
                        <th>Pejabat Penilai</th>
                        <th>Atasan Pejabat Penilai</th>
                        <th>Aksi Lain</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($pegawai->DRDP3s as $drdp3) {
                ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drdp3_id[]", $drdp3->id); ?></td>
                        <td><input type="text" name="tanggal[]" class="date" value="<?php echo enformat_date($drdp3->tanggal); ?>" /></td>
                        <td><?php echo form_dropdown("jabatan_id[]", $opt_jabatan, $drdp3->jabatan_id, "class=\"ignore\""); ?></td>
                        <td>
                            <div class="label">Kesetiaan</div><input type="text" name="kesetiaan[]" class="very_small" value="<?php echo $drdp3->kesetiaan; ?>" /><br />
                            <div class="label">Prestasi</div><input type="text" name="prestasi[]" class="very_small" value="<?php echo $drdp3->prestasi; ?>" /><br />
                            <div class="label">Tanggung Jawab</div><input type="text" name="tanggung_jawab[]" class="very_small" value="<?php echo $drdp3->tanggung_jawab; ?>" /><br />
                            <div class="label">Ketaatan</div><input type="text" name="ketaatan[]" class="very_small" value="<?php echo $drdp3->ketaatan; ?>" /><br />
                            <div class="label">Kejujuran</div><input type="text" name="kejujuran[]" class="very_small" value="<?php echo $drdp3->kejujuran; ?>" /><br />
                            <div class="label">Kerja Sama</div><input type="text" name="kerja_sama[]" class="very_small" value="<?php echo $drdp3->kerja_sama; ?>" /><br />
                            <div class="label">Prakarsa</div><input type="text" name="prakarsa[]" class="very_small" value="<?php echo $drdp3->prakarsa; ?>" /><br />
                            <div class="label">Kepemimpinan</div><input type="text" name="kepemimpinan[]" class="very_small" value="<?php echo $drdp3->kepemimpinan; ?>" />
                        </td>
                        <td><?php echo form_dropdown("penilai_pegawai_id[]", $opt_pegawai, $drdp3->penilai_pegawai_id); ?><br /><?php echo form_dropdown("penilai_jabatan_id[]", $opt_jabatan, $drdp3->penilai_jabatan_id, "class=\"ignore\""); ?></td>
                        <td><?php echo form_dropdown("atasan_penilai_pegawai_id[]", $opt_pegawai, $drdp3->atasan_penilai_pegawai_id); ?><br /><?php echo form_dropdown("atasan_penilai_jabatan_id[]", $opt_jabatan, $drdp3->atasan_penilai_jabatan_id, "class=\"ignore\""); ?></td>
                        <td><a href="<?php echo site_url("pegawai/delete_process/$drdp3->id/dp3" . uri_string()); ?>"><img src="<?php echo site_url("img/b_drop.png"); ?>" alt="Hapus" title="Hapus" /></a></td>
                    </tr>
                <?php
                    $i++;
                }

                if ($i == 1) {
                ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drdp3_id[]", 0); ?></td>
                        <td><input type="text" name="tanggal[]" class="date" value="" /></td>
                        <td><?php echo form_dropdown("jabatan_id[]", $opt_jabatan); ?></td>
                        <td>
                            <div class="label">Kesetiaan</div><input type="text" name="kesetiaan[]" class="very_small" value="" /><br />
                            <div class="label">Prestasi</div><input type="text" name="prestasi[]" class="very_small" value="" /><br />
                            <div class="label">Tanggung Jawab</div><input type="text" name="tanggung_jawab[]" class="very_small" value="" /><br />
                            <div class="label">Ketaatan</div><input type="text" name="ketaatan[]" class="very_small" value="" /><br />
                            <div class="label">Kejujuran</div><input type="text" name="kejujuran[]" class="very_small" value="" /><br />
                            <div class="label">Kerja Sama</div><input type="text" name="kerja_sama[]" class="very_small" value="" /><br />
                            <div class="label">Prakarsa</div><input type="text" name="prakarsa[]" class="very_small" value="" /><br />
                            <div class="label">Kepemimpinan</div><input type="text" name="kepemimpinan[]" class="very_small" value="" />
                        </td>
                        <td><?php echo form_dropdown("penilai_pegawai_id[]", $opt_pegawai); ?><br /><?php echo form_dropdown("penilai_jabatan_id[]", $opt_jabatan, 0, "class=\"ignore\""); ?></td>
                        <td><?php echo form_dropdown("atasan_penilai_pegawai_id[]", $opt_pegawai); ?><br /><?php echo form_dropdown("atasan_penilai_jabatan_id[]", $opt_jabatan, 0, "class=\"ignore\""); ?></td>
                        <td></td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" class="form">
            <tbody class="fieldset">                
                <tr class="button">
                    <td style="text-align: right;">
                        <a href="javascript:addDP3()">Tambah Data DP3</a>
                        <input id="submit" type="submit" value="Simpan" />
                    </td>
                </tr>
            </tbody>
        </table>
        <?php echo form_close(); ?>
            </div>
        </div>

        <!-- script section -->
        <script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
        <script type="text/javascript">
            var next_i = <?php echo $i; ?>;
            var n = next_i - 1;
            var opt_jabatan = $("tbody select:first").html();
            var opt_pegawai = $("tbody select:eq(1)").html();
            var addDP3 = function() {
                $("table.data").dataTable().fnAddData( [
                    next_i + "<input type=\"hidden\" name=\"drdp3_id[]\" value=\"0\" />",
                    "<input type=\"text\" name=\"tanggal[]\" class=\"date\" value=\"\" />",
                    "<select name=\"jabatan_id[]\">" + opt_jabatan + "</select>",
                    "<div class=\"label\">Kesetiaan</div><input type=\"text\" name=\"kesetiaan[]\" class=\"very_small\" value=\"\" /><br /><div class=\"label\">Prestasi</div><input type=\"text\" name=\"prestasi[]\" class=\"very_small\" value=\"\" /><br /><div class=\"label\">Tanggung Jawab</div><input type=\"text\" name=\"tanggung_jawab[]\" class=\"very_small\" value=\"\" /><br /><div class=\"label\">Ketaatan</div><input type=\"text\" name=\"ketaatan[]\" class=\"very_small\" value=\"\" /><br /><div class=\"label\">Kejujuran</div><input type=\"text\" name=\"kejujuran[]\" class=\"very_small\" value=\"\" /><br /><div class=\"label\">Kerja Sama</div><input type=\"text\" name=\"kerja_sama[]\" class=\"very_small\" value=\"\" /><br /><div class=\"label\">Prakarsa</div><input type=\"text\" name=\"prakarsa[]\" class=\"very_small\" value=\"\" /><br /><div class=\"label\">Kepemimpinan</div><input type=\"text\" name=\"kepemimpinan[]\" class=\"very_small\" value=\"\" />",
                    "<select name=\"penilai_pegawai_id[]\">" + opt_pegawai + "</select><br /><select name=\"penilai_jabatan_id[]\">" + opt_jabatan + "</select>",
                    "<select name=\"atasan_penilai_pegawai_id[]\">" + opt_pegawai + "</select><br /><select name=\"atasan_penilai_jabatan_id[]\">" + opt_jabatan + "</select>",
                    "" ] );

                $("table.data tbody tr:last select").addClass("def").val(0).each(function() {
                    $(this).selectmenu({
                        style: 'dropdown',
                        width: 200,
                        menuWidth: 450,
                        maxHeight: 150
                    });
                });

                $("table.data tbody tr:last input.date").datepicker({
                    dateFormat: "dd-mm-yy",
                    dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
                    monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                    changeMonth: true,
                    changeYear: true
                });

                next_i++;
                n++;
            }

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

                $("table.data").dataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bSort": false,
                    "bFilter": false,
                    "bInfo": false,
                    "oLanguage": {
                        "sZeroRecords": "Maaf, data tidak ditemukan"
                    }
                });

                $("input.date").datepicker({
                    dateFormat: "dd-mm-yy",
                    dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
                    monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                    changeMonth: true,
                    changeYear: true
                });

                $("select.ignore").each(function() {
                    $(this).selectmenu({
                        style: 'dropdown',
                        width: 200,
                        menuWidth: 450,
                        maxHeight: 150
                    });
                });

                $("tr.button a").button();

                $("input:submit").button();
            });
        </script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    div.label {
        width: 100px;
        float: left;
        clear: both;
    }
    table.data th {
        padding: 10px 5px;
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
    }
</style>
