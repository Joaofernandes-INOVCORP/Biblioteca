<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Encomenda extends Model
{
     protected $fillable = [
        'user_id',
        'total',
        'estado',
        'morada'
    ];

    public function items()
    {
        return $this->hasMany(EncomendaLivro::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
