<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncomendaLivro extends Model
{
    protected $table = 'encomenda_livro';

    protected $fillable  = [
            'encomenda_id',
            'livro_id',
            'quantidade',
            'preco',
    ];

    public function livro(){
        return $this->belongsTo(Livro::class, 'livro_id');
    }

    public function encomenda(){
        return $this->belongsTo(Encomenda::class, 'encomenda_id');
    }
    
}
