<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('produit')->insertBatch([
            ['libelle' => 'Mangue',    'prix_unitaire' => 2000.00,    'quantite_stock' => 20],
            ['libelle' => 'Pomme',    'prix_unitaire' => 2500.00,    'quantite_stock' => 5],
            ['libelle' => 'Gourde',    'prix_unitaire' => 30000.00,    'quantite_stock' => 10],
            ['libelle' => 'Stylo',    'prix_unitaire' => 500.00,    'quantite_stock' => 50],
            ['libelle' => 'Cahier',    'prix_unitaire' => 1000.00,    'quantite_stock' => 30],
        ]);

        $this->db->table('caisse')->insertBatch([
            ['libelle' => 'Caisse 1'],
            ['libelle' => 'Caisse 2'],
        ]);
    }
}
