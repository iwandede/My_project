<?php
$field = array(
    array("name" => "nip", "label" => "NIP", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "nomor_kartu_pegawai", "label" => "Nomor Kartu Pegawai", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "tempat_lahir", "label" => "Tempat Lahir", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "tanggal_lahir", "label" => "Tanggal Lahir", "value" => "", "class" => "date", "suffix" => ""),
    array("name" => "kelompok_pegawai", "label" => "Kelompok Pegawai", "value" => "", "class" => "small", "suffix" => ""),
    array("name" => "status_kerja", "label" => "Status Kerja", "value" => "", "class" => "small", "suffix" => ""),
    array("name" => "tinggi", "label" => "Tinggi Badan", "value" => "", "class" => "very_small", "suffix" => "cm"),
    array("name" => "berat", "label" => "Berat Badan", "value" => "", "class" => "very_small", "suffix" => "kg"),
    array("name" => "telepon_rumah", "label" => "Telepon Rumah", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "telepon_genggam", "label" => "Telepon Genggam", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "alamat_email", "label" => "Alamat Email", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "kenaikan_pangkat_berkala_tahun", "label" => "Kenaikan Pangkat Berkala", "value" => 0, "class" => "very_small", "suffix" => "tahun"),
    array("name" => "kenaikan_pangkat_berkala_bulan", "label" => "", "value" => 0, "class" => "very_small", "suffix" => "bulan"),
    array("name" => "pasangan", "label" => "Nama istri / suami", "value" => "", "class" => "large", "suffix" => ""),
    array("name" => "nomor_kartu_pasangan", "label" => "Nomor Karis / Karsu (jika istri / suami sebagai PNS)", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "nomor_askes", "label" => "Nomor ASKES", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "nomor_npwp", "label" => "NPWP", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "nomor_induk_kependudukan", "label" => "Nomor Induk Kependudukan", "value" => "", "class" => "medium", "suffix" => ""),
    array("name" => "tanggal_sumpah_pns", "label" => "Tanggal sumpah PNS", "value" => "", "class" => "date", "suffix" => "")
);
$select = array();
$select["instansi_id"] = $this->input->post("instansi_id") ? $this->input->post("instansi_id") : 0;
$select["agama_id"] = $this->input->post("agama_id") ? $this->input->post("agama_id") : 0;
$select["kelompok_pegawai_1"] = $this->input->post("kelompok_pegawai_1") ? $this->input->post("kelompok_pegawai_1") : 0;
$select["kelompok_pegawai_2"] = $this->input->post("kelompok_pegawai_2") ? $this->input->post("kelompok_pegawai_2") : 0;
$select["kelompok_pegawai_3"] = $this->input->post("kelompok_pegawai_3") ? $this->input->post("kelompok_pegawai_3") : 0;
$select["kelompok_pegawai_4"] = $this->input->post("kelompok_pegawai_4") ? $this->input->post("kelompok_pegawai_4") : 0;
$select["status_kerja"] = $this->input->post("status_kerja") ? $this->input->post("status_kerja") : 0;
$select["golongan_darah"] = $this->input->post("golongan_darah") ? $this->input->post("golongan_darah") : "A";
$select["jenis_kelamin"] = $this->input->post("jenis_kelamin") ? $this->input->post("jenis_kelamin") : 0;
$select["status_pernikahan_id"] = $this->input->post("status_pernikahan_id") ? $this->input->post("status_pernikahan_id") : 0;
?>
<div id="main_content">
    <ul>
        <li><a href="<?php echo site_url("pegawai/manage"); ?>">Daftar Pegawai</a></li>
        <li><a href="#tabs-1">Tambah Pegawai</a></li>
    </ul>
    <div id="tabs-1">
        <div class="info ui-corner-all <?php echo $msg["type"]; ?>">
            <?php echo $msg["content"]; ?>
        </div>
        <?php echo form_open("pegawai/add_process"); ?>
            <table class="form">
                <tbody id="pegawai" class="fieldset">
                    <tr>
                        <td style="width: 315px;"><label for="nama">Nama pegawai</label></td>
                        <td><input type="text" id="nama" name="nama" class="large" value="<?php echo set_value("nama"); ?>" /></td>
                    </tr>
                <?php for ($i = 0; $i < 1; $i++) {
                ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td><input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo set_value($field[$i]["name"], $field[$i]["value"]); ?>" /> <?php echo $field[$i]["suffix"]; ?></td>
                    </tr>
                    <tr>
                        <td><label for="nip_lama">NIP lama</label></td>
                        <td><input type="text" id="nip_lama" name="nip_lama" class="medium" value="<?php echo set_value("nip_lama", ""); ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="password">Password</label></td>
                        <td><input type="password" id="password" name="password" class="medium" value="" /></td>
                    </tr>
                    <tr>
                        <td><label for="passconf">Ketik ulang password</label></td>
                        <td><input type="password" id="passconf" name="passconf" class="medium" value="" /></td>
                    </tr>
                <?php } ?>
                <?php for (; $i < 2; $i++) {
                ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td><input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo set_value($field[$i]["name"], $field[$i]["value"]); ?>" /> <?php echo $field[$i]["suffix"]; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="instansi_id">Instansi</label></td>
                    <td><?php echo form_dropdown("instansi_id", $opt_instansi, $select["instansi_id"]); ?></td>
                </tr>
                <?php for (; $i < 4; $i++) {
                ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td><input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo set_value($field[$i]["name"], $field[$i]["value"]); ?>" /> <?php echo $field[$i]["suffix"]; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="agama_id">Agama</label></td>
                    <td><?php echo form_dropdown("agama_id", $opt_agama, $select["agama_id"]); ?></td>
                </tr>
                <tr>
                    <td><label for="eselon">Eselon</label></td>
                    <td><input type="text" id="eselon" name="eselon" class="small" value="" /></td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_1">Unit kerja eselon I</label></td>
                    <td><?php echo form_dropdown("kelompok_pegawai_1", $opt_kelompok_pegawai, $select["kelompok_pegawai_1"]); ?></td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_2">Unit kerja eselon II</label></td>
                    <td><?php echo form_dropdown("kelompok_pegawai_2", $opt_kelompok_pegawai, $select["kelompok_pegawai_2"]); ?></td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_3">Unit kerja eselon III</label></td>
                    <td><?php echo form_dropdown("kelompok_pegawai_3", $opt_kelompok_pegawai, $select["kelompok_pegawai_3"]); ?></td>
                </tr>
                <tr>
                    <td><label for="kelompok_pegawai_4">Unit kerja eselon IV</label></td>
                    <td><?php echo form_dropdown("kelompok_pegawai_4", $opt_kelompok_pegawai, $select["kelompok_pegawai_4"]); ?></td>
                </tr>
                <tr>
                    <td><label for="status_kerja">Status kerja</label></td>
                    <td><?php echo form_dropdown("status_kerja", array("Aktif", "Pensiun", "Dipekerjakan"), $select["status_kerja"]); ?></td>
                </tr>
                <tr>
                    <td><label for="tanggal_pensiun">Tanggal pensiun</label></td>
                    <td>
                        <?php echo form_input("tanggal_pensiun", "", "class=\"date\""); ?>
                    </td>
                </tr>
                <?php for ($i = 6; $i < 8; $i++) {
                ?>
                    <tr>
                        <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                        <td><input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo set_value($field[$i]["name"], $field[$i]["value"]); ?>" /> <?php echo $field[$i]["suffix"]; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><label for="golongan_darah">Golongan darah</label></td>
                    <td>
                        <?php echo form_dropdown("golongan_darah", array("A" => "A", "B" => "B", "O" => "O", "AB" => "AB"), $select["golongan_darah"]); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="jenis_kelamin">Jenis kelamin</label></td>
                    <td><?php echo form_dropdown("jenis_kelamin", array("Laki-laki", "Perempuan"), $select["jenis_kelamin"]); ?></td>
                </tr>
                <tr>
                    <td class="top_align"><label for="alamat">Alamat</label></td>
                    <td><textarea id="alamat" name="alamat" rows="3" cols="60"><?php echo set_value("alamat"); ?></textarea></td>
                </tr>
                <?php for (; $i < 13; $i++) {
 ?>
                            <tr>
                                <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                                <td><input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo set_value($field[$i]["name"], $field[$i]["value"]); ?>" /> <?php echo $field[$i]["suffix"]; ?></td>
                            </tr>
<?php } ?>
                        <tr>
                            <td><label for="status_pernikahan_id">Status Pernikahan</label></td>
                            <td><?php echo form_dropdown("status_pernikahan_id", $opt_status_pernikahan, $select["status_pernikahan_id"]); ?></td>
                        </tr>
<?php for (; $i < 19; $i++) { ?>
                            <tr>
                                <td><label for="<?php echo $field[$i]["name"]; ?>"><?php echo $field[$i]["label"]; ?></label></td>
                                <td><input type="text" id="<?php echo $field[$i]["name"]; ?>" name="<?php echo $field[$i]["name"]; ?>" class="<?php echo $field[$i]["class"]; ?>" value="<?php echo set_value($field[$i]["name"], $field[$i]["value"]); ?>" /> <?php echo $field[$i]["suffix"]; ?></td>
                            </tr>
<?php } ?>
                    </tbody>
                    <tbody class="fieldset">
                        <tr class="button">
                            <td>&nbsp;</td>
                            <td>
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
            selected: 1,
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
        
        $("input:submit").button();
    });
</script>