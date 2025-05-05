<?php

namespace App\Http\Controllers;

use App\Models\Editora;
use Illuminate\Http\Request;
use App\Models\Livro;
use Illuminate\Support\Facades\Http;

use App\Exports\LivrosExport;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

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


    public function create(Request $request)
    {
        $data = $request->validate(["isbn" => "sometimes|min_digits:10|max_digits:13"]);
        $livro = [];

        if (!empty($data) && $data["isbn"]) {
            $livro = $this->viaGoogle($data["isbn"]);
        }

        return view("livros.create", compact("livro"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isbn' => 'required',
            'titulo' => 'required',
            'bibliografia' => 'required',
            'preco' => 'required|decimal:2',
            'editora' => 'required|exists:App\Models\Editora,nome',
            'capa' => 'max:4096',
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

    public function viaGoogle(int $isbn)
    {


        $resp = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => 'isbn:' . $isbn,
            'maxResults' => 1,
        ]);


        if (!$resp->ok() || $resp->json('totalItems') == 0) {
            return [
                "error" => "Livro não encontrado!",
            ];
        }

        $info = $resp->json('items.0.volumeInfo');

        return [
            'isbn' => $isbn,
            'titulo' => $info['title'] ?? '',
            'autores' => $info['authors'] ?? [],
            'editora' => $info['publisher'] ?? '',
            'bibliografia' => $info['description'] ?? '',
            'capa' => $info['imageLinks']['thumbnail'] ?? null,
        ];
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

    public function export()
    {
        return Excel::download(new LivrosExport, 'livros.csv');
    }


}
