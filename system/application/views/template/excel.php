<?php
header("Content-Type: application/xls");
header("Content-Disposition: attachment;filename=" . $filename . " ");
echo "<?xml version=\"1.0\"?>
<?mso-application progid=\"Excel.Sheet\"?>
";
?>
<ss:Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
             xmlns:o="urn:schemas-microsoft-com:office:office"
             xmlns:x="urn:schemas-microsoft-com:office:excel"
             xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
             xmlns:html="http://www.w3.org/TR/REC-html40">
    <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
        <WindowHeight>8385</WindowHeight>
        <WindowWidth>18975</WindowWidth>
        <WindowTopX>120</WindowTopX>
        <WindowTopY>120</WindowTopY>
        <ProtectStructure>False</ProtectStructure>
        <ProtectWindows>False</ProtectWindows>
    </ExcelWorkbook>

<?php echo $content; ?>

</ss:Workbook>