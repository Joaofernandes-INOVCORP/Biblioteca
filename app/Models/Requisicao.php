<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    protected $fillable = [
        'numero',
        'livro_id',
        'user_id',
        'data_inicio',
        'data_prevista_fim',
        'data_real_fim',
        'status',
        'foto_cidadao',
    ];

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
