<ss:Styles>
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
</ss:Styles>
<ss:Worksheet ss:Name="Absensi Harian <?php echo date_id("j F Y", $date); ?>">
    <ss:Table>
        <ss:Column ss:Index="1" ss:AutoFitWidth="1" ss:Width="40" />
        <ss:Column ss:Index="2" ss:AutoFitWidth="1" ss:Width="130" />
        <ss:Column ss:Index="3" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="4" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="5" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="6" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="7" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="8" ss:AutoFitWidth="1" ss:Width="130" />
        <ss:Column ss:Index="9" ss:AutoFitWidth="1" />
        <ss:Row>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">No</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Nama Pegawai</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Status</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Jam Masuk (Handkey)</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Jam Keluar (Handkey)</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Jam Masuk</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Jam Keluar</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Keterangan</ss:Data></ss:Cell>
            <ss:Cell><ss:Data ss:Type="String"> </ss:Data></ss:Cell>
        </ss:Row>
        <?php
        $iterator = 0;
        $opt_pegawai = array();
        $opt_pegawai["0"] = "-- Pegawai --";
        foreach ($pegawais as $pegawai)
            $opt_pegawai["$pegawai->id"] = $pegawai->nama;
        
        foreach ($presensies as $presensi) {
            $iterator++;
            $presensi->masuk_j_h = empty($presensi->masuk_j_h) ? "00" : $presensi->masuk_j_h;
            $presensi->masuk_m_h = empty($presensi->masuk_m_h) ? "00" : $presensi->masuk_m_h;
            $presensi->keluar_j_h = empty($presensi->keluar_j_h) ? "00" : $presensi->keluar_j_h;
            $presensi->keluar_m_h = empty($presensi->keluar_m_h) ? "00" : $presensi->keluar_m_h;
            echo "
        <ss:Row>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"Number\">$iterator</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">" . $opt_pegawai[$presensi->pegawai_id] . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">" . $status[$presensi->status] . "</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$presensi->masuk_j_h:$presensi->masuk_m_h</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$presensi->keluar_j_h:$presensi->keluar_m_h</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$presensi->masuk_j:$presensi->masuk_m</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$presensi->keluar_j:$presensi->keluar_m</ss:Data></ss:Cell>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">$presensi->keterangan</ss:Data></ss:Cell>
            <ss:Cell><ss:Data ss:Type=\"String\"> </ss:Data></ss:Cell>
        </ss:Row>
";
        }
        ?>
    </ss:Table>
</ss:Worksheet>