<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAchat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'caisse_id'          => ['type' => 'INTEGER', 'null' => false]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('caisse_id', 'caisse', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('achat');
    }

    public function down()
    {
        $this->forge->dropTable('achat');
    }
}
