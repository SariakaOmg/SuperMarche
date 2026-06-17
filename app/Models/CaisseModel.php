<?php

namespace App\Models;

use CodeIgniter\Model;

class CaisseModel extends Model
{
    protected $table = 'caisse';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle']; // Très important : autorise l'insertion du champ 'libelle'
    protected $useTimestamps = false;

    /**
     * Récupère toutes les caisses
     * @return array
     */
    public function getAllCaisse()
    {
        return $this->findAll();
    }

    /**
     * Insère une nouvelle caisse dans la base de données
     * @param string $libelle Le nom de la caisse à ajouter
     * @return bool|int|string Retourne l'ID généré ou false en cas d'échec
     */
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