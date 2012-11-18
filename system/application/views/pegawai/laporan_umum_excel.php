<ss:Styles>
    <ss:Style ss:ID="title">
        <ss:Alignment ss:Horizontal="Center" />
        <ss:Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#000000" ss:Bold="1" />
    </ss:Style>
    <ss:Style ss:ID="head">
        <ss:Alignment ss:Vertical="Top" ss:WrapText="1" />
        <ss:Borders>
            <ss:Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" />
        </ss:Borders>
        <ss:Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000" ss:Bold="1" />
        <ss:Interior ss:Color="#D4E0A3" ss:Pattern="Solid" />
    </ss:Style>
    <ss:Style ss:ID="body">
        <ss:Alignment ss:Vertical="Top" ss:WrapText="1" />
        <ss:Borders>
            <ss:Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" />
        </ss:Borders>
    </ss:Style>
    <ss:Style ss:ID="bodycentered">
        <ss:Alignment ss:Vertical="Top" ss:Horizontal="Center" ss:WrapText="1" />
        <ss:Borders>
            <ss:Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" />
        </ss:Borders>
    </ss:Style>
</ss:Styles>
<ss:Worksheet ss:Name="Laporan Umum">
    <ss:Table>
        <ss:Column ss:Index="1" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="2" ss:AutoFitWidth="1" ss:Width="140" />
        <ss:Column ss:Index="3" ss:AutoFitWidth="1" ss:Width="65" />
        <ss:Column ss:Index="4" ss:AutoFitWidth="1" ss:Width="65" />
        <ss:Column ss:Index="5" ss:AutoFitWidth="1" ss:Width="120" />
        <ss:Column ss:Index="6" ss:AutoFitWidth="1" ss:Width="45" />
        <ss:Column ss:Index="7" ss:AutoFitWidth="1" ss:Width="105" />
        <ss:Column ss:Index="8" ss:AutoFitWidth="1" ss:Width="120" />
        <ss:Column ss:Index="9" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="10" ss:AutoFitWidth="1" ss:Width="65" />
        <ss:Column ss:Index="11" ss:AutoFitWidth="1" />
        <ss:Row>
            <ss:Cell ss:MergeAcross="9" ss:StyleID="title">
                <ss:Data ss:Type="String"><?php echo $judul_laporan; ?></ss:Data>
            </ss:Cell>
        </ss:Row>
        <ss:Row>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">No</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Nama, NIP, Tempat/Tgl Lahir</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">CPNS</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Gol/Ruang&#10;&#13;TMT</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Jabatan</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Eselon</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Pendidikan Terakhir</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Diklat</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Instansi Induk</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Keterangan</ss:Data></ss:Cell>
            <ss:Cell><ss:Data ss:Type="String"> </ss:Data></ss:Cell>
        </ss:Row>
        <?php
        $iterator = 0;
        $keys = array_keys($pegawais);
        foreach ($keys as $key) {
            $pegawai = $pegawais[$key];
            $iterator++;

            $last_pangkat = $pegawai->lastRiwayatPangkat();

            $last_jabatan = $pegawai->lastRiwayatJabatan();

            $last_pendidikan = $pegawai->lastRiwayatPendidikan();
            if($last_pendidikan) {
                $pendidikan = $last_pendidikan->MPendidikanFormal->nama_pendidikan . "&#10;&#13;";
                $pendidikan .= (empty($last_pendidikan->jurusan) ? "" : $last_pendidikan->jurusan . "&#10;&#13;");
                $pendidikan .= (empty($last_pendidikan->lembaga) ? "" : $last_pendidikan->lembaga . "&#10;&#13;");
                $pendidikan .= substr($last_pendidikan->tanggal_ijazah, 0, 4);
            } else
                $pendidikan = "-";

            $diklat = "";
            foreach ($pegawai->DRDs as $drd)
                $diklat .= $drd->MDiklat->jenis_diklat . " (" . date_id("j F Y", strtotime($drd->tanggal_selesai)) . ")&#10;&#13;";
            if (empty($diklat))
                $diklat = "-";

            echo "
        <ss:Row>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"Number\">$iterator</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">" . $pegawai->nama . "&#10;&#13;" . $pegawai->nip . "&#10;&#13;" . $pegawai->tempat_lahir . "/" . enformat_date($pegawai->tanggal_lahir) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">" . enformat_date($pegawai->tanggal_sumpah_pns) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">" . ($last_pangkat ? $last_pangkat->MPangkat->golongan_ruang . "&#10;&#13;" . enformat_date($last_pangkat->tmt) : "-") . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">" . ($last_jabatan ? $last_jabatan->MJabatan->nama_eselon : "-") . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">$pegawai->eselon</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">" . $pendidikan . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$diklat</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">" . $pegawai->MInstansi->nama_instansi . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">-</ss:Data></ss:Cell>
            <ss:Cell><ss:Data ss:Type=\"String\"> </ss:Data></ss:Cell>
        </ss:Row>
";
        }
        ?>
    </ss:Table>
</ss:Worksheet>