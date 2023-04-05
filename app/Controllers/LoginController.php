<?php

namespace App\Controllers;

class LoginController extends BaseController
{
    // Displays the login page
    public function index()
    {
        return view('login');
    }

    // Handles the login form submission
    public function login()
    {
        // Define the validation rules for the form
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        // Check if the form was submitted via POST request
        if ($this->request->getMethod() == 'post') {
            // Validate the form data against the defined rules
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                // Connect to the database and retrieve the user by email
                $db = \Config\Database::connect();
                $builder = $db->table('user');
                $user = $builder->where('email', $email)->get()->getRow();

                // If a user with the given email exists, check the password
                if ($user) {
                    if (password_verify($password, $user->password)) {
                        // If the user's account is verified, log them in and set a success message
                        if ($user->stutus == 1) {
                            $session = \Config\Services::session();
                            $session->set('user_id', $user->id);

                            $session->setFlashdata('success', 'Logged in successfully!');
                            return view('login');
                        } else {
                            // If the user's account is not verified, set an error message
                            $data['error'] = 'Your account is not verified. Please check your email for a verification link.';
                        }
                    } else {
                        // If the password is incorrect, set an error message
                        $data['error'] = 'Invalid email or password';
                    }
                } else {
                    // If no user with the given email exists, set an error message
                    $data['error'] = 'Invalid email or password';
                }
            } else {
                // If the form validation fails, store the errors in the $data array
                $data['validation'] = $this->validator;
            }
        }

        // Get the success message (if any) and error message from the session
        $session = \Config\Services::session();
        $data['success'] = $session->getFlashdata('success');
        $data['error'] = isset($data['error']) ? $data['error'] : $session->getFlashdata('error');

        // Display the login page with the $data array passed to it
        return view('login', $data);
    }
}
