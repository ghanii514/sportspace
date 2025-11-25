<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UbahFotoProfil extends Migration
{
    public function up()
    {
        $fields = [
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => 'default_profile.jpg',
                'after' => 'email',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'profile_picture');
    }
}
