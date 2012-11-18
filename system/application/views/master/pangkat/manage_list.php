<div id="main_content">
    <ul>
        <li><a href="#tabs-1">Daftar Pangkat</a></li>
        <li><a href="<?php echo site_url("master/pangkat/add"); ?>">Tambah Pangkat</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <table id="list_user" cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pangkat</th>
                    <th>Golongan/Ruang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($pangkats as $pangkat) {
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $pangkat->nama_pangkat; ?></td>
                        <td><?php echo $pangkat->golongan_ruang; ?></td>
                        <td>
                            <?php echo anchor("master/pangkat/edit/$pangkat->id", "<img src='" . site_url("img/b_edit.png") . "' alt='Ubah' title='Ubah' />"); ?>
                            <?php echo anchor("master/pangkat/delete_process/$pangkat->id", "<img src='" . site_url("img/b_drop.png") . "' alt='Hapus' title='Hapus' />"); ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- script section -->
<script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript">
    $(function() {
        $("#main_content").tabs({
            selected: 0,
            select: function(event, ui) {
                var url = $.data(ui.tab, "load.tabs");
                if(url) {
                    location.href = url;
                    return false;
                }
                return true;
            }
        });
        
        $("#list_user").dataTable({
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
    #list_user th {
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
        padding: 15px 0;
    }
</style>
