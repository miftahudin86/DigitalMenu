<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('admin/login');
    }

    public function login()
    {
        $model = new AdminModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $admin = $model->where('username', $username)->first();

        if ($admin) {
            if (password_verify((string)$password, $admin['password'])) {
                session()->set([
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                    'isLoggedIn' => true
                ]);
                return redirect()->to('/dashboard');
            }
        }

        session()->setFlashdata('error', 'Username atau Password salah');
        return redirect()->to('/auth');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }

    public function create_admin()
    {
        $model = new AdminModel();
        if (!$model->first()) {
            $model->save([
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
            ]);
            return 'Admin created! Username: admin, Password: admin123';
        }
        return 'Admin already exists.';
    }
}
