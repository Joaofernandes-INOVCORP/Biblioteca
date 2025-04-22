<?php

namespace App\Http\Controllers;

use App\Mail\NewRequisitionAlert;
use App\Mail\RequisicaoConfirmada;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Auth;
use Mail;


class RequisicaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $reqs = Requisicao::with(['livro', 'user'])->latest()->get();
        } else {
            $reqs = $user->requisicoes()->with('livro')->latest()->get();
        }

        $ativas = Requisicao::where('status', 'ativa')->count();
        $ult30dias = Requisicao::where('created_at', '>=', now()->subDays(30))->count();
        $entreguesHoje = Requisicao::whereDate('data_real_fim', "=", now())->count();

        return view('requisicoes.index', compact('reqs', 'ativas', 'ult30dias', 'entreguesHoje'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validação
        $data = $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'foto_cidadao' => 'required|image|max:2048',
        ]);

        $livro = Livro::findOrFail($data['livro_id']);

        if ($livro->requisicaos()->where('status', 'ativa')->exists()) {
            return back()->withErrors('Livro não disponível');
        }

        if (auth()->user()->requisicaos()->where('status', 'ativa')->count() >= 3) {
            return back()->withErrors('Limite de 3 requisições ativas');
        }

        // Gerar número sequencial
        $proximoId = (Requisicao::max('id') ?? 0) + 1;
        $numero = 'REQ-' . str_pad($proximoId, 4, '0', STR_PAD_LEFT);

        $inicio = now()->toDateString();
        $prev = now()->addDays(5)->toDateString();

        $foto = $request->file('foto_cidadao')->store('fotos', 'public');

        $requisicao = Requisicao::create([
            'numero' => $numero,
            'livro_id' => $livro->id,
            'user_id' => auth()->id(),
            'data_inicio' => $inicio,
            'data_prevista_fim' => $prev,
            'foto_cidadao' => $foto,
        ]);
        
        Mail::to(auth()->user())->send(new RequisicaoConfirmada($requisicao));
        Mail::to(User::where('role', 'admin')->pluck('email'))
            ->send(new NewRequisitionAlert($requisicao));

        return redirect()->route('requisicoes.show', $requisicao);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requisicao $requisicao)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'data_real_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $requisicao->update([
            'data_real_fim' => $request->data_real_fim,
            'status' => 'concluida',
        ]);

        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
}
