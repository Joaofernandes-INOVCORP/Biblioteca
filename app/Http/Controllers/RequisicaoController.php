<?php

namespace App\Http\Controllers;

use App\Mail\NewRequisitionAlert;
use App\Mail\RequisicaoConfirmada;
use App\Models\Livro;
use App\Mail\LivroDisponivel;
use App\Models\NotificacaoDisponibilidade;
use App\Models\User;
use Carbon\Carbon;
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
            $reqs = Requisicao::with(['livro', 'user'])->orderByRaw("
            CASE status
                WHEN 'ativa' THEN 0
                ELSE 1
            END
            ")->get();
        } else {
            $reqs = $user->requisicoes()->with('livro')->orderByRaw("
            CASE status
                WHEN 'ativa' THEN 0
                ELSE 1
            END
            ")->get();
        }

        $ativas = Requisicao::where('status', 'ativa')->count();
        $ult30dias = Requisicao::where('created_at', '>=', now()->subDays(30))->count();
        $entreguesHoje = Requisicao::whereDate('data_real_fim', "=", now())->count();

        return view('requisicoes.index', compact('reqs', 'ativas', 'ult30dias', 'entreguesHoje'));
    }

    public function create()
    {
        return view("requisicoes.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'foto_cidadao' => 'image|max:2048',
        ]);

        $livro = Livro::findOrFail($data['livro_id']);

        if ($livro->requisicoes()->where('status', 'ativa')->count() >= $livro->stock) {
            return back()->withErrors('Livro fora de stock');
        }

        if (auth()->user()->requisicoes()->where('status', 'ativa')->count() >= 3) {
            return back()->withErrors('Limite de 3 requisições ativas');
        }

        $proximoId = (Requisicao::max('id') ?? 0) + 1;
        $numero = 'REQ-' . str_pad($proximoId, 4, '0', STR_PAD_LEFT);

        $inicio = now()->toDateString();
        $prev = now()->addDays(5)->toDateString();

        $foto = $request->file('foto_cidadao')?->store('fotos', 'public');

        $requisicao = Requisicao::create([
            'numero' => $numero,
            'livro_id' => $livro->id,
            'user_id' => auth()->id(),
            'data_inicio' => $inicio,
            'data_prevista_fim' => $prev,
            'foto_cidadao' => $foto,
        ]);

        $admins = User::where('role', 'admin');

        Mail::to(auth()->user())->send(new RequisicaoConfirmada($requisicao));
        if ($admins->count() > 0){
            Mail::to($admins->get())
                ->send(new NewRequisitionAlert($requisicao, $admins->pluck('email')->toArray()));
        }

        //Nota para amanha: Repetir isto nas acoes todas de todos os controllers
        LogController::registarLog('requisicao', 'criacao', $requisicao->id); 

        return redirect()->route('requisicoes.show', $requisicao);
    }

    public function show(string $id)
    {
        $requisicao = Requisicao::with(["livro", "user"])->find($id);

        return view("requisicoes.show", compact("requisicao"));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $data = $request->validate([
            'action' => 'required|in:finish,extend',
        ]);

        $requisicao = Requisicao::find($id);

        if ($data["action"] === "finish") {
            $requisicao->data_real_fim = now()->toDateString();
            $requisicao->status = "entregue";
             LogController::registarLog('requisicao', 'entrega', $requisicao->id);

            //enviar email após o livro estar disponivel para os users que pediram para serem notificados
            /*$livro = $requisicao->livro;
            $notificacoes = NotificacaoDisponibilidade::where('livro_id', $livro->id)->get();

            foreach ($notificacoes as $not) {
                Mail::to($not->user->email)->send(new LivroDisponivel($livro));
                $not->delete();
            }*/

        } else {
            $requisicao->data_prevista_fim = Carbon::createFromFormat('Y-m-d', $requisicao->data_prevista_fim)->addDays(5)->toDateString();
                    LogController::registarLog('requisicao', 'extensão da data de entrega', $requisicao->id);
        }

        $requisicao->save();

        return back();
    }

    public function destroy(string $id)
    {
        //
    }


}
