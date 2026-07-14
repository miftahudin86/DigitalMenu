<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'   => 'admin',
            'password'   => password_hash('admin123', PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Only insert if no admin exists
        $existing = $this->db->table('admins')->get()->getRowArray();
        if (!$existing) {
            $this->db->table('admins')->insert($data);
            echo "Admin created: username=admin, password=admin123\n";
        } else {
            echo "Admin already exists.\n";
        }
    }
}
