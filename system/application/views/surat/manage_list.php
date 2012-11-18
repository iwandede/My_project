<?php $modul_link = $modul == "all" ? "umum" : $modul; ?>
<div id="main_content">
    <ul>
        <li><a href="#tabs-1">Daftar Berkas</a></li>
        <li><a href="<?php echo site_url("surat/add/$owner_id/$ref_id/$modul_link"); ?>">Tambah Berkas</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <h3><?php echo $title; ?></h3>
        <table id="list_surat" cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Field</th>
                    <th>Pemilik Berkas</th>
                    <th>Uploader</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($surats as $surat) {
                    $keterangan = unserialize($surat->keterangan);
                    $tip = "";
                    $field = "";
                    foreach ($keterangan as $key => $value)
                        if ($key != "sk_id" && $key != "link" && $key != "is_internal_link" && $key != "judul" && $key != "keterangan") {
                            $key = ucfirst($key);
                            $tip .= "<b>$key</b>: $value<br />";
                            $field .= "$key: $value\n";
                        } else if ($key == "sk_id") {
                            $sk = Doctrine::getTable("MSuratKeputusan")->find($value);
                            $key = "Jenis SK";
                            $tip .= "<b>$key</b>: $sk->jenis_sk<br />";
                            $field .= "$key: $value\n";
                        }
                    $value = $keterangan["keterangan"];
                    $tip .= "<b>Keterangan</b>: $value<br />";
                    $field .= "Keterangan: $value\n";
                    if ($modul == "all" || $modul == $keterangan["modul"]) {
                ?>
                        <tr>
                            <td title="<?php echo $tip; ?>"><?php echo $i; ?></td>
                            <td title="<?php echo $tip; ?>"><?php echo $keterangan["judul"]; ?></td>
                            <td><?php echo $field; ?></td>
                            <td title="<?php echo $tip; ?>"><?php echo empty($surat->Owner->DPegawai->nama) ? $surat->Owner->username : $surat->Owner->DPegawai->nama; ?></td>
                            <td title="<?php echo $tip; ?>"><?php echo empty($surat->Uploader->DPegawai->nama) ? $surat->Uploader->username : $surat->Uploader->DPegawai->nama; ?></td>
                            <td>
                        <?php echo anchor("surat/download/$surat->id", "<img src='" . site_url("img/b_save.png") . "' alt='Download' title='Download' />"); ?>
                        <?php echo anchor("surat/delete_process/$surat->id" . uri_string(), "<img src='" . site_url("img/b_drop.png") . "' alt='Hapus' title='Hapus' />"); ?>
                    </td>
                </tr>
                <?php
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- script section -->
<script type="text/javascript" src="<?php echo site_url("js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("js/jquery.dropshadow.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("js/jquery.timers.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("js/mbTooltip.js"); ?>"></script>
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
        
        $("#list_surat").dataTable({
            "sPaginationType": "full_numbers",
            "bSort": true,
            "aoColumnDefs": [ 
                { "bVisible": false, "aTargets": [ 2 ] }
            ],
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

        $("[title]").mbTooltip({    // also $([domElement]).mbTooltip  >>  in this case only children element are involved
            opacity : .97,          //opacity
            wait: 300,             //before show
            cssClass: "default",    // default = default
            timePerWord: 70,        //time to show in milliseconds per word
            hasArrow: false,        // if you whant a little arrow on the corner
            hasShadow: true,
            imgPath: "img/",
            ancor: "mouse",         //"parent"  you can ancor the tooltip to the mouse position or at the bottom of the element
            shadowColor: "black",   //the color of the shadow
            mb_fade: 200            //the time to fade-in
        });
    });
</script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo site_url("css/mbTooltip.css"); ?>" type="text/css" />
<style type="text/css">
    #list_surat th {
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
        padding: 15px 0;
    }
</style>
