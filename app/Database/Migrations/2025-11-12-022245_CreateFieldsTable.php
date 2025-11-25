<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFieldsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'alamat' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'harga' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'image' => ['type' => 'VARCHAR', 'constraint' => '255', 'default' => 'default.jpg'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('fields');
    }

    public function down()
    {
        $this->forge->dropTables('fields');
    }
}
