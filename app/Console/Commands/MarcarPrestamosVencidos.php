<?php

namespace App\Console\Commands;

use App\Models\Prestamo;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarcarPrestamosVencidos extends Command
{
    protected $signature   = 'biblioteca:marcar-vencidos';
    protected $description = 'Marca como vencidos los préstamos activos con más de 15 días sin devolver';

    public function handle(): int
    {
        $limite = Carbon::now()->subDays(15);

        $actualizados = Prestamo::where('estado', 'activo')
            ->where('fecha_devolucion_estimada', '<', $limite)
            ->update(['estado' => 'vencido']);

        $this->info("Se marcaron {$actualizados} préstamo(s) como vencidos.");

        return Command::SUCCESS;
    }
}
