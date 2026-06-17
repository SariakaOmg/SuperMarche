<?php

namespace App\Models;

use CodeIgniter\Model;

class CaisseModel extends Model
{
    protected $table = 'caisse';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle'];
    protected $useTimestamps = false;

    public function getAllCaisse()
    {
        return $this->findAll();
    }

    public function inserer($libelle)
    {
        $data = [
            'libelle' => $libelle
        ];

        return $this->insert($data);
    }
    
    public function getCaisseById($id)
    {
        return $this->find($id);
    }
}