<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'requisicao_id',
        'estado',
        'pontuacao',
        'comentario',
        'razao'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function requisicao() {
        return $this->belongsTo(Requisicao::class);
    }
}
