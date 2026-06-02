<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model {
    protected $table = 'downloads';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['titulo', 'arquivo'];

    public function relacionavel()
    {
        return $this->morphTo();
    }
}
