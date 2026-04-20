<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $guarded = ['id'];
}