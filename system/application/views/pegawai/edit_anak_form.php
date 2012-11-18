<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Ubah Data Anak <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/edit_process"); ?>
        <?php echo form_hidden("modul", "anak"); ?>
        <?php echo form_hidden("id", $pegawai->id); ?>
        <table cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anak</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Aksi Lain</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($pegawai->DAnaks as $anak) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("anak_id[]", $anak->id); ?></td>
                        <td><input type="text" name="nama[]" class="large" value="<?php echo $anak->nama; ?>" /></td>
                        <td><?php echo form_dropdown("jenis_kelamin[]", array("Laki-laki", "Perempuan"), $anak->jenis_kelamin); ?></td>
                        <td><input type="text" name="tanggal_lahir[]" class="date" value="<?php echo enformat_date($anak->tanggal_lahir); ?>" /></td>
                        <td><a href="<?php echo site_url("pegawai/delete_process/$anak->id/anak" . uri_string()); ?>"><img src="<?php echo site_url("img/b_drop.png"); ?>" alt="Hapus" title="Hapus" /></a></td>
                    </tr>
                    <?php
                    $i++;
                }

                if ($i == 1) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("anak_id[]", 0); ?></td>
                        <td><input type="text" name="nama[]" class="large" value="" /></td>
                        <td><?php echo form_dropdown("jenis_kelamin[]", array("Laki-laki", "Perempuan"), 0); ?></td>
                        <td><input type="text" name="tanggal_lahir[]" class="date" value="" /></td>
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
                        <a href="javascript:addAnak()">Tambah Data Anak</a>
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
    var addAnak = function() {
        $("table.data").dataTable().fnAddData( [
            next_i + "<input type=\"hidden\" name=\"anak_id[]\" value=\"0\" />",
            "<input type=\"text\" name=\"nama[]\" class=\"large\" value=\"\" />",
            "<select id=\"select_" + next_i + "\" name=\"jenis_kelamin[]\"><option value=\"0\" selected=\"selected\">Laki-laki</option><option value=\"1\">Perempuan</option></select>",
            "<input id=\"date_" + next_i + "\" type=\"text\" name=\"tanggal_lahir[]\" class=\"date\" value=\"\" />",
            "" ] );
	
        $("#date_" + next_i).datepicker({
            dateFormat: "dd-mm-yy",
            dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            changeMonth: true,
            changeYear: true
        });

        $("#select_" + next_i).each(function() {
            $(this).selectmenu({
                style: 'dropdown',
                maxHeight: 150
            });
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
