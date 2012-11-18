<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Ubah Data Pendidikan Formal <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/edit_process"); ?>
        <?php echo form_hidden("modul", "pendidikan_formal"); ?>
        <?php echo form_hidden("id", $pegawai->id); ?>
        <table cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Lembaga Pendidikan</th>
                    <th>Jenjang</th>
                    <th>Jurusan</th>
                    <th>Nomor Ijazah</th>
                    <th>Tahun</th>
                    <th>Aksi Lain</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($pegawai->DRPFs as $drpf) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drpf_id[]", $drpf->id); ?></td>
                        <td><input type="text" name="lembaga[]" class="medium" value="<?php echo $drpf->lembaga; ?>" /></td>
                        <td><?php echo form_dropdown("pendidikan_id[]", $opt_pendidikan, $drpf->pendidikan_id); ?></td>
                        <td><input type="text" name="jurusan[]" class="medium" value="<?php echo $drpf->jurusan; ?>" /></td>
                        <td><input type="text" name="nomor_ijazah[]" class="medium" value="<?php echo $drpf->nomor_ijazah; ?>" /></td>
                        <td><input type="text" name="tanggal_ijazah[]" class="small" value="<?php echo date("Y", strtotime($drpf->tanggal_ijazah)); ?>" /></td>
                        <td><a href="<?php echo site_url("pegawai/delete_process/$drpf->id/pendidikan_formal" . uri_string()); ?>"><img src="<?php echo site_url("img/b_drop.png"); ?>" alt="Hapus" title="Hapus" /></a></td>
                    </tr>
                    <?php
                    $i++;
                }

                if ($i == 1) {
                    ?>
                    <tr>
                        <td><?php echo $i . form_hidden("drpf_id[]", 0); ?></td>
                        <td><input type="text" name="lembaga[]" class="medium" value="" /></td>
                        <td><?php echo form_dropdown("pendidikan_id[]", $opt_pendidikan, 0); ?></td>
                        <td><input type="text" name="jurusan[]" class="medium" value="" /></td>
                        <td><input type="text" name="nomor_ijazah[]" class="medium" value="" /></td>
                        <td><input type="text" name="tanggal_ijazah[]" class="small" value="" /></td>
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
                        <a href="javascript:addPendidikanFormal()">Tambah Data Pendidikan Formal</a>
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
    var opt_pendidikan = $("tbody select:first").html();
    var addPendidikanFormal = function() {
        $("table.data").dataTable().fnAddData( [
            next_i + "<input type=\"hidden\" name=\"drpf_id[]\" value=\"0\" />",
            "<input type=\"text\" name=\"lembaga[]\" class=\"medium\" value=\"\" />",
            "<select id=\"select_" + next_i + "\" name=\"pendidikan_id[]\">" + opt_pendidikan + "</select>",
            "<input type=\"text\" name=\"jurusan[]\" class=\"medium\" value=\"\" />",
            "<input type=\"text\" name=\"nomor_ijazah[]\" class=\"medium\" value=\"\" />",
            "<input type=\"text\" name=\"tanggal_ijazah[]\" class=\"small\" value=\"\" />",
            "" ] );
        
        $("table.data tbody tr:last select").addClass("def").val(0);
	
        $("table.data tbody tr:last input.date").datepicker({
            dateFormat: "yy-mm-dd",
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
            dateFormat: "yy-mm-dd",
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
