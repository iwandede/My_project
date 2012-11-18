<?php
$temp = DTemp::findByUserAndModel($pegawai->user_id, "DPegawai");
if ($temp->count() == 0)
    unset($temp);
else {
    $temp = $temp->getFirst();
    $diff = unserialize($temp->diff);
}

if (!empty($diff["kenaikan_pangkat_berkala"])) {
    $diff["kenaikan_pangkat_berkala_tahun"] = floor($diff["kenaikan_pangkat_berkala"] / 12);
    $diff["kenaikan_pangkat_berkala_bulan"] = $diff["kenaikan_pangkat_berkala"] % 12;
}

$field = array(
    array("name" => "nip", "label" => "NIP", "value" => $pegawai->nip, "class" => "medium", "suffix" => ""),
    array("name" => "nomor_kartu_pegawai", "label" => "Nomor Kartu Pegawai", "value" => $pegawai->nomor_kartu_pegawai, "class" => "medium", "suffix" => ""),
    array("name" => "tempat_lahir", "label" => "Tempat Lahir", "value" => $pegawai->tempat_lahir, "class" => "medium", "suffix" => ""),
    array("name" => "tanggal_lahir", "label" => "Tanggal Lahir", "value" => enformat_date($pegawai->tanggal_lahir), "class" => "date", "suffix" => ""),
    array("name" => "kelompok_pegawai", "label" => "Kelompok Pegawai", "value" => $pegawai->kelompok_pegawai_1, "class" => "small", "suffix" => ""),
    array("name" => "status_kerja", "label" => "Status Kerja", "value" => $pegawai->status_kerja, "class" => "small", "suffix" => ""),
    array("name" => "tinggi", "label" => "Tinggi Badan", "value" => $pegawai->tinggi, "class" => "very_small", "suffix" => "cm"),
    array("name" => "berat", "label" => "Berat Badan", "value" => $pegawai->berat, "class" => "very_small", "suffix" => "kg"),
    array("name" => "telepon_rumah", "label" => "Telepon Rumah", "value" => $pegawai->telepon_rumah, "class" => "medium", "suffix" => ""),
    array("name" => "telepon_genggam", "label" => "Telepon Genggam", "value" => $pegawai->telepon_genggam, "class" => "medium", "suffix" => ""),
    array("name" => "alamat_email", "label" => "Alamat Email", "value" => $pegawai->alamat_email, "class" => "medium", "suffix" => ""),
    array("name" => "kenaikan_pangkat_berkala_tahun", "label" => "Kenaikan Pangkat Berkala", "value" => floor($pegawai->kenaikan_pangkat_berkala / 12), "class" => "very_small", "suffix" => "tahun"),
    array("name" => "kenaikan_pangkat_berkala_bulan", "label" => "", "value" => $pegawai->kenaikan_pangkat_berkala % 12, "class" => "very_small", "suffix" => "bulan"),
    array("name" => "pasangan", "label" => "Nama istri / suami", "value" => $pegawai->pasangan, "class" => "large", "suffix" => ""),
    array("name" => "nomor_kartu_pasangan", "label" => "Nomor Karis / Karsu (jika istri / suami sebagai PNS)", "value" => $pegawai->nomor_kartu_pasangan, "class" => "medium", "suffix" => ""),
    array("name" => "nomor_askes", "label" => "Nomor ASKES", "value" => $pegawai->nomor_askes, "class" => "medium", "suffix" => ""),
    array("name" => "nomor_npwp", "label" => "NPWP", "value" => $pegawai->nomor_npwp, "class" => "medium", "suffix" => ""),
    array("name" => "nomor_induk_kependudukan", "label" => "Nomor Induk Kependudukan", "value" => $pegawai->nomor_induk_kependudukan, "class" => "medium", "suffix" => ""),
    array("name" => "tanggal_sumpah_pns", "label" => "Tanggal sumpah PNS", "value" => enformat_date($pegawai->tanggal_sumpah_pns), "class" => "date", "suffix" => "")
);
?>
<div id="main_content">
    <ul>
        <li class="<?php if ($hide_tab)
    echo "hidden"; ?>"><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li class="<?php if ($hide_tab)
                echo "hidden"; ?>"><a href="<?php echo site_url("pegawai/add"); ?>">Tambah Pegawai</a></li>
        <li><a href="<?php echo site_url("pegawai/detail/" . $pegawai->id); ?>">Data <?php echo $pegawai->nama; ?></a></li>
        <li><a href="#tabs-1">Ubah Data Umum <?php echo $pegawai->nama; ?></a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/edit_process"); ?>
        <?php echo form_hidden("modul", "umum"); ?>
        <?php echo form_hidden("id", $pegawai->id); ?>
        <table class="form">
            <tbody id="pegawai" class="fieldset">
                <tr>
                    <td style="width: 315px;"><label for="nama">Nama pegawai</label></td>
                    <td>
                        <input type="text" id="nama" name="nama" class="large" value="<?php echo $pegawai->nama; ?>" />
                        <?php if (!empty($diff["nama"]))
                            echo "<span class=\"diff_field\">" . $diff["nama"] . "</span>"; ?>
                    </td>
                </tr>
                <?php for ($i = 0; $i < 1; $i++) {
                    ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td>
                            <input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo $field[$i]["value"]; ?>" /> <?php echo $field[$i]["suffix"]; ?>
                            <?php if (!empty($diff[$field[$i]["name"]]))
                                echo "<span class=\"diff_field\">" . $diff[$field[$i]["name"]] . "</span>"; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="nip_lama">NIP lama</label></td>
                    <td>
                        <input type="text" id="nip_lama" name="nip_lama" class="medium" value="<?php echo $pegawai->nip_lama; ?>" /> <?php echo $field[$i]["suffix"]; ?>
                        <?php if (!empty($diff["nip_lama"]))
                            echo "<span class=\"diff_field\">" . $diff["nip_lama"] . "</span>"; ?>
                    </td>
                </tr>
                <?php for (; $i < 2; $i++) {
                    ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td>
                            <input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo $field[$i]["value"]; ?>" /> <?php echo $field[$i]["suffix"]; ?>
                            <?php if (!empty($diff[$field[$i]["name"]]))
                                echo "<span class=\"diff_field\">" . $diff[$field[$i]["name"]] . "</span>"; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="instansi_id">Instansi</label></td>
                    <td>
                        <?php echo form_dropdown("instansi_id", $opt_instansi, $pegawai->instansi_id); ?>
                        <?php if (!empty($diff["intansi_id"]))
                            echo "<span class=\"diff_field\">" . $opt_instansi[$diff["instansi_id"]] . "</span>"; ?>
                    </td>
                </tr>
                <?php for (; $i < 4; $i++) {
                    ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td>
                            <input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo $field[$i]["value"]; ?>" /> <?php echo $field[$i]["suffix"]; ?>
                            <?php if (!empty($diff[$field[$i]["name"]]))
                                echo "<span class=\"diff_field\">" . $diff[$field[$i]["name"]] . "</span>"; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="agama_id">Agama</label></td>
                    <td>
                        <?php echo form_dropdown("agama_id", $opt_agama, $pegawai->agama_id); ?>
                        <?php if (!empty($diff["agama_id"]))
                            echo "<span class=\"diff_field\">" . $opt_agama[$diff["agama_id"]] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="eselon">Eselon</label></td>
                    <td>
                        <input type="text" id="eselon" name="eselon" class="small" value="<?php echo $pegawai->eselon; ?>" />
                        <?php if (!empty($diff["eselon"]))
                            echo "<span class=\"diff_field\">" . $diff["eselon"] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_1">Unit kerja eselon I</label></td>
                    <td>
                        <?php echo form_dropdown("kelompok_pegawai_1", $opt_kelompok_pegawai, $pegawai->kelompok_pegawai_1); ?>
                        <?php if (!empty($diff["kelompok_pegawai_1"]))
                            echo "<span class=\"diff_field\">" . $opt_kelompok_pegawai[$diff["kelompok_pegawai_1"]] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_2">Unit kerja eselon II</label></td>
                    <td>
                        <?php echo form_dropdown("kelompok_pegawai_2", $opt_kelompok_pegawai, $pegawai->kelompok_pegawai_2); ?>
                        <?php if (!empty($diff["kelompok_pegawai_2"]))
                            echo "<span class=\"diff_field\">" . $opt_kelompok_pegawai[$diff["kelompok_pegawai_2"]] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_3">Unit kerja eselon III</label></td>
                    <td>
                        <?php echo form_dropdown("kelompok_pegawai_3", $opt_kelompok_pegawai, $pegawai->kelompok_pegawai_3); ?>
                        <?php if (!empty($diff["kelompok_pegawai_3"]))
                            echo "<span class=\"diff_field\">" . $opt_kelompok_pegawai[$diff["kelompok_pegawai_3"]] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_4">Unit kerja eselon IV</label></td>
                    <td>
                        <?php echo form_dropdown("kelompok_pegawai_4", $opt_kelompok_pegawai, $pegawai->kelompok_pegawai_4); ?>
                        <?php if (!empty($diff["kelompok_pegawai_4"]))
                            echo "<span class=\"diff_field\">" . $opt_kelompok_pegawai[$diff["kelompok_pegawai_4"]] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="status_kerja">Status kerja</label></td>
                    <td>
                        <?php $opt_status_kerja = array("Aktif", "Pensiun", "Dipekerjakan"); ?>
                        <?php echo form_dropdown("status_kerja", $opt_status_kerja, $pegawai->status_kerja); ?>
                        <?php if (!empty($diff["status_kerja"]))
                            echo "<span class=\"diff_field\">" . $opt_status_kerja[$diff["status_kerja"]] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="tanggal_pensiun">Tanggal pensiun</label></td>
                    <td>
                        <?php echo form_input("tanggal_pensiun", enformat_date($pegawai->tanggal_pensiun), "class=\"date\""); ?>
                        <?php if (!empty($diff["tanggal_pensiun"]))
                            echo "<span class=\"diff_field\">" . enformat_date($diff["tanggal_pensiun"]) . "</span>"; ?>
                    </td>
                </tr>
                <?php for ($i = 6; $i < 8; $i++) {
                    ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td>
                            <input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo $field[$i]["value"]; ?>" /> <?php echo $field[$i]["suffix"]; ?>
                            <?php if (!empty($diff[$field[$i]["name"]]))
                                echo "<span class=\"diff_field\">" . $diff[$field[$i]["name"]] . "</span>"; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="golongan_darah">Golongan darah</label></td>
                    <td>
                        <?php echo form_dropdown("golongan_darah", array("A" => "A", "B" => "B", "O" => "O", "AB" => "AB"), $pegawai->jenis_kelamin); ?>
                        <?php if (!empty($diff["golongan_darah"]))
                            echo "<span class=\"diff_field\">" . $diff["golongan_darah"] . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="jenis_kelamin">Jenis kelamin</label></td>
                    <td>
                        <?php echo form_dropdown("jenis_kelamin", array("Laki-laki", "Perempuan"), $pegawai->jenis_kelamin); ?>
                        <?php if (!empty($diff["jenis_kelamin"]))
                            echo "<span class=\"diff_field\">" . ($diff["jenis_kelamin"] ? "Perempuan" : "Laki-laki") . "</span>"; ?>
                    </td>
                </tr>
                <tr>
                    <td class="top_align"><label for="alamat">Alamat</label></td>
                    <td>
                        <textarea id="alamat" name="alamat" rows="3" cols="60"><?php echo $pegawai->alamat; ?></textarea>
                        <?php if (!empty($diff["alamat"]))
                            echo "<span class=\"diff_field\" style=\"display: block; margin-left: 0;\">" . $diff["alamat"] . "</span>"; ?>
                    </td>
                </tr>
                <?php for (; $i < 13; $i++) {
                    ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td>
                            <input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo $field[$i]["value"]; ?>" /> <?php echo $field[$i]["suffix"]; ?>
                            <?php if (!empty($diff[$field[$i]["name"]]))
                                echo "<span class=\"diff_field\">" . $diff[$field[$i]["name"]] . "</span>"; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="status_pernikahan_id">Status Pernikahan</label></td>
                    <td>
                        <?php echo form_dropdown("status_pernikahan_id", $opt_status_pernikahan, $pegawai->status_pernikahan_id); ?>
                        <?php if (!empty($diff["status_pernikahan_id"]))
                            echo "<span class=\"diff_field\">" . $opt_status_pernikahan[$diff["status_pernikahan_id"]] . "</span>"; ?>
                    </td>
                </tr>
                <?php for (; $i < 19; $i++) {
                    ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td>
                            <input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo $field[$i]["value"]; ?>" /> <?php echo $field[$i]["suffix"]; ?>
                            <?php if (!empty($diff[$field[$i]["name"]]))
                                echo "<span class=\"diff_field\">" . $diff[$field[$i]["name"]] . "</span>"; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tbody class="fieldset">
                <tr class="button">
                    <td>&nbsp;</td>
                    <td>
                        <?php if (!$hide_tab && !empty($temp->id))
                            echo anchor("temp/accept/$temp->id", "Setujui Perubahan") . " " . anchor("temp/reject/$temp->id", "Tolak Perubahan"); ?>
                        <input id="submit" type="submit" value="Simpan" />
                    </td>
                </tr>
            </tbody>
        </table>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- script section -->
<script type="text/javascript">
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
