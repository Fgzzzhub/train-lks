<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
    }

    public function register()
    {
        $data['title'] = 'Register';
        if (! $this->input->post('username')) {
            $this->load->view('auth/register', $data);
            return;
        }

        $this->__set_register_rules();
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors('<p class="text-sm text-danger my-0">', '</p>'));
            $this->load->view('auth/register', $data);

            return;
        }

        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $role     = $this->input->post('role', true);
        $role_id  = $role === 'panitia' ? 4 : 5;

        $is_saved = $this->Users_model->create([
            'username'  => $username,
            'password'  => md5($password),
            'role'      => $role,
            'is_active' => 1,
            'role_id'   => $role_id,
        ]);

        if (! $is_saved) {
            $this->session->set_flashdata('error', 'Registrasi gagal, silakan coba lagi');
            $this->load->view('auth/register', $data);

            return;
        }

        $this->session->set_flashdata('success', 'Registrasi berhasil, silakan login');
        redirect('login');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            $this->redirect_by_role((int) $this->session->userdata('role_id'));
            return;
        }

        if (! $this->input->post('username')) {
            $this->load->view('auth/login');
            return;
        }

        $this->__set_rules();

        if ($this->form_validation->run() !== true) {
            $this->session->set_flashdata('error', 'Format input salah');
            $this->load->view('auth/login');

            return;
        }

        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $user     = $this->Users_model->get_by_username($username);

        if ($user && (! isset($user->is_active) || (int) $user->is_active === 1)) {
            $isValid = md5($password) === $user->password;

            if ($isValid) {
                $this->session->set_userdata([
                    'logged_in' => true,
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'role'      => $user->role,
                    'role_id'   => $user->role_id,
                ]);

                $this->redirect_by_role((int) $user->role_id);
                return;
            }
        }

        $this->session->set_flashdata('error', 'Username/Password salah');
        redirect('login');
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

    private function __set_register_rules()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('password_confirm', 'Retype Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[user,panitia]');
    }

    private function redirect_by_role(int $role_id): void
    {
        if (in_array($role_id, [1, 2, 3], true)) {
            redirect('admin/dashboard');
            return;
        }

        redirect('landing');
    }
}
