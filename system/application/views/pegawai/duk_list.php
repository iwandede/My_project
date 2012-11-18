<div id="main_content">
    <ul>
        <li><a href="#tabs-1">Daftar Urut Kepangkatan</a></li>
        <li><a href="<?php echo site_url("pegawai/laporanduk/excel/"); ?>">Download</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <table id="list_user" cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th style="min-width: 25%">Nama, NIP, Tempat/Tgl Lahir</th>
                    <th>CPNS</th>
                    <th>Gol/Ruang<br />TMT</th>
                    <th style="min-width: 30%">Jabatan</th>
                    <th>Eselon</th>
                    <th style="min-width: 15%">Pendidikan</th>
                </tr>
            </thead>
            <tbody>
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
            "bSort": false,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo site_url("pegawai/laporanduk_data/0/0/0/0/9"); ?>",
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
        
        $("input:submit").button();
        
        $("select.ignore").each(function() {
            $(this).selectmenu({
                style: 'dropdown',
                width: 500,
                maxHeight: 150
            });
        });
    });
</script>

<!-- style section -->
<link rel="stylesheet" href="<?php echo site_url("css/datatables.css"); ?>" type="text/css" />
<style type="text/css">
    ol {
        padding-left: 20px;
        list-style-type: decimal;
    }

    .dataTables_wrapper {
        min-height: 0;
        padding: 15px 0;
    }
</style>
