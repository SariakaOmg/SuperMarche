<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'eleve';
    protected $primaryKey = 'ideleve';
    protected $allowedFields = ['nomeleve', 'prenom'];
    protected $useTimestamps = false;

    public function getStudentsForList(): array
    {
        return $this->select([
                'eleve.ideleve as id',
                'eleve.prenom as firstname',
                'eleve.nomeleve as lastname',
                "CONCAT('EL-', LPAD(eleve.ideleve, 4, '0')) as matricule",
                'COALESCE(MAX(parcours.nomParcours), "") as program',
            ])
            ->join('inscription_parcours', 'inscription_parcours.ideleve = eleve.ideleve', 'left')
            ->join('parcours', 'parcours.idParcours = inscription_parcours.idParcours', 'left')
            ->groupBy('eleve.ideleve')
            ->findAll();
    }

    public function getStudentProfile(int $id): ?array
    {
        $student = $this->select([
                'eleve.ideleve as id',
                'eleve.prenom as firstname',
                'eleve.nomeleve as lastname',
            ])
            ->where('eleve.ideleve', $id)
            ->first();

        return $student ?: null;
    }
}
