<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerificationToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'verification_token' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'password',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'verification_token',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['verification_token', 'is_active']);
    }
}