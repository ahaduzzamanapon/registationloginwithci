<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Form extends BaseController
{

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session(); // Initialize session
    }

    public function index()
    {
        return view('form'); // Load form view
    }

    public function stor()
    {
        $data = [];
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        // Check if form is submitted with POST method
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

                $db = \Config\Database::connect(); // Get database connection
                $builder = $db->table('user'); // Get table builder
                $builder->insert($data); // Insert data into the user table

                $to = $email;
                $subject = 'Confirm your registration';
                $message = '
                <p>Thank you for registering. Please click the following button to activate your account:</p>
                <a href="' . base_url() . '/activate/' . $token . '" style="display:inline-block;padding:12px 24px;background-color:#007bff;color:#fff;text-decoration:none;">Activate Account</a>
              ';

                $email = \Config\Services::email(); // Get email service
                $email->setTo($to);
                $email->setFrom('noreply@example.com', 'My Website');
                $email->setSubject($subject);
                $email->setMessage($message);
                $email->send(); // Send activation email to user

                // Set success message
                $session = \Config\Services::session();
                $session->setFlashdata('success', 'registation  successfully! Please activate this account ');
                return redirect()->to('login'); // Redirect to login page
            } else {
                // Validation failed, store errors in the $data array
                $data['validation'] = $this->validator;
            }
        }

        // Get the success message (if any) from the session
        $session = \Config\Services::session();
        $data['success'] = $session->getFlashdata('success');

        return view('form', $data); // Load form view with data
    }
}
