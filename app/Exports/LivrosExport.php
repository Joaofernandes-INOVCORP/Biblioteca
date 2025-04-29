<?php

namespace App\Exports;

use App\Models\Livro;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LivrosExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function headings(): array
    {
        return [
            'ISBN',
            'Nome',
            'Bibliografia',
            'Preco',
            'Editora'
        ];
    }

    public function collection()
    {
        $livros = Livro::with("editoras")->get()->map(function ($l) {
            return [
                $l->isbn,
                $l->nome,
                $l->bibliografia,
                $l->preco,
                $l->editoras->nome,
            ];
        });
        
        return $livros;
    }
}
