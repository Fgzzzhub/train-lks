<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('admin/dashboard');
        }

        if ($this->input->post('username')) {
            $this->__set_rules();
            if ($this->form_validation->run() == true) {
                $username = $this->input->post('username', true);
                $password = $this->input->post('password', true);
                $user     = $this->Users_model->get_by_username($username);
                if ($user && (! isset($user->is_active) || (int) $user->is_active === 1)) {
                    $ok = (md5($password) === $user->password);
                    if ($ok) {
                        $this->session->set_userdata([
                            'logged_in' => true,
                            'user_id'   => $user->id,
                            'username'  => $user->username,
                            'role'      => $user->role,
                            'role_id'   => $user->role_id,
                        ]);

                        redirect('admin/dashboard');
                    }
                }
                $this->session->set_flashdata('error', 'User nggaada');
                redirect('login');
            }
            $this->session->set_flashdata('error', 'Username/Password salah');
        }
        // $this->session->set_flashdata('error','cihuyy');
        $this->load->view('auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    private function __set_rules()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
    }

}
