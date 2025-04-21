<?php

namespace App\Http\Controllers;

use App\Models\Editora;

class EditoraController extends Controller
{
    public function index()
    {
        $query = Editora::withCount('livros');

        if (request("search")) {
            $query->where("nome", "LIKE", "%" . request("search") . "%");
        }

        $editoras = $query->orderBy('nome', strtolower(request("order") ?? "asc") == "asc"? "asc": "desc")->paginate(4);

        return view('editoras.index', compact('editoras'));
    }

    public function show(Editora $editora)
    {
        $editora->load('livros.autores');

        return view('editoras.show', compact('editora'));
    }
}
