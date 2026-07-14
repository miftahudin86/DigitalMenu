<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Nama/label meja, contoh: Meja 1, VIP A',
            ],
            'number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Nomor unik meja',
            ],
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 4,
                'comment'    => 'Kapasitas kursi meja',
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Lokasi meja, contoh: Indoor, Outdoor, Lantai 2',
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('number');
        $this->forge->createTable('tables');
    }

    public function down()
    {
        $this->forge->dropTable('tables');
    }
}
