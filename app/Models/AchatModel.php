<?php

namespace App\Models;

use CodeIgniter\Model;

class AchatModel extends Model
{
    protected $table = 'achat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['caisse_id'];
}