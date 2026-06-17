<?php

namespace App\Controllers;

use App\Models\CaisseModel;
use App\Services\AchatService;

class CaisseController extends BaseController
{
    public function choix()
    {
        $caisseModel = new CaisseModel();

        return view('choix_caisse', [
            'caisses' => $caisseModel->getAllCaisse(),
        ]);
    }

    public function enregistrerChoix()
    {
        $caisseId = (int) $this->request->getPost('caisse_id');

        if (!$caisseId) {
            return redirect()->to('/')->with('error', 'Veuillez sélectionner une caisse.');
        }

        $caisseModel = new CaisseModel();
        $caisse = $caisseModel->getCaisseById($caisseId);

        if (!$caisse) {
            return redirect()->to('/')->with('error', 'Caisse introuvable.');
        }

        session()->set('caisse', $caisse);

        return redirect()->to('/achat');
    }
}
