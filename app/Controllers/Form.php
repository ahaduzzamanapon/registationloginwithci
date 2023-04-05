<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Form extends BaseController
{

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function index()
    {
    return view('form');
    }
    public function stor()
    {
        $data = [];
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
    
        if ($this->request->getMethod() == 'post') {
            if ($this->validate($rules)) {
                // Validation passed, insert data into the database
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $token = bin2hex(random_bytes(32));
    
                $data = [
                    'email' => $email,
                    'activation_token' =>  $token,
                    'password' => password_hash($password, PASSWORD_DEFAULT),

                ];
    
                $db = \Config\Database::connect();
                $builder = $db->table('user');
                $builder->insert($data);

                

                $to = $email;
                $subject = 'Confirm your registration';
                $message = 'Thank you for registering. Please click the following link to activate your account: ' . base_url() . '/activate/' . $token;
                
                $email = \Config\Services::email();
                $email->setTo($to);
                $email->setFrom('noreply@example.com', 'My Website');
                $email->setSubject($subject);
                $email->setMessage($message);
                $email->send();

    
                // Set success message
                $session = \Config\Services::session();
                $session->setFlashdata('success', 'registation  successfully! Please activate this account ');
                return redirect()->to('login');
            } else {
                // Validation failed, store errors in the $data array
                $data['validation'] = $this->validator;
            }
        }
    
        // Get the success message (if any) from the session
        $session = \Config\Services::session();
        $data['success'] = $session->getFlashdata('success');
    
        return view('form', $data);
    }
    
    
}
