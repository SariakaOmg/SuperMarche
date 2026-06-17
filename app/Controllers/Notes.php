<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\NoteModel;

class Notes extends BaseController
{
    public function index()
    {
        $m = new StudentModel();
        try {
            $students = $m->getStudentsForList();
            return view('list', ['students' => $students, 'dbError' => null]);
        } catch (\Throwable $e) {
            return view('list', [
                'students' => [],
                'dbError' => 'Connexion BD impossible. Verifiez MySQL (base Note) et les identifiants.',
            ]);
        }
    }

    public function view($id = null)
    {
        $sModel = new StudentModel();
        $nModel = new NoteModel();
        try {
            $student = $sModel->getStudentProfile((int) $id);
            if (!$student) {
                return redirect()->to('/notes');
            }
            $notes = $nModel->getByStudent((int) $id);
            helper('notes_helper');

            $notesBySemester = [];
            foreach ($notes as $n) {
                $sem = $n['semester'] ?: 'Sans semestre';
                $notesBySemester[$sem][] = $n;
            }

            $semesterSummary = [];
            foreach ($notesBySemester as $sem => $semNotes) {
                $avgSem = weighted_average($semNotes);
                $creditsSem = calculate_credits($semNotes);
                $requiredCredits = $nModel->getRequiredCreditsForSemester((int) $id, (string) $sem);
                $passSem = passes_semester($semNotes, max(1, $requiredCredits));
                $semesterSummary[$sem] = [
                    'avg' => $avgSem,
                    'credits' => $creditsSem,
                    'required' => $requiredCredits,
                    'pass' => $passSem,
                    'mention' => mention_from_avg($avgSem),
                ];
            }

            $avgS3 = $semesterSummary['S3']['avg'] ?? null;
            $avgS4 = $semesterSummary['S4']['avg'] ?? null;
            $l2Avg = null;
            if ($avgS3 !== null && $avgS4 !== null) {
                $l2Avg = round((floatval($avgS3) + floatval($avgS4)) / 2, 2);
            }

            $l2Credits = intval($semesterSummary['S3']['credits'] ?? 0) + intval($semesterSummary['S4']['credits'] ?? 0);
            $l2Required = intval($semesterSummary['S3']['required'] ?? 0) + intval($semesterSummary['S4']['required'] ?? 0);
            $l2Pass = ($l2Required > 0) ? ($l2Credits >= $l2Required) : false;

            return view('student_notes', [
                'student' => $student,
                'notesBySemester' => $notesBySemester,
                'semesterSummary' => $semesterSummary,
                'l2Avg' => $l2Avg,
                'l2Credits' => $l2Credits,
                'l2Required' => $l2Required,
                'l2Pass' => $l2Pass,
                'l2Mention' => $l2Avg !== null ? mention_from_avg($l2Avg) : 'N/A',
            ]);
        } catch (\Throwable $e) {
            return redirect()->to('/notes');
        }
    }

    public function add($student_id = null)
    {
        $sModel = new StudentModel();
        $nModel = new NoteModel();

        if ($student_id === null) {
            try {
                $first = $sModel->first();
                $student_id = $first['ideleve'] ?? null;
            } catch (\Throwable $e) {
                $student_id = null;
            }
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'ideleve' => (int) $this->request->getPost('student_id'),
                'idmatiere' => (int) $this->request->getPost('idmatiere'),
                'valeur_note' => (float) $this->request->getPost('note'),
            ];
            try {
                $nModel->insert($data);
            } catch (\Throwable $e) {
                return redirect()->back();
            }
            return redirect()->to(site_url('notes/view/' . $data['ideleve']));
        }

        try {
            $students = $sModel->getStudentsForList();
            $selectedStudentId = (int) ($this->request->getGet('student_id') ?: $student_id);
            $semesters = $selectedStudentId ? $nModel->getSemestersForStudent($selectedStudentId) : [];

            $selectedSemester = $this->request->getGet('semester');
            if (!$selectedSemester && !empty($semesters)) {
                $selectedSemester = $semesters[0]['nomSemestre'];
            }

            $subjects = ($selectedStudentId && $selectedSemester)
                ? $nModel->getSubjectsForStudentBySemester($selectedStudentId, (string) $selectedSemester)
                : [];
        } catch (\Throwable $e) {
            $students = [];
            $semesters = [];
            $selectedStudentId = (int) $student_id;
            $selectedSemester = null;
            $subjects = [];
        }

        return view('form', [
            'student_id' => $selectedStudentId,
            'students' => $students,
            'semesters' => $semesters,
            'selectedSemester' => $selectedSemester,
            'subjects' => $subjects,
        ]);
    }

    public function update($idNote)
    {
        $nModel = new NoteModel();
        $studentId = (int) $this->request->getPost('student_id');
        $value = (float) $this->request->getPost('note');

        try {
            $nModel->updateNoteValue((int) $idNote, $value);
        } catch (\Throwable $e) {
            // keep silent and redirect
        }

        return redirect()->to(site_url('notes/view/' . $studentId));
    }

    public function delete($idNote)
    {
        $nModel = new NoteModel();
        $studentId = (int) $this->request->getPost('student_id');

        try {
            $nModel->delete((int) $idNote);
        } catch (\Throwable $e) {
            // keep silent and redirect
        }

        return redirect()->to(site_url('notes/view/' . $studentId));
    }

    public function createForSubject()
    {
        $nModel = new NoteModel();
        $studentId = (int) $this->request->getPost('student_id');
        $matiereId = (int) $this->request->getPost('idmatiere');
        $value = (float) $this->request->getPost('note');

        if ($studentId > 0 && $matiereId > 0) {
            try {
                $nModel->insert([
                    'ideleve' => $studentId,
                    'idmatiere' => $matiereId,
                    'valeur_note' => $value,
                ]);
            } catch (\Throwable $e) {
                // ignore and redirect
            }
        }

        return redirect()->to(site_url('notes/view/' . $studentId));
    }
}
