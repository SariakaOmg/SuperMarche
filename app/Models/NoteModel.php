<?php

namespace App\Models;

use CodeIgniter\Model;

class NoteModel extends Model
{
    protected $table = 'note';
    protected $primaryKey = 'idNote';
    protected $allowedFields = ['ideleve', 'idmatiere', 'valeur_note'];
    protected $useTimestamps = false;

    public function getByStudent(int $studentId)
    {
        return $this->db->table('inscription_parcours ip')
            ->select([
                'CAST(SUBSTRING_INDEX(GROUP_CONCAT(n.idNote ORDER BY n.valeur_note DESC, n.idNote DESC), ",", 1) AS UNSIGNED) as note_id',
                'm.idMatiere as idmatiere',
                'm.nomMatiere as subject',
                'COALESCE(MAX(n.valeur_note), 0) as note',
                'COALESCE(m.coefficient, 1) as coeff',
                'COALESCE(m.credit, 0) as credit',
                's.nomSemestre as semester',
                'COALESCE(gm.est_optionnel_groupe, 0) as optional',
            ])
            ->join('programme p', 'p.idSemestre = ip.idSemestre AND (p.idParcours <=> ip.idParcours)', 'inner', false)
            ->join('semestre s', 's.idSemestre = p.idSemestre', 'inner')
            ->join('matiere m', 'm.idMatiere = p.idMatiere', 'inner')
            ->join('note n', 'n.ideleve = ip.ideleve AND n.idmatiere = m.idMatiere', 'left')
            ->join('groupe_matiere gm', 'gm.idGroupe = m.idGroupe', 'left')
            ->where('ip.ideleve', $studentId)
            ->groupBy('ip.idSemestre, m.idMatiere')
            ->orderBy('s.idSemestre', 'ASC')
            ->orderBy('m.nomMatiere', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getSemestersForStudent(int $studentId): array
    {
        return $this->db->table('inscription_parcours ip')
            ->select(['s.idSemestre', 's.nomSemestre'])
            ->join('semestre s', 's.idSemestre = ip.idSemestre', 'inner')
            ->where('ip.ideleve', $studentId)
            ->orderBy('s.idSemestre', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getSubjectsForStudentBySemester(int $studentId, string $semester): array
    {
        return $this->db->table('inscription_parcours ip')
            ->select([
                'm.idMatiere',
                'm.nomMatiere',
                'COALESCE(s.nomSemestre, "") as semester',
            ])
            ->join('programme p', 'p.idSemestre = ip.idSemestre AND (p.idParcours <=> ip.idParcours)', 'inner', false)
            ->join('matiere m', 'm.idMatiere = p.idMatiere', 'inner')
            ->join('semestre s', 's.idSemestre = p.idSemestre', 'left')
            ->where('ip.ideleve', $studentId)
            ->where('s.nomSemestre', $semester)
            ->groupBy('m.idMatiere')
            ->orderBy('s.idSemestre', 'ASC')
            ->orderBy('m.nomMatiere', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getRequiredCreditsForSemester(int $studentId, string $semester): int
    {
        $row = $this->db->table('inscription_parcours ip')
            ->select('COALESCE(SUM(m.credit), 0) as required_credits', false)
            ->join('programme p', 'p.idSemestre = ip.idSemestre AND (p.idParcours <=> ip.idParcours)', 'inner', false)
            ->join('matiere m', 'm.idMatiere = p.idMatiere', 'inner')
            ->join('semestre s', 's.idSemestre = ip.idSemestre', 'inner')
            ->where('ip.ideleve', $studentId)
            ->where('s.nomSemestre', $semester)
            ->get()
            ->getRowArray();

        return (int) ($row['required_credits'] ?? 0);
    }

    public function updateNoteValue(int $noteId, float $value): bool
    {
        return (bool) $this->update($noteId, ['valeur_note' => $value]);
    }
}
