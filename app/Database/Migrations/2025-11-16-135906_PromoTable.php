<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'promo ' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'deskripsi' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'image' => ['type' => 'VARCHAR', 'constraint' => '255', 'default' => 'default.jpg'],
            'promo_code' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true, 'unique' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('promo');
    }

    public function down()
    {
        $this->forge->dropTable('promo');
    }
}