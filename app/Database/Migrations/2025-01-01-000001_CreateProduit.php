<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProduit extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'libelle'          => ['type' => 'TEXT', 'null' => false],
            'prix_unitaire'        => ['type' => 'FLOAT', 'null' => false],
            'quantite_stock' => ['type' => 'INTEGER', 'null' => false]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('produit');
    }

    public function down()
    {
        $this->forge->dropTable('produit');
    }
}
