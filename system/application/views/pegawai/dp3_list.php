<?php
switch($args["status_kerja"]) {
    case 0:
        $esk = array(true, false, false);
        break;
    case 1:
        $esk = array(false, true, false);
        break;
    case 2:
        $esk = array(false, false, true);
        break;
    case 3:
        $esk = array(true, true, false);
        break;
    case 4:
        $esk = array(true, false, true);
        break;
    case 4:
        $esk = array(false, true, true);
        break;
    case 9:
        $esk = array(true, true, true);
        break;
    default:
        $esk = array(false, false, false);
        break;
}
$opt_status_kerja = array(
    0 => "Aktif",
    1 => "Pensiun",
    2 => "Dipekerjakan",
    3 => "Aktif + Dipekerjakan",
    9 => "Semua"
);
$opt_golongan = MPangkat::options_array();
$opt_golongan[0] = "Semua";
$opt_eselon = DPegawai::eselon_options_array();
$opt_eselon[0] = "Semua";
$unit_kerja = $args["unit_kerja"] ? $args["unit_kerja"] : "";
$opt_unit_kerja = array_values(MSatuanKerja::options_array());
$opt_jenis_kelamin = array(
    0 => "Laki-laki",
    1 => "Perempuan",
    9 => "Semua"
);
?>
<div id="main_content">
    <ul>
        <li><a href="#tabs-1">Laporan</a></li>
        <li><a href="<?php echo site_url("pegawai/laporandp3/excel/" . $judul_laporan . "/" . $args["status_kerja"] . "/" . $args["golongan"] . "/" . $args["eselon"] . "/" . $args["unit_kerja"] . "/" . $args["jenis_kelamin"]); ?>">Download</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/laporandp3"); ?>
        <?php echo form_hidden("is_post", 1); ?>
            <table class="form">
                <tbody id="pegawai" class="fieldset">
                    <tr>
                        <td style="width: 100px;"><label for="status_kerja">Status kerja</label></td>
                        <td>
                            <?php echo form_checkbox("status_kerja[]", "0", $esk[0]); ?> Aktif
                            <?php echo form_checkbox("status_kerja[]", "1", $esk[1]); ?> Pensiun
                            <?php echo form_checkbox("status_kerja[]", "2", $esk[2]); ?> Dipekerjakan
                        </td>
                    </tr>
                    <tr>
                        <td><label for="golongan">Pangkat</label></td>
                        <td><?php echo form_dropdown("golongan", $opt_golongan, $args["golongan"]); ?></td>
                    </tr>
                    <tr>
                        <td><label for="eselon">Eselon</label></td>
                        <td><?php echo form_dropdown("eselon", $opt_eselon, $args["eselon"]); ?></td>
                    </tr>
                    <tr>
                        <td><label for="unit_kerja">Unit kerja</label></td>
                        <td><?php echo form_input("unit_kerja", $unit_kerja, "class=\"large\""); ?></td>
                    </tr>
                    <tr>
                        <td><label for="jenis_kelamin">Jenis Kelamin</label></td>
                        <td><?php echo form_dropdown("jenis_kelamin", $opt_jenis_kelamin, $args["jenis_kelamin"]); ?></td>
                    </tr>
                                        <tr>
                        <td><label for="judul_laporan">Judul Laporan</label></td>
                        <td><?php echo form_input("judul_laporan", $judul_laporan, "class=\"large\""); ?></td>
                    </tr>

                </tbody>
                <tbody class="fieldset">
                    <tr class="button">
                        <td>&nbsp;</td>
                        <td>
                            <input id="submit" type="submit" value="Saring" />
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php echo form_close(); ?>
        <table id="list_user" cellpadding="0" cellspacing="0" border="0" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Jumlah</th>
                    <th>Rata-Rata</th>
                    <th>Pejabat Penilai</th>
                    <th>Atasan Pejabat Penilai</th>
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
            "sAjaxSource": "<?php echo site_url("pegawai/laporandp3_data/" . $args["status_kerja"] . "/" . $args["golongan"] . "/" . $args["eselon"] . "/" . $args["unit_kerja"] . "/" . $args["jenis_kelamin"]); ?>",
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

        $("input[name=unit_kerja]").autocomplete({
			source: <?php echo json_encode($opt_unit_kerja); ?>
		});
        
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
    
    #list_user th {
        cursor: pointer;
    }

    .dataTables_wrapper {
        min-height: 0;
        padding: 15px 0;
    }
</style>
