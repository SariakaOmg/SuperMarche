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
        $this->achatModel      = new AchatModel();
        $this->detailAchatModel = new DetailAchatModel();
        $this->produitModel    = new ProduitModel();
        $this->caisseModel     = new CaisseModel();
    }

    public function listeProduits(): array
    {
        return $this->produitModel->findAll();
    }

    public function listeCaisses(): array
    {
        return $this->caisseModel->findAll();
    }

    public function getProduitById(int $produitId): ?array
    {
        return $this->produitModel->find($produitId);
    }

    public function getCaisseById(int $caisseId): ?array
    {
        return $this->caisseModel->find($caisseId);
    }

    public function getAchatById(int $achatId): ?array
    {
        return $this->achatModel->find($achatId);
    }

    public function getDetailAchatByAchatId(int $achatId): array
    {
        return $this->detailAchatModel->where('achat_id', $achatId)->findAll();
    }

    public function getDetailAchatAvecProduits(int $achatId): array
    {
        $db = \Config\Database::connect();
        return $db->table('detail_achat da')
            ->select('da.*, p.libelle, p.prix_unitaire, (da.quantite * p.prix_unitaire) AS sous_total')
            ->join('produit p', 'p.id = da.produit_id')
            ->where('da.achat_id', $achatId)
            ->get()
            ->getResultArray();
    }

    public function getAllAchats(): array
    {
        return $this->achatModel->findAll();
    }

    public function verifyIfCanBuy(int $produitId, int $quantite): bool
    {
        $produit = $this->getProduitById($produitId);

        if (!$produit) {
            throw new \Exception("Produit #{$produitId} non trouvé.");
        }

        if ($produit['quantite_stock'] < $quantite) {
            throw new \Exception(
                "Stock insuffisant pour « {$produit['libelle']} ». "
                . "Disponible : {$produit['quantite_stock']}, demandé : {$quantite}."
            );
        }

        return true;
    }

    public function effectuerAchat(int $caisseId, array $produits): int
    {
        $caisse = $this->getCaisseById($caisseId);
        if (!$caisse) {
            throw new \Exception("Caisse #{$caisseId} non trouvée.");
        }

        foreach ($produits as $item) {
            $this->verifyIfCanBuy((int)$item['produit_id'], (int)$item['quantite']);
        }

        $achatId = $this->achatModel->insert(['caisse_id' => $caisseId], true);

        $db = \Config\Database::connect();
        foreach ($produits as $item) {
            $pid = (int)$item['produit_id'];
            $qte = (int)$item['quantite'];

            $this->detailAchatModel->insert([
                'achat_id'   => $achatId,
                'produit_id' => $pid,
                'quantite'   => $qte,
            ]);

            $db->query(
                'UPDATE produit SET quantite_stock = quantite_stock - ? WHERE id = ?',
                [$qte, $pid]
            );
        }

        return $achatId;
    }
}
