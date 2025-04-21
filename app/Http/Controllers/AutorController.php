<?php

namespace App\Http\Controllers;

use App\Models\Autor;

class AutorController extends Controller
{
    public function index()
    {
        $query = Autor::withCount('livros');

        if (request("search")) {
            $query->where("nome", "LIKE", "%" . request("search") . "%");
        }
        
        $autores = $query->orderBy('nome', strtolower(request("order") ?? "asc") == "asc"? "asc": "desc")->paginate(8);
        
        return view('autores.index', compact('autores'));
    }

    public function show(Autor $autor)
    {
        $autor->load('livros');

        return view('autores.show', compact('autor'));
    }

}
