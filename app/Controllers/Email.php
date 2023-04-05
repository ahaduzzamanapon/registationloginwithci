<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Email extends BaseController
{
    public function index()
    { 
        $to ='ahaduzzamanapon@gmail.com';
        $subject ='varifide email';
        $message ='
       ';
        
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('ahaduzzamanapon@gmail.com', 'Confirm Registration');
        
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
            echo 'Email successfully sent';
        } 
		else 
		{
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }
}
