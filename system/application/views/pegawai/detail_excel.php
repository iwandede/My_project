<ss:Styles>
    <ss:Style ss:ID="title">
        <ss:Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="12" ss:Color="#000000" ss:Bold="1" />
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
    <ss:Style ss:ID="empty">
        <ss:Alignment ss:Vertical="Top" />
        <ss:Borders>
            <ss:Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" />
            <ss:Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" />
        </ss:Borders>
    </ss:Style>
</ss:Styles>
<ss:Worksheet ss:Name="Data Umum">
    <ss:Table>
        <ss:Column ss:Index="1" ss:AutoFitWidth="1" ss:Width="15" />
        <ss:Column ss:Index="2" ss:AutoFitWidth="1" ss:Width="150" />
        <ss:Column ss:Index="3" ss:AutoFitWidth="1" ss:Width="150" />
        <ss:Column ss:Index="4" ss:AutoFitWidth="1" ss:Width="80" />
        <ss:Column ss:Index="5" ss:AutoFitWidth="1" ss:Width="80" />
        <ss:Column ss:Index="6" ss:AutoFitWidth="1" ss:Width="80" />
        <ss:Column ss:Index="7" ss:AutoFitWidth="1" ss:Width="80" />
        <ss:Column ss:Index="8" ss:AutoFitWidth="1" />
        <?php echo write_excel(array(1 => "I. KETERANGAN PERORANGAN"), array(1 => 6), "title"); ?>
        <?php echo write_excel(array(1 => 1, 2 => "NAMA LENGKAP", 3 => $pegawai->nama), array(3 => 4)); ?>
        <?php echo write_excel(array(1 => 2, 2 => "NIP", 3 => $pegawai->nip), array(3 => 4)); ?>
        <?php echo write_excel(array(1 => 3, 2 => "JENIS KELAMIN", 3 => $pegawai->jenis_kelamin ? "PEREMPUAN" : "LAKI-LAKI"), array(3 => 4)); ?>>
        <?php echo write_excel(array(1 => 4, 2 => "TEMPAT LAHIR", 3 => $pegawai->tempat_lahir, 4 => "TANGGAL LAHIR", 5 => date_id("j F Y", strtotime($pegawai->tanggal_lahir))), array(5 => 2)); ?>
        <?php $last_pangkat = $pegawai->lastRiwayatPangkat() ? $pegawai->lastRiwayatPangkat()->MPangkat->nama_pangkat . " (" . $pegawai->lastRiwayatPangkat()->MPangkat->golongan_ruang . ")" : ""; ?>
        <?php $tmt = $pegawai->lastRiwayatPangkat() ? date_id("j F Y", strtotime($pegawai->lastRiwayatPangkat()->tmt)) : ""; ?>
        <?php echo write_excel(array(1 => 5, 2 => "PANGKAT/GOLONGAN RUANG/TMT", 3 => $last_pangkat . "/" . $tmt), array(3 => 4)); ?>
        <?php $last_jabatan = $pegawai->lastRiwayatJabatan() ? $pegawai->lastRiwayatJabatan()->MJabatan->nama_eselon : ""; ?>
        <?php echo write_excel(array(1 => 6, 2 => "JABATAN", 3 => $last_jabatan), array(3 => 4)); ?>
        <?php echo write_excel(array(1 => 7, 2 => "AGAMA", 3 => $pegawai->MAgama->nama_agama), array(3 => 4)); ?>
        <?php echo write_excel(array(1 => 8, 2 => "STATUS PERNIKAHAN", 3 => $pegawai->MStatusPernikahan->status_pernikahan), array(3 => 4)); ?>
        <?php echo write_excel(array(1 => 9, 2 => "NAMA ISTRI/SUAMI", 3 => $pegawai->pasangan), array(3 => 4)); ?>
        
        <ss:Row></ss:Row>
        <?php $drjs = $pegawai->getRiwayatJabatan(array(), "tanggal_sk DESC"); ?>
        <?php echo write_excel(array(1 => "II. RIWAYAT JABATAN"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "JABATAN", 4 => "NOMOR SK", 6 => "TMT"), array(2 => 1, 4 => 1, 6 => 1), "head");
        if ($drjs->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($drjs as $drj) {
                echo write_excel(array(1 => $i, 2 => $drj->MJabatan->nama_eselon, 4 => $drj->no_sk, 6 => $drj->tanggal_sk), array(2 => 1, 4 => 1, 6 => 1));
                $i++;
            }
        }
        ?>
        
        <ss:Row></ss:Row>
        <?php $drps = $pegawai->getRiwayatPendidikan(array(), "tanggal_ijazah DESC"); ?>
        <?php echo write_excel(array(1 => "III. RIWAYAT PENDIDIKAN"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "LEMBAGA PENDIDIKAN", 3 => "JENJANG", 4 => "JURUSAN", 6 => "NOMOR IJAZAH", 7 => "TAHUN LULUS"), array(4 => 1), "head");
        if ($drps->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($drps as $drp) {
                echo write_excel(array(1 => $i, 2 => $drp->lembaga, 3 => $drp->MPendidikanFormal->nama_pendidikan, 4 => $drp->jurusan, 6 => $drp->nomor_ijazah, 7 => date("Y", strtotime($drp->tanggal_ijazah))), array(4 => 1));
                $i++;
            }
        }        
        ?>

        <ss:Row></ss:Row>
        <?php $drps = $pegawai->getRiwayatPendidikanNonFormal(array(), "tanggal_selesai DESC"); ?>
        <?php echo write_excel(array(1 => "IV. RIWAYAT KURSUS"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "JENIS KURSUS", 3 => "TANGGAL", 4 => "JENIS PARTISIPASI", 6 => "KETERANGAN"), array(4 => 1, 6 => 1), "head");
        if ($drps->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($drps as $drp) {
                echo write_excel(array(1 => $i, 2 => $drp->jenis_kursus, 3 => date_id("j F Y", strtotime($drp->tanggal_selesai)), 4 => $drp->jenis_partisipasi, 6 => $drp->keterangan), array(4 => 1, 6 => 1));
                $i++;
            }
        }
        ?>

        <ss:Row></ss:Row>
        <?php $drps = $pegawai->getRiwayatDiklat(array(), "tanggal_selesai DESC"); ?>
        <?php echo write_excel(array(1 => "V. RIWAYAT DIKLAT"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "JENIS DIKLAT", 3 => "TANGGAL", 4 => "KETERANGAN"), array(4 => 3), "head");
        if ($drps->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($drps as $drp) {
                echo write_excel(array(1 => $i, 2 => $drp->MDiklat->jenis_diklat, 3 => date_id("j F Y", strtotime($drp->tanggal_selesai)), 4 => $drp->keterangan), array(4 => 3));
                $i++;
            }
        }
        ?>
        
        <ss:Row></ss:Row>
        <?php $drps = $pegawai->getRiwayatPangkat(array(), "tmt DESC"); ?>
        <?php echo write_excel(array(1 => "VI. RIWAYAT KEPANGKATAN"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "PANGKAT", 3 => "NO SK", 5 => "TANGGAL SK", 7 => "TMT"), array(3 => 1, 5 => 1), "head");
        if ($drps->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($drps as $drp) {
                echo write_excel(array(1 => $i, 2 => $drp->MPangkat->nama_pangkat . " (" . $drp->MPangkat->golongan_ruang . ")", 3 => $drp->no_sk, 5 => date_id("j F Y", strtotime($drp->tanggal_sk)), 7 => date_id("j F Y", strtotime($drp->tmt))), array(3 => 1, 5 => 1));
                $i++;
            }
        }
        ?>
        
        <ss:Row></ss:Row>
        <?php $drks = $pegawai->getRiwayatKunjungan(array(), "tanggal_berangkat DESC"); ?>
        <?php echo write_excel(array(1 => "VII. RIWAYAT KUNJUNGAN LUAR NEGERI"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "JENIS KUNJUNGAN", 3 => "TANGGAL", 4 => "TUJUAN", 5 => "PENYELENGGARA", 6 => "SUMBER DANA", 7 => "KETERANGAN"), null, "head");
        if ($drks->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($drks as $drk) {
                echo write_excel(array(1 => $i, 2 => $drk->jenis_kunjungan, 3 => date_id("j F Y", strtotime($drk->tanggal_berangkat)) . " - " . date_id("j F Y", strtotime($drk->tanggal_kembali)), 4 => $drk->tujuan . "(" . $drk->negara . ")", 5 => $drk->penyelenggara, 6 => ($drk->sumber_dana ? "Non APBN" : "APBN"), 7 => $drk->keterangan), null);
                $i++;
            }
        }
        ?>
        
        <ss:Row></ss:Row>
        <?php $drtjs = $pegawai->getRiwayatTandaJasa(array(), "tanggal DESC"); ?>
        <?php echo write_excel(array(1 => "VIII. RIWAYAT TANDA JASA"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "JENIS", 3 => "TANGGAL", 4 => "PEMBERI TANDA JASA"), array(4 => 3), "head");
        if ($drtjs->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($drtjs as $drtj) {
                echo write_excel(array(1 => $i, 2 => $drtj->MTandaJasa->nama, 3 => date_id("j F Y", strtotime($drtj->tanggal)), 4 => $drtj->keterangan), array(4 => 3));
                $i++;
            }
        }
        ?>
        
        <ss:Row></ss:Row>
        <?php $anaks = $pegawai->getAnak(array(), "tanggal_lahir DESC"); ?>
        <?php echo write_excel(array(1 => "IX. KETERANGAN KELUARGA (ANAK)"), array(1 => 6), "title"); ?>
        <?php
        echo write_excel(array(1 => "NO", 2 => "NAMA ANAK", 4 => "JENIS KELAMIN", 6 => "TANGGAL LAHIR"), array(2 => 1, 4 => 1, 6 => 1), "head");
        if ($anaks->count() == 0)
            echo write_excel(array(1 => "Tidak ada data"), array(1 => 6), "empty");
        else {
            $i = 1;
            foreach($anaks as $anak) {
                echo write_excel(array(1 => $i, 2 => $anak->nama, 4 => $anak->jenis_kelamin ? "Perempuan" : "Laki-laki", 6 => date_id("j F Y", strtotime($anak->tanggal_lahir))), array(2 => 1, 4 => 1, 6 => 1));
                $i++;
            }
        }
        ?>
    </ss:Table>
</ss:Worksheet>