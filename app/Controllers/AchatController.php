<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AchatModel;
use App\Models\DetailAchatModel;
use App\Models\ProduitModel;
use App\Models\CaisseModel;
use App\Services\AchatService;

class AchatController extends BaseController
{
    protected AchatModel $achatModel;
    protected DetailAchatModel $detailAchatModel;
    protected ProduitModel $produitModel;
    protected CaisseModel $caisseModel;
    protected AchatService $achatService;

    public function __construct()
    {
        $this->achatModel       = new AchatModel();
        $this->detailAchatModel = new DetailAchatModel();
        $this->produitModel     = new ProduitModel();
        $this->caisseModel      = new CaisseModel();
        $this->achatService     = new AchatService();
    }

    public function index()
    {
        $caisse = session()->get('caisse');

        if (!$caisse) {
            return redirect()->to('/')->with('error', 'Veuillez choisir une caisse.');
        }

        return view('saisie_achat', [
            'produits' => $this->achatService->listeProduits(),
            'caisse'   => $caisse,
        ]);
    }

    public function verifyIfCanBuy()
    {
        $produitId = (int) $this->request->getPost('produit_id');
        $quantite  = (int) $this->request->getPost('quantite');

        try {
            $this->achatService->verifyIfCanBuy($produitId, $quantite);
            $produit = $this->achatService->getProduitById($produitId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Stock suffisant',
                'stock'   => $produit['quantite_stock'],
            ]);
        } catch (\Exception $e) {
            $produit = $this->achatService->getProduitById($produitId);

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
                'stock'   => $produit ? $produit['quantite_stock'] : 0,
            ]);
        }
    }

    public function createAchat()
    {
        $json     = $this->request->getJSON(true);
        $caisseId = (int) ($json['caisse_id'] ?? 0);
        $produits = $json['produits'] ?? [];

        if (!$caisseId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Caisse non spécifiée.',
            ]);
        }

        if (empty($produits)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun produit sélectionné.',
            ]);
        }

        try {
            $achatId = $this->achatService->effectuerAchat($caisseId, $produits);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Achat enregistré avec succès.',
                'achat_id' => $achatId,
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function cloturer()
    {
        $caisse   = session()->get('caisse');
        $caisseId = $caisse ? (int) $caisse['id'] : (int) $this->request->getPost('caisse_id');

        $produitsRaw = $this->request->getPost('produits');

        if (!$caisseId) {
            return redirect()->to('/')->with('error', 'Caisse non trouvée.');
        }

        if (empty($produitsRaw) || !is_array($produitsRaw)) {
            return redirect()->to('/achat')->with('error', 'Aucun produit à clôturer.');
        }

        try {
            $this->achatService->effectuerAchat($caisseId, $produitsRaw);

            return redirect()->to('/achat')->with('success', 'Achat clôturé avec succès !');
        } catch (\Exception $e) {
            return redirect()->to('/achat')->with('error', $e->getMessage());
        }
    }
}
