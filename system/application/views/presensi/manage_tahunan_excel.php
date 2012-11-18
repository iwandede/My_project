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
<?php if ($uid)
    $pegawai = Doctrine::getTable("DPegawai")->find($uid); ?>
<ss:Worksheet ss:Name="Absensi Tahunan <?php echo date_id("Y", $date) . ($uid == 0 ? " (Semua Pegawai)" : " ($pegawai->nama)"); ?>">
    <ss:Table>
        <ss:Column ss:Index="1" ss:AutoFitWidth="1" ss:Width="85" />
        <?php
        $index = 2;
        foreach ($status as $s) {
            echo "
        <ss:Column ss:Index=\"$index\" ss:AutoFitWidth=\"1\" ss:Width=\"85\" />";
            $index++;
        }
        ?>
        <ss:Column ss:Index="<?php echo $index; ?>" ss:AutoFitWidth="1" ss:Width="85" />
        <ss:Column ss:Index="<?php echo $index + 1; ?>" ss:AutoFitWidth="1" />
        <ss:Row>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Tanggal</ss:Data></ss:Cell>
            <?php
            foreach ($status as $s)
                echo "
            <ss:Cell ss:StyleID=\"head\"><ss:Data ss:Type=\"String\">" . ucwords($s) . "</ss:Data></ss:Cell>
";
            ?>
            <ss:Cell ss:StyleID="head"><ss:Data ss:Type="String">Libur</ss:Data></ss:Cell>
            <ss:Cell><ss:Data ss:Type="String"> </ss:Data></ss:Cell>
        </ss:Row>
        <?php
        $format = date("Y", $date);
        $l_format = date("Y/", $date);
        $a_format = date("Y-", $date);

        $n_status = sizeof($status);
        $n_pegawais = sizeof($pegawais);
        for ($i = 1; $i <= 12; $i++) {
            echo "
        <ss:Row>
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"String\">" . month_id($i) . " $format</ss:Data></ss:Cell>";
            for ($j = 0; $j <= $n_status; $j++)
                echo "
            <ss:Cell ss:StyleID=\"body\"><ss:Data ss:Type=\"Number\">" . $tppm[$a_format . padding_date($i) . "-01"][$j] . "</ss:Data></ss:Cell>";
            echo "
        </ss:Row>
";
        }
        ?>
    </ss:Table>
</ss:Worksheet>