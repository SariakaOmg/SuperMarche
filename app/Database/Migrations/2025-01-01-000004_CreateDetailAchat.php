<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailAchat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'achat_id'          => ['type' => 'INTEGER', 'null' => false],
            'produit_id'        => ['type' => 'INTEGER', 'null' => false],
            'quantite' => ['type' => 'INTEGER', 'null' => false]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('achat_id', 'achat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produit_id', 'produit', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_achat');
    }

    public function down()
    {
        $this->forge->dropTable('detail_achat');
    }
}
