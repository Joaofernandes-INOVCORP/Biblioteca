<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Encomenda;
use App\Models\EncomendaLivro;
use App\Models\Livro;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function finalizarPagamento(Request $request)
    {
        $morada = $request->validate(['morada' => 'required|string'])['morada'];

        session(['morada' => $morada]);

        $cart = auth()->user()->cart;
        if (!$cart || $cart->items()->count() === 0) {
            abort(403);
        }


        $products = $cart->items()->get()->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int) ($item->livro->preco * 100),
                    'product_data' => [
                        'name' => $item->livro->nome,
                    ],
                ],
                'quantity' => $item->amount,
            ];
        })->toArray();

        return auth()->user()->checkout(
            $products,
            [
                'success_url' => route('checkout.sucesso'),
                'cancel_url' => route('checkout.cancelado'),
            ]
        );
    }

    public function enviado(Request $request)
    {
        $encomenda = Encomenda::findOrFail($request->validate(['id' => 'required|exists:encomendas,id'])['id']);
        $encomenda->estado = 'enviada';
        $encomenda->save();
        return back();
    }

    public function sucesso()
    {
        $morada = session()->pull('morada');
        if (!$morada) {
            abort(403);
        }

        $cart = auth()->user()->cart;

        $enc = Encomenda::create([
            'user_id' => auth()->user()->id,
            'total' => $cart->items->sum(fn($item) => $item->amount * $item->livro->preco),
            'morada' => $morada
        ]);


        foreach ($cart->items as $item) {
            $livro = Livro::find($item->livro->id);
            $livro->stock -= $item->amount;
            $livro->save();

            EncomendaLivro::create([
                'encomenda_id' => $enc->id,
                'livro_id' => $item->livro->id,
                'quantidade' => $item->amount,
                'preco' => $item->livro->preco,
            ]);
        }

        Cart::destroy($cart->id);

        return redirect(route('livros.index'));
    }

    public function cancelado()
    {
        $morada = session()->pull('morada');
        if (!$morada) {
            abort(403);
        }

        return redirect(route('cart'));
    }
}
