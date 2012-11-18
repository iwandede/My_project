<div id="main_content">
    <ul>
        <li><a href="#tabs-1">Daftar Berita</a></li>
         <?php if ($current_user->role == 1) { ?>
        <li><a href="<?php echo site_url("berita/add"); ?>">Tambah Berita</a></li>
        <?php } ?>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <table id="list_berita" cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Isi Berita</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($beritas as $berita) {
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $berita->judul; ?></td>
                        <td class="expander"><?php echo $berita->isi; ?></td>
                        <td><?php echo $berita->tgl_dibuat ?></td>
                        <td>
                        <?php if ($current_user->role == 1) { 
                            echo anchor("berita/edit/$berita->id", "<img src='" . site_url("img/b_edit.png") . "' alt='Ubah' title='Ubah' />");
                            echo anchor("berita/delete_process/$berita->id", "<img src='" . site_url("img/b_drop.png") . "' alt='Hapus' title='Hapus' />");
                            }
                            echo anchor('berita/detail/' . $berita->id , "<img src='" . site_url("img/b_browse.png") . "' alt='Detail' title='Detail' />");
                            ?>
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
    <script type="text/javascript" src="<?php echo site_url("js/jquery.expander.min.js"); ?>"></script>
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

            $("#list_berita").dataTable({
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
            $('td.expander').expander({
                slicePoint:       200,  // default is 100    
                expandPrefix:     ' ', // default is '... '    
                expandText:       '[tampilkan]', // default is 'read more'
                userCollapseText: '[sembunyikan]'  // default is 'read less'
            });

        });
    </script>

    <!-- style section -->
    <link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    #list_surat th {
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
        padding: 15px 0;
    }
</style>
