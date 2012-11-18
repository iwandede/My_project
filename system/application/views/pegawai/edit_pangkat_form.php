<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Ubah Data Pangkat <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/edit_process"); ?>
        <?php echo form_hidden("modul", "pangkat"); ?>
        <?php echo form_hidden("id", $pegawai->id); ?>
        <table cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pangkat</th>
                    <th>No SK</th>
                    <th>Tanggal SK</th>
                    <th>TMT</th>
                    <th>Aksi Lain</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($pegawai->DRPs as $drp) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drp_id[]", $drp->id); ?></td>
                        <td><?php echo form_dropdown("pangkat_id[]", $opt_pangkat, $drp->pangkat_id); ?></td>
                        <td><input type="text" name="no_sk[]" class="medium" value="<?php echo $drp->no_sk; ?>" /></td>
                        <td><input type="text" name="tanggal_sk[]" class="date" value="<?php echo enformat_date($drp->tanggal_sk); ?>" /></td>
                        <td><input type="text" name="tmt[]" class="date" value="<?php echo enformat_date($drp->tmt); ?>" /></td>
                        <td><a href="<?php echo site_url("pegawai/delete_process/$drp->id/pangkat" . uri_string()); ?>"><img src="<?php echo site_url("img/b_drop.png"); ?>" alt="Hapus" title="Hapus" /></a></td>
                    </tr>
                    <?php
                    $i++;
                }

                if ($i == 1) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drp_id[]", 0); ?></td>
                        <td><?php echo form_dropdown("pangkat_id[]", $opt_pangkat, 0); ?></td>
                        <td><input type="text" name="no_sk[]" class="medium" value="" /></td>
                        <td><input type="text" name="tanggal_sk[]" class="date" value="" /></td>
                        <td><input type="text" name="tmt[]" class="date" value="" /></td>
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
                        <a href="javascript:addPangkat()">Tambah Data Pangkat</a>
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
    var opt_pangkat = $("tbody select:first").html();
    var addPangkat = function() {
        $("table.data").dataTable().fnAddData( [
            next_i + "<input type=\"hidden\" name=\"drp_id[]\" value=\"0\" />",
            "<select id=\"select_" + next_i + "\" name=\"pangkat_id[]\">" + opt_pangkat + "</select>",
            "<input type=\"text\" name=\"no_sk[]\" class=\"medium\" value=\"\" />",
            "<input type=\"text\" name=\"tanggal_sk[]\" class=\"date\" value=\"\" />",
            "<input type=\"text\" name=\"tmt[]\" class=\"date\" value=\"\" />",
            "" ] );
        
        $("table.data tbody tr:last select").addClass("def").val(0);
	
        $("table.data tbody tr:last input.date").datepicker({
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
        
        //$("select").addClass("def");
        
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
