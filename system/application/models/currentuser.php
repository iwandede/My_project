<?php

class CurrentUser {

    const field = "c3806483dd2acc7991a63220963848760308ff161a14724e7fd842894563ac71";
    private static $user;

    private function __construct() {
        
    }

    public static function user() {
        if (!isset(self::$user)) {
            $CI = & get_instance();
            $CI->load->library("session");

            $item = "title";
            if (!$user_id = $CI->session->userdata(hash("sha256", $CI->config->item("web_$item"))))
                return FALSE;

            if (!$u = Doctrine::getTable("DUser")->find($user_id))
                return FALSE;

            self::$user = $u;
        }

        return self::$user;
    }

    public static function login($username, $password) {

        if (($u = Doctrine::getTable("DUser")->findOneByUsername($username))) {

            $u_input = new DUser();
            $u_input->password = $password;
            if ($u->password == $u_input->password) {
                unset($u_input);

                $CI = & get_instance();
                $CI->load->library("session");
                $CI->session->set_userdata(self::field, $u->id);

                return TRUE;
            }

            unset($u_input);
        }

        return FALSE;
    }
    
    public static function listThemes() {
        return array("south-street", "ui-lightness", "hot-sneaks", "base");
    }
    
    public static function getTheme() {
        $themes = self::listThemes();
        $i = self::user() ? self::user()->theme : 0;
        return $themes[$i];
    }

    public function __clone() {
        trigger_error("Clone is not allowed.", E_USER_ERROR);
    }

}
