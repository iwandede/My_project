<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

class MY_Form_validation extends CI_Form_validation {

    function unique($value, $params) {
        $CI = & get_instance();
        $CI->form_validation->set_message("unique", "Data <i>%s</i> sudah digunakan.");
        $id = $CI->input->post("id");
        list($model, $field) = explode(".", $params, 2);
        $find = "findOneBy" . $field;
        if (($item = Doctrine::getTable($model)->$find($value))) {
            if ($item->id == $id)
                return true;
            else
                return false;
        } else {
            return true;
        }
    }

    function date_format($date) {
        $CI = & get_instance();
        $CI->form_validation->set_message("date_format", "Data <i>%s</i> harus mengikuti format tanggal yyyy-mm-dd.");

        $date = str_split($date);
        return (
        sizeof($date) == 10 &&
        (int) $date[0] >= 0 &&
        (int) $date[0] <= 9 &&
        (int) $date[1] >= 0 &&
        (int) $date[1] <= 9 &&
        (int) $date[2] >= 0 &&
        (int) $date[2] <= 9 &&
        (int) $date[3] >= 0 &&
        (int) $date[3] <= 9 &&
        (int) $date[5] >= 0 &&
        (int) $date[5] <= 9 &&
        (int) $date[6] >= 0 &&
        (int) $date[6] <= 9 &&
        (int) $date[8] >= 0 &&
        (int) $date[8] <= 9 &&
        (int) $date[9] >= 0 &&
        (int) $date[9] <= 9 &&
        $date[4] == "-" &&
        $date[7] == "-"
        );
    }

}