<ss:Styles>
    <ss:Style ss:ID="title">
        <ss:Alignment ss:Horizontal="Center" />
        <ss:Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Color="#000000" ss:Bold="1" />
    </ss:Style>
    <ss:Style ss:ID="head">
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
        <ss:Alignment ss:Vertical="Top" />
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
<ss:Worksheet ss:Name="Daftar Urut Kepangkatan">
    <ss:Table>
        <ss:Column ss:Index="1" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="2" ss:AutoFitWidth="1" ss:Width="130" />
        <ss:Column ss:Index="3" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="4" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="5" ss:AutoFitWidth="1" ss:Width="40" />
        
        <ss:Column ss:Index="6" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="7" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="8" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="9" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="10" ss:AutoFitWidth="1" ss:Width="40" />
        
        <ss:Column ss:Index="11" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="12" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="13" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="14" ss:AutoFitWidth="1" ss:Width="130" />
        <ss:Column ss:Index="15" ss:AutoFitWidth="1" ss:Width="130" />
        <ss:Column ss:Index="16" ss:AutoFitWidth="1" />
                <ss:Row>
            <ss:Cell ss:MergeAcross="14" ss:StyleID="title">
                <ss:Data ss:Type="String">
                    <?php echo $judul_laporan;?>
                </ss:Data>
            </ss:Cell>
        </ss:Row>

        <ss:Row>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">No</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Nama Pegawai</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Kesetiaan</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Prestasi Kerja</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Tanggung Jawab</ss:Data></ss:Cell>

            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Ketaatan</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Kejujuran</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Kerja Sama</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Prakarsa</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Kepemimpinan</ss:Data></ss:Cell>

            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Jumlah</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Rata-Rata</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Sebutan</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Pejabat Penilai</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Atasan Pejabat Penilai</ss:Data></ss:Cell>
            <ss:Cell><ss:Data ss:Type="String"> </ss:Data></ss:Cell>
        </ss:Row>
        <?php
        $iterator = 0;
        foreach ($pegawais as $pegawai) {
            $iterator++;
            $last_dp3 = $pegawai->lastRiwayatDP3();
            $jumlah = 0;
            $pp = "";
            $app = "";
            if ($last_dp3) {
                $jumlah += $last_dp3->kesetiaan;
                $jumlah += $last_dp3->prestasi;
                $jumlah += $last_dp3->tanggung_jawab;
                $jumlah += $last_dp3->ketaatan;
                $jumlah += $last_dp3->kejujuran;
                $jumlah += $last_dp3->kerja_sama;
                $jumlah += $last_dp3->prakarsa;
                $jumlah += $last_dp3->kepemimpinan;
                $pp = Doctrine::getTable("DPegawai")->find($last_dp3->penilai_pegawai_id)->nama;
                $app = Doctrine::getTable("DPegawai")->find($last_dp3->atasan_penilai_pegawai_id)->nama;
            } else
                $last_dp3 = new DRiwayatDP3();
            $rata = $jumlah / 8;
            $sebutan = "";
            echo "
        <ss:Row>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"Number\">$iterator</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$pegawai->nama</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->kesetiaan) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->prestasi) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->tanggung_jawab) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->ketaatan) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->kejujuran) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->kerja_sama) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->prakarsa) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($last_dp3->kepemimpinan) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($jumlah) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"bodycentered\"><ss:Data ss:Type=\"String\">". number_format($rata) . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$sebutan </ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$pp </ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$app </ss:Data></ss:Cell>
            <ss:Cell><ss:Data ss:Type=\"String\"> </ss:Data></ss:Cell>
        </ss:Row>
";
        }
        ?>
    </ss:Table>
</ss:Worksheet>