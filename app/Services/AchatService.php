<?php

namespace App\Services;

use App\Models\AchatModel;
use App\Models\DetailAchatModel;
use App\Models\ProduitModel;
use App\Models\CaisseModel;


class AchatService
{
    protected $achatModel;
    protected $detailAchatModel;
    protected $produitModel;
    protected $caisseModel;

    public function __construct()
    {
        $this->achatModel = new AchatModel();
        $this->detailAchatModel = new DetailAchatModel();
        $this->produitModel = new ProduitModel();
        $this->caisseModel = new CaisseModel();
    }


    public function listeProduits()
    {
        return $this->produitModel->findAll();
    }

    public function listeCaisses()
    {
        return $this->caisseModel->findAll();
    }

    public function getProduitById(int $produitId)
    {
        return $this->produitModel->find($produitId);
    }

    public function getCaisseById(int $caisseId)
    {
        return $this->caisseModel->find($caisseId);
    }

    public function getAchatById(int $achatId)
    {
        return $this->achatModel->find($achatId);
    }


    public function getDetailAchatByAchatId(int $achatId)
    {
        return $this->detailAchatModel->where('achat_id', $achatId)->findAll();
    }

    public function getAllAchats()
    {
        return $this->achatModel->findAll();
    }


    public function verifyIfCanBuy(int $produitId, int $quantite)
    {
        $produit = $this->getProduitById($produitId);
        if (!$produit) {
            throw new \Exception("Produit non trouvé.");
        }

        if ($produit['quantite_stock'] < $quantite) {
            throw new \Exception("Quantité en stock insuffisante pour le produit: " . $produit['libelle']);
        }

        return true;
    }

    public function effectuerAchat(int $caisseId, array $produits)
    {
        $caisse = $this->getCaisseById($caisseId);
        if (!$caisse) {
            throw new \Exception("Caisse non trouvée.");
        }

        foreach ($produits as $produit) {
            $this->verifyIfCanBuy($produit['produit_id'], $produit['quantite']);
        }

        $achatId = $this->achatModel->insert(['caisse_id' => $caisseId]);

        foreach ($produits as $produit) {
            $this->detailAchatModel->insert([
                'achat_id' => $achatId,
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite']
            ]);

            $this->produitModel->decrement('quantite_stock', ['id' => $produit['produit_id']], $produit['quantite']);
        }

        return $achatId;
    }


}
