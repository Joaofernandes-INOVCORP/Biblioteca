<?php

namespace App\Http\Controllers;

use App\Models\Editora;
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

        $livros = $query->orderBy('preco', strtolower(request("order") ?? "asc") == "asc" ? "asc" : "desc")->paginate(8);

        return view('livros.index', compact('livros'));
    }

    public function show(Livro $livro)
    {
        $livro->load(['autores', 'editoras']);

        return view('livros.show', compact('livro'));
    }


    public function create()
    {
        return view('livros.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isbn' => 'required',
            'titulo' => 'required',
            'bibliografia' => 'required',
            'preco' => 'required|decimal:2',
            'editora' => 'required|exists:App\Models\Editora,nome',
            'capa' => 'file|max:4096',
        ]);

        if ($request->file('capa'))
            $capa = $request->file('capa')->store('fotos', 'public');
        else
            $capa = null;

        $livro = Livro::create([
            'isbn' => $data['isbn'],
            'nome' => $data['titulo'],
            'bibliografia' => $data['bibliografia'],
            'preco' => $data['preco'],
            'editora_id' => Editora::where('nome', '=', $data['editora'])->get()->first()->id,
            'capa' => $capa,
        ]);

        return redirect()->route('livros.show', $livro);
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
        Livro::find($id)->delete();

        return redirect()->route('livros.index');
    }
}
