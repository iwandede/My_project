<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y/m/d")); ?>">Absensi Harian</a></li>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y/m")); ?>">Absensi Bulanan</a></li>
        <li><a href="<?php echo site_url("presensi/manage/0/" . date("Y")); ?>">Absensi Tahunan</a></li>
        <li><a href="<?php echo site_url("presensi/graph"); ?>">Grafik Absensi</a></li>
        <li><a href="#tabs-1">Akumulasi Absensi</a></li>
        <li><a href="<?php echo site_url("presensi/upload"); ?>">Upload Absensi</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("presensi/akumulasi"); ?>
        <table class="form">
            <tbody id="gaji" class="fieldset">
                <tr>
                    <td style="width: 45px;">Periode</td>
                    <td style="width: 85px;"><input type="text" name="mulai" class="date" value="<?php echo $mulai; ?>" /></td>
                    <td style="width: 10px;">-</td>
                    <td style="width: 85px;"><input type="text" name="akhir" class="date" value="<?php echo $akhir; ?>" /></td>
                    <td><input id="submit" type="submit" value="Hitung" /></td>
                </tr>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <table id="list_akumulasi" cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Akumulasi Absensi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($pegawais as $pegawai) {
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $pegawai->nip; ?></td>
                        <td><?php echo $pegawai->nama; ?></td>
                        <td><?php echo $acc[$pegawai->id]; ?></td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript">
    $(function() {
        $("#main_content").tabs({
            selected: 4,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });
        
        $("input.date").datepicker({
            dateFormat: "dd-mm-yy",
            dayNamesMin: ["Mg", "Sn", "Se", "Ra", "Ka", "Ju", "Sa"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            changeMonth: true,
            changeYear: true
        });
        
        $("input:submit").button();
        
        $("#list_akumulasi").dataTable({
            "sPaginationType": "full_numbers",
            "bSort": true,
            "oLanguage": {
                "sSearch": "Cari:",
                "sLengthMenu": "Menampilkan _MENU_ data per halaman",
                "sZeroRecords": "Maaf, data tidak ditemukan",
                "sInfo": "Menampilkan data _START_ sampai _END_, dari _TOTAL_ data",
                "sInfoEmpty": "Menampilkan data 0 sampai 0, dari 0 data",
                "sInfoFiltered": "(disaring dari _MAX_ total data)",
                "oPaginate" : {
                    "sFirst": "Pertama",
                    "sLast": "Terakhir",
                    "sPrevious": "Sebelumnya",
                    "sNext": "Berikutnya"
                }
            }
        });
    });
</script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    #list_akumulasi th {
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
        padding: 15px 0;
    }
</style>
