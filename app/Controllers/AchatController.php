<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\BaseConnection;
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
        $this->achatModel = new AchatModel();
        $this->detailAchatModel = new DetailAchatModel();
        $this->produitModel = new ProduitModel();
        $this->caisseModel = new CaisseModel();
        $this->achatService = new AchatService();
    }

    public function index()
    {
        // $caisseId = session()->get('caisse_id');
        return view('saisie_achat', [
            'produits' => $this->achatService->listeProduits(),
            'caisse' => $this->achatService->getCaisseById(1)
        ]);
    }

    public function verifyIfCanBuy()
    {
        $produitId = $this->request->getPost('produit_id');
        $quantite = $this->request->getPost('quantite');

        $produit = $this->achatService->getProduitById($produitId);


        if ($this->achatService->verifyIfCanBuy($produitId, $quantite)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Stock suffisant',
                'stock'   => $produit['quantite_stock']
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Stock insuffisant',
            'stock'   => $produit['quantite_stock']
        ]);
    }

    public function createAchat()
    {
        $caisseId = $this->request->getPost('caisse_id');
        $produits = $this->request->getPost('produits'); // Attendu comme un tableau de {produit_id, quantite}

        // Vérifier la caisse
        $caisse = $this->achatService->getCaisseById($caisseId);
        if (!$caisse) {
             return $this->response->setJSON([
                'success' => false,
                'message' => 'Caisse non trouvée.'
            ]);
        }

        if (!$produits || !is_array($produits) || count($produits) === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun produit sélectionné.'
            ]);
        }

        $this->achatService->effectuerAchat($caisseId, $produits);

        return view('choix_caisse');
    }

        
}
