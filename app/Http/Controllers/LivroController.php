<?php

namespace App\Http\Controllers;

use App\Models\Editora;
use App\Models\NotificacaoDisponibilidade;
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

        $reqs = $livro->requisicoes()->where("status", "=", "ativa")->count();

        $reqs_with_reviews = $livro->requisicoes()
            ->where('status', 'entregue')
            ->whereHas('review', function ($query) {
                $query->where('estado', 'ativo');
            })
            ->with('review')
            ->get();


        $livros = Livro::all()->pluck('bibliografia', 'id')->toArray();
        $vetores = $this->calcularTFIDF($livros);

        $idAtual = $livro->id;
        $vetorAtual = $vetores[$idAtual];

        $similaridades = [];

        foreach ($vetores as $id => $vetor) {
            if ($id === $idAtual)
                continue;

            $sim = $this->similaridadeCosseno($vetorAtual, $vetor);
            $similaridades[$id] = $sim;
        }

        arsort($similaridades);
        $idsMaisSimilares = array_keys(array_slice($similaridades, 0, 6));
        $recomendacoes = Livro::whereIn('id', $idsMaisSimilares)->get();

        return view('livros.show', compact('livro', 'reqs', 'reqs_with_reviews', 'recomendacoes'));
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
            'stock' => 'required|numeric|integer|min:0'
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

    public function notificarDisponivel(Livro $livro)
    {
        NotificacaoDisponibilidade::firstOrCreate([
            'user_id' => auth()->id(),
            'livro_id' => $livro->id,
        ]);

        return back()->with('sucesso', 'Serás notificado por email quando o livro estiver disponível para requisição.');
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

    //limpeza da string e transformação num array com as palavras -> 'PHP para web' passa para ['php', 'web']
    protected function tokenize(string $text): array
    {
        $text = strtolower(strip_tags($text));
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
        $tokens = explode(' ', $text);

        $stopwords = ['de', 'a', 'e', 'o', 'que', 'do', 'da', 'em', 'um', 'para', 'é', 'com', 'não', 'uma', 'os', 'no'];
        return array_values(array_filter($tokens, fn($t) => strlen($t) > 2 && !in_array($t, $stopwords)));
    }

    protected function calcularTFIDF(array $documentos): array
    {
        $tf = [];
        $df = [];

        foreach ($documentos as $id => $texto) {
            $tokens = $this->tokenize($texto);
            $tf[$id] = array_count_values($tokens);

            foreach (array_unique($tokens) as $termo) {
                $df[$termo] = ($df[$termo] ?? 0) + 1;
            }
        }

        $n = count($documentos);
        $vetores = [];

        foreach ($tf as $id => $frequencias) {
            $vetor = [];

            foreach ($df as $termo => $dfCount) {
                $freqTermo = $frequencias[$termo] ?? 0;
                $tfidf = $freqTermo * log($n / $dfCount);
                $vetor[$termo] = $tfidf;
            }

            $vetores[$id] = $vetor;
        }

        return $vetores;
    }

    protected function similaridadeCosseno(array $v1, array $v2): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        $todosTermos = array_unique(array_merge(array_keys($v1), array_keys($v2)));

        foreach ($todosTermos as $termo) {
            $a = $v1[$termo] ?? 0;
            $b = $v2[$termo] ?? 0;

            $dot += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }

        if ($normA == 0 || $normB == 0)
            return 0;

        return $dot / (sqrt($normA) * sqrt($normB));
    }


}
