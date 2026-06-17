<?php

namespace App\Controllers;

use App\Models\CaisseModel;

class CaisseController extends BaseController
{
    public function choix()
    {
        $caisseModel = new CaisseModel();

        $data['caisses'] = $caisseModel->getAllCaisse();

        return view('choix_caisse', $data);
    }

    public function enregistrerChoix()
    {
        $caisseId = $this->request->getPost('caisse_id');

        if (!$caisseId) {
            return redirect()->to('/choix')->with('error', 'Veuillez sélectionner une caisse.');
        }

        $caisseModel = new CaisseModel();
        $caisse = $caisseModel->getCaisseById($caisseId);

        if (!$caisse) {
            return redirect()->to('/choix')->with('error', 'Caisse introuvable.');
        }

        $session = session();
        $session->set('caisse', $caisse); 

        return view('saisie_achat', [
            'produits' => (new \App\Services\AchatService())->listeProduits(),
            'caisse' => $caisse
        ]);
    }
}