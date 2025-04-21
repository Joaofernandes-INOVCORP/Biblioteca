<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livro;

class LivroController extends Controller
{

    public function index()
    {

        $query = Livro::with('autores', 'editoras');

        if (request("search")) {
            $query->where("nome", "LIKE", "%" . request("search") . "%");
        }

        if (request("price_min")) {
            $query->where("preco", ">", request("price_min"));
        }

        if (request("price_max")) {
            $query->where("preco", "<", request("price_max"));
        }

        $livros = $query->orderBy('preco', strtolower(request("order") ?? "asc") == "asc"? "asc": "desc")->paginate(8);

        return view('livros.index', compact('livros'));
    }

    public function show(Livro $livro)
    {
        $livro->load(['autores', 'editoras']);

        return view('livros.show', compact('livro'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
