<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MilkingSession;
use App\Models\Animal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Asegúrate de importar Carbon para manejar fechas

class ReportsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        /* ─── Producción diaria con distribución de calidad ───────────────────────── */
        $daily = MilkingSession::query()
            ->selectRaw('DATE(date) as date')
            ->selectRaw('SUM(milk_yield) as totalYield')
            ->selectRaw('AVG(milk_yield) as averageYield')
            ->selectRaw('COUNT(*) as sessionCount')
            ->selectRaw('SUM(CASE WHEN quality = "excellent" THEN 1 ELSE 0 END) as excellent')
            ->selectRaw('SUM(CASE WHEN quality = "good"      THEN 1 ELSE 0 END) as good')
            ->selectRaw('SUM(CASE WHEN quality = "fair"      THEN 1 ELSE 0 END) as fair')
            ->selectRaw('SUM(CASE WHEN quality = "poor"      THEN 1 ELSE 0 END) as poor')
            ->groupByRaw('DATE(date)')
            ->orderByDesc('date')
            ->limit(30)                       // últimos 30 días
            ->get()
            // Mapear al shape que espera tu ReportData
            ->map(fn($d) => [
                'date'                => $d->date->format('Y-m-d'),
                'totalYield'          => (float) $d->totalYield,
                'averageYield'        => (float) $d->averageYield,
                'sessionCount'        => (int)   $d->sessionCount,
                'qualityDistribution' => [
                    'excellent' => (int) $d->excellent,
                    'good'      => (int) $d->good,
                    'fair'      => (int) $d->fair,
                    'poor'      => (int) $d->poor,
                ],
            ]);

        /* ─── Estadísticas de calidad agregadas ─────────────────── */
        $qualityStats = MilkingSession::query()
            ->selectRaw('quality, COUNT(*) as count')
            ->groupBy('quality')
            ->pluck('count', 'quality');

        /* ─── Listado completo de animales ───────────────────────── */
    $animals = Animal::select([
            'id',
            'name',
            'tag',
            'breed',
            'age',
            'health_status   AS healthStatus',
            'average_daily   AS averageDaily',
            'total_production AS totalProduction',
            'last_milking',
        ])
        ->get()
        ->map(function($a) {
            return [
                'id'              => $a->id,
                'name'            => $a->name,
                'tag'             => $a->tag,
                'breed'           => $a->breed,
                'age'             => $a->age,
                'healthStatus'    => $a->healthStatus,
                'averageDaily'    => (float) $a->averageDaily,
                'totalProduction' => (float) $a->totalProduction,
                // <-- aquí parseamos con Carbon
                'lastMilking'     => $a->lastMilking
                    ? Carbon::parse($a->lastMilking)->toIso8601String()
                    : null,
            ];
        });

    /* ─── Sesiones detalladas ─── */
    $sessions = MilkingSession::with('animal')
        ->orderByDesc('date')
        ->get()
        ->map(function(MilkingSession $s) {
            return [
              'date'       => $s->date->toIso8601String(),
              'startTime'  => $s->start_time,    // asume que en tu tabla es start_time
              'endTime'    => $s->end_time,      // idem
              'milk_yield' => (float) $s->milk_yield,
              'quality'    => $s->quality,
              'temperature'=> $s->temperature !== null ? (float)$s->temperature : null,
              'notes'      => $s->notes,
              'animalName' => $s->animal->name,
              'animalTag'  => $s->animal->tag,
            ];
        });

    return response()->json([
      'dailyProduction' => $daily,
      'qualityStats'    => $qualityStats,
      'animals'         => $animals,
      'sessions'        => $sessions,
      'sessionsTotal'   => count($sessions),
    ]);
}
}