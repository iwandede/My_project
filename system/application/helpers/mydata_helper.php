<?php

function write_data($label, $data, $separator = null, $tags = null) {
    if ($tags == null)
        $tags = array("td", "td", "td", "tr");

    $retval = "";
    if (!empty($tags[3]))
        $retval .= "<" . $tags[3] . ">";

    $retval .= "<" . $tags[0] . ">$label</" . $tags[0] . ">";

    if (!empty($tags[2]))
        $retval .= "<" . $tags[2] . ">";
    $retval .= $separator;
    if (!empty($tags[2]))
        $retval .= "</" . $tags[2] . ">";

    $data = trim($data);
    if ($data == "")
        $data = "-";
    $retval .= "<" . $tags[1] . ">$data</" . $tags[1] . ">";

    if (!empty($tags[3]))
        $retval .= "</" . $tags[3] . ">";

    return $retval;
}

function write_excel($data, $merge, $style = "body") {
    $retval = "<ss:Row>";

    foreach ($data as $key => $cell) {
        if (trim($cell) == "")
            $cell = "-";
        $retval .= "
            <ss:Cell ss:StyleID=\"$style\" ss:Index=\"$key\"";
        if (!empty($merge[$key]))
            $retval .= " ss:MergeAcross=\"" . $merge[$key] . "\"";
        $retval .= "><ss:Data ss:Type=\"String\">$cell</ss:Data></ss:Cell>";
    }

    $retval .= "
            <ss:Cell><ss:Data ss:Type=\"String\" ss:Index=\"8\"> </ss:Data></ss:Cell>
        </ss:Row>
";

    return $retval;
}

function write_data_separator($tags = null) {
    return write_data("&nbsp;", "&nbsp;", $tags);
}

function write_some($text, $length = 20) {

    $exp_text = explode(' ', $text);
    $some_text = '';
    $i = 1;
    foreach ($exp_text as $teks) {
        if ($i <= $length)
            $some_text .=$teks . ' ';

        $i++;
    }
    return $some_text;
}

?>
