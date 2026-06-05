<?php

namespace App\Console\Commands;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Usuario;
use Illuminate\Console\Command;

class ReporteBiblioteca extends Command
{
    protected $signature   = 'biblioteca:reporte';
    protected $description = 'Muestra un reporte de libros más prestados, usuarios con vencidos y libros sin stock';

    public function handle(): int
    {
        $this->newLine();
        $this->line('═══════════════════════════════════════');
        $this->info('        REPORTE BIBLIOTECA');
        $this->line('═══════════════════════════════════════');

        // 1. Libros más prestados
        $this->newLine();
        $this->comment('Libros mas prestados (top 5)');

        $masPrestados = Libro::withCount('prestamos')
            ->orderByDesc('prestamos_count')
            ->limit(5)
            ->get(['id', 'titulo', 'stock_disponible']);

        $this->table(
            ['ID', 'Título', 'Veces prestado', 'Stock'],
            $masPrestados->map(fn ($l) => [
                $l->id,
                mb_strimwidth($l->titulo, 0, 40, '...'),
                $l->prestamos_count,
                $l->stock_disponible,
            ])
        );

        // 2. Usuarios con préstamos vencidos
        $this->newLine();
        $this->comment('Usuarios con prestamos vencidos');

        $conVencidos = Usuario::whereHas('prestamos', fn ($q) => $q->where('estado', 'vencido'))
            ->withCount(['prestamos as vencidos' => fn ($q) => $q->where('estado', 'vencido')])
            ->get(['id', 'nombre', 'email']);

        if ($conVencidos->isEmpty()) {
            $this->line('  Sin préstamos vencidos.');
        } else {
            $this->table(
                ['ID', 'Nombre', 'Email', 'Vencidos'],
                $conVencidos->map(fn ($u) => [$u->id, $u->nombre, $u->email, $u->vencidos])
            );
        }

        // 3. Libros sin stock
        $this->newLine();
        $this->comment('Libros sin stock disponible');

        $sinStock = Libro::where('stock_disponible', 0)->get(['id', 'titulo', 'isbn']);

        if ($sinStock->isEmpty()) {
            $this->line('  Todos los libros tienen stock.');
        } else {
            $this->table(
                ['ID', 'Título', 'ISBN'],
                $sinStock->map(fn ($l) => [$l->id, mb_strimwidth($l->titulo, 0, 40, '...'), $l->isbn])
            );
        }

        $this->newLine();

        return Command::SUCCESS;
    }
}
