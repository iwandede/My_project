<?php
class User extends Controller {

    public function __construct() {
        parent::Controller();

        $this->load->helper(array("form", "url", "mycalendar"));
        $this->load->library(array("form_validation", "session"));
        $this->form_validation->set_message("required", "Data <i>%s</i> harus diisi.");
        $this->form_validation->set_message("alpha_numeric", "Data <i>%s</i> hanya dapat diisi dengan karakter huruf dan angka (alpha-numeric).");
        $this->form_validation->set_message("min_length", "Data <i>%s</i> minimal berisi <i>%s</i> karakter.");
        $this->form_validation->set_message("max_length", "Data <i>%s</i> maksimal berisi <i>%s</i> karakter.");
        $this->form_validation->set_message("matches", "Data <i>%s</i> tidak sesuai dengan data <i>%s</i>.");
    }

    public function index() {
        if (!CurrentUser::user())
            redirect("user/login");

        $this->edit();
    }

    public function manage() {
        if (!($user = CurrentUser::user()) || !CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["users"] = Doctrine_Query::create()
                        ->from("DUser")
                        ->where("id != ?", $user->id)
                        ->execute();

        $html = array();
        $html["title"] = "Daftar User";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("user/manage_list", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit() {
        if (!CurrentUser::user())
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();

        $data["user"] = CurrentUser::user();

        $html = array();
        $html["title"] = "Pengaturan User";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("user/edit_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_process() {
        if (!CurrentUser::user())
            redirect("home/error/403");

        $msg = array(
            "type" => "ui-state-error",
            "content" => "Perubahan pengaturan user tidak berhasil."
        );
        $target = "user/edit";

        if ($this->_edit_validate()) {
            $user = CurrentUser::user();
            $user->username = $this->input->post("new_username");
            $user->password = $this->input->post("new_password");
            $user->theme = $this->input->post("theme");
            $user->save();
            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Perubahan pengaturan user berhasil."
            );
        } else
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function edit_other($id = 0) {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $data = array();
        $data["msg"] = $this->_get_flashdata();

        if(!($data["user"] = Doctrine::getTable("DUser")->find($id)))
            redirect("home/error/404");

        $html = array();
        $html["title"] = "Pengaturan User";
        $html["header"] = $this->load->view("template/user_header", "", true);
        $html["content"] = $this->load->view("user/edit_other_form", $data, true);
        $html["footer"] = $this->load->view("template/user_footer", "", true);
        $this->load->view("template/page", $html);
    }

    public function edit_other_process() {
        if (!CurrentUser::user()->role == 1)
            redirect("home/error/403");

        $id = $this->input->post("id");

        $msg = array(
            "type" => "ui-state-error",
            "content" => "Perubahan pengaturan user tidak berhasil."
        );
        $target = "user/edit_other/$id";

        if ($this->_edit_other_validate()) {
            $user = Doctrine::getTable("DUser")->find($id);
            $user->username = $this->input->post("username");
            $user->password = $this->input->post("new_password");
            $user->save();
            $msg = array(
                "type" => "ui-state-highlight",
                "content" => "Perubahan pengaturan user berhasil."
            );
            $target = "user/manage";
        } else
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );

        $this->session->set_flashdata("process_msg", $msg);
        redirect($target);
    }

    public function login() {
        if (CurrentUser::user())
        redirect("/");
        $html = array();
        $html["title"] = "Login";
        $view = "user/login_form";
        $data = array();
        $data["msg"] = $this->_get_flashdata();
        $data["is_dialog"] = true;
        $html["content"] = $this->load->view($view, $data, true);
        $this->load->view("template/page", $html);
    }

    public function login_process() {
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $msg = null;
        if ($this->_login_validate() === false)
            $msg = array(
                "type" => "ui-state-error",
                "content" => validation_errors()
            );
        $this->session->set_flashdata("process_msg", $msg);
        redirect("user/login");
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect("/");
    }

    public function theme_process() {
        $id = func_get_arg(0);
        if (!CurrentUser::user() || $id < 0 || $id >= sizeof(CurrentUser::listThemes()))
            redirect("home/error/403");

        $user = CurrentUser::user();
        $user->theme = $id;
        $user->save();

        $args = func_get_args();
        $args[0] = null;
        redirect(implode("/", $args));
    }

    private function _get_flashdata() {
        $msg = $this->session->flashdata("process_msg");
        if (empty($msg))
            return array("type" => "hidden", "content" => "");
        else
            return $msg;
    }

    private function _login_validate() {
        $this->form_validation->set_rules("username", "Username", "trim|required");
        $this->form_validation->set_rules("password", "Password", "trim|required|callback__authenticate");
        return $this->form_validation->run();
    }

    public function _edit_validate() {
        $this->form_validation->set_rules("username", "Username", "trim|required|unique[DUser.username]");
        $this->form_validation->set_rules("new_username", "Username", "trim|required|unique[DUser.username]");
        $this->form_validation->set_rules("password", "Password lama", "trim|required|callback__authenticate");
        $this->form_validation->set_rules("new_password", "Password baru", "trim|required");
        $this->form_validation->set_rules("passconf", "Ketik ulang password baru", "trim|required|matches[new_password]");
        return $this->form_validation->run();
    }

    public function _edit_other_validate() {
        $this->form_validation->set_rules("username", "Username", "trim|required|unique[DUser.username]");
        $this->form_validation->set_rules("new_password", "Password baru", "trim|required");
        $this->form_validation->set_rules("passconf", "Ketik ulang password baru", "trim|required|matches[new_password]");
        return $this->form_validation->run();
    }

    public function _authenticate() {
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $this->form_validation->set_message("_authenticate", "Password tidak sesuai, silahkan coba lagi.");
        return CurrentUser::login($username, $password);
    }

}
