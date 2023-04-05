<?php

namespace App\Controllers;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $data = [];
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
    
        if ($this->request->getMethod() == 'post') {
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
    
                $db = \Config\Database::connect();
                $builder = $db->table('user');
                $user = $builder->where('email', $email)->get()->getRow();
    
                if ($user) {
                    if (password_verify($password, $user->password)) {
                        


                        if ($user->stutus == 1) {
                            $session = \Config\Services::session();
                            $session->set('user_id', $user->id);
    
                            $session->setFlashdata('success', 'Logged in successfully!');
                            return view('login');
                        } else {
                            $data['error'] = 'Your account is not verified. Please check your email for a verification link.';
                        }
                    } else {
                        $data['error'] = 'Invalid email or password';
                    }
                } else {
                    $data['error'] = 'Invalid email or password';
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }
    
        $session = \Config\Services::session();
        $data['success'] = $session->getFlashdata('success');
        $data['error'] = isset($data['error']) ? $data['error'] : $session->getFlashdata('error');
    
        return view('login', $data);
    }
    
    
}
