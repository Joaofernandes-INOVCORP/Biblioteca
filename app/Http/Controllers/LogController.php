<?php

namespace App\Http\Controllers;


use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')->latest()->paginate(20);
        return view('logs.index', compact('logs'));
    }

    public static function registarLog(string $modulo, string $acao, $objetoId = null)
    {
        Log::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'modulo' => $modulo,
            'alteracao' => $acao,
            'objeto_id' => $objetoId,
            'ip' => request()->ip(),
            'browser' => request()->userAgent()
        ]);
    }
}
