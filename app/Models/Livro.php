<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Requisicao; 

class Livro extends Model
{
    /** @use HasFactory<\Database\Factories\LivroFactory> */
    use HasFactory;

    public function autores()
    {
        return $this->belongsToMany (Autor::class);
    }

    public function editoras()
    {
        return $this->belongsTo(Editora::class, 'editora_id');    
    }

    public function requisicoes() {
        return $this->hasMany(Requisicao::class);
    }
}
