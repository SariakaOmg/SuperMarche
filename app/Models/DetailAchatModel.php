<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailAchatModel extends Model
{
    protected $table         = 'detail_achat';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['achat_id', 'produit_id', 'quantite'];
    protected $useTimestamps = false;
    protected $returnType    = 'array';
}
