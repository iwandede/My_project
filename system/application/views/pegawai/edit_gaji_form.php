<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Ubah Data Gaji <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/edit_process"); ?>
        <?php echo form_hidden("modul", "gaji"); ?>
        <?php echo form_hidden("id", $pegawai->id); ?>
        <table cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan Jabatan</th>
                    <th>Tunjangan Istri/Suami</th>
                    <th>Tunjangan Anak</th>
                    <th>Kenaikan Berkala</th>
                    <th>Nilai Kenaikan</th>
                    <th>Tanggal Berlaku</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($pegawai->DRGs as $drg) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drg_id[]", $drg->id); ?></td>
                        <td>Rp <input type="text" name="gaji_pokok[]" class="small" value="<?php echo $drg->gaji_pokok; ?>" /></td>
                        <td>Rp <input type="text" name="tunjangan_jabatan[]" class="small" value="<?php echo $drg->tunjangan_jabatan; ?>" /></td>
                        <td>Rp <input type="text" name="tunjangan_pasangan[]" class="small" value="<?php echo $drg->tunjangan_pasangan; ?>" /></td>
                        <td>Rp <input type="text" name="tunjangan_anak[]" class="small" value="<?php echo $drg->tunjangan_anak; ?>" /></td>
                        <td>
                            <input type="text" name="kenaikan_berkala_tahun[]" class="very_small" value="<?php echo floor($drg->kenaikan_berkala / 12); ?>" /> tahun<br />
                            <input type="text" name="kenaikan_berkala_bulan[]" class="very_small" value="<?php echo $drg->kenaikan_berkala % 12; ?>" /> bulan
                        </td>
                        <td><input type="text" name="nilai_kenaikan[]" class="very_small" value="<?php echo $drg->nilai_kenaikan; ?>" />%</td>
                        <td><input type="text" name="tanggal[]" class="date" value="<?php echo enformat_date($drg->tanggal); ?>" /></td>
                    </tr>
                    <?php
                    $i++;
                }

                if ($i == 1) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drg_id[]", 0); ?></td>
                        <td>Rp <input type="text" name="gaji_pokok[]" class="small" value="" /></td>
                        <td>Rp <input type="text" name="tunjangan_jabatan[]" class="small" value="" /></td>
                        <td>Rp <input type="text" name="tunjangan_pasangan[]" class="small" value="" /></td>
                        <td>Rp <input type="text" name="tunjangan_anak[]" class="small" value="" /></td>
                        <td>
                            <input type="text" name="kenaikan_berkala_tahun[]" class="very_small" value="0" /> tahun<br />
                            <input type="text" name="kenaikan_berkala_bulan[]" class="very_small" value="0" /> bulan
                        </td>
                        <td><input type="text" name="nilai_kenaikan[]" class="very_small" value="" /></td>
                        <td><input type="text" name="tanggal[]" class="date" value="" />%</td>
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
                        <a href="javascript:addGaji()">Tambah Data Gaji</a>
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
    var addGaji = function() {
        $("table.data").dataTable().fnAddData( [
            next_i + "<input type=\"hidden\" name=\"drg_id[]\" value=\"0\" />",
            "Rp <input type=\"text\" name=\"gaji_pokok[]\" class=\"small\" value=\"\" />",
            "Rp <input type=\"text\" name=\"tunjangan_jabatan[]\" class=\"small\" value=\"\" />",
            "Rp <input type=\"text\" name=\"tunjangan_pasangan[]\" class=\"small\" value=\"\" />",
            "Rp <input type=\"text\" name=\"tunjangan_anak[]\" class=\"small\" value=\"\" />",
            "<input type=\"text\" name=\"kenaikan_berkala_tahun[]\" class=\"very_small\" value=\"0\" /> tahun<br /><input type=\"text\" name=\"kenaikan_berkala_bulan[]\" class=\"very_small\" value=\"0\" /> bulan",
            "<input type=\"text\" name=\"nilai_kenaikan[]\" class=\"very_small\" value=\"\" />%",
            "<input type=\"text\" name=\"tanggal[]\" class=\"date\" value=\"\" />" ] );

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
        
        $("select").addClass("def");

        $("input.date").datepicker({
            dateFormat: "dd-mm-yy",
            dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            changeMonth: true,
            changeYear: true
        });
        
        $("tr.button a").button();
        
        $("input:submit").button();
    });
</script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    table.data th {
        padding: 10px 5px;
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
    }
</style>
