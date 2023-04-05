<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class users extends BaseController
{
    public function index()
    {
      $db=\Config\Database::connect();
      $query=$db->query("SELECT * FROM details");
$result=$query->getResult();
echo"<pre>";
print_r($result);
    }



    public function activate($token)
{
    $db = \Config\Database::connect();
    $builder = $db->table('user');
    $query = $builder->getWhere(['activation_token' => $token]);

    if ($query->getRow()) {
        $data = [
            'activation_token' => null,
            'stutus' => 1
        ];
        $builder->update($data, ['activation_token' => $token]);

        // Set success message
        $session = \Config\Services::session();
        $session->setFlashdata('success', 'Your account has been activated. Please log in.');
        return redirect()->to('login');

    } else {
     
        $session = \Config\Services::session();
        $session->setFlashdata('error', 'Invalid activation link.');

        echo"registation unsuccessful";
    }
}
  
}
