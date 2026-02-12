<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MilkingSession;
use App\Models\Animal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // Validate date filters
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Build query with date filters
        $sessionsQuery = MilkingSession::query();
        
        if ($request->filled('start_date')) {
            $sessionsQuery->where('date', '>=', $validated['start_date']);
        }
        
        if ($request->filled('end_date')) {
            $sessionsQuery->where('date', '<=', $validated['end_date']);
        }

        /* ─── Producción diaria con distribución de calidad ───────────────────────── */
        $daily = (clone $sessionsQuery)
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
        $qualityStats = (clone $sessionsQuery)
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
                'lastMilking'     => $a->lastMilking
                    ? Carbon::parse($a->lastMilking)->toIso8601String()
                    : null,
            ];
        });

    /* ─── Sesiones detalladas ─── */
    $sessions = (clone $sessionsQuery)
        ->with('animal')
        ->orderByDesc('date')
        ->get()
        ->map(function(MilkingSession $s) {
            return [
              'date'       => $s->date->toIso8601String(),
              'startTime'  => $s->start_time,
              'endTime'    => $s->end_time,
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

    /**
     * Export report as PDF
     */
    public function exportPdf(Request $request)
    {
        // TODO: Implement PDF generation using a library like DomPDF or TCPDF
        // For now, return a message
        return response()->json([
            'message' => 'PDF export functionality - To be implemented with DomPDF or TCPDF',
            'note' => 'Install package: composer require barryvdh/laravel-dompdf'
        ], 501);
    }

    /**
     * Export report as CSV
     */
    public function exportCsv(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = MilkingSession::with('animal');
        
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $validated['start_date']);
        }
        
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $validated['end_date']);
        }

        $sessions = $query->orderByDesc('date')->get();

        $csv = "Fecha,Animal,Tag,Producción (L),Calidad,Temperatura,Notas\n";
        
        foreach ($sessions as $session) {
            $csv .= sprintf(
                "%s,%s,%s,%.2f,%s,%.1f,\"%s\"\n",
                $session->date->format('Y-m-d'),
                $session->animal->name,
                $session->animal->tag,
                $session->milk_yield,
                $session->quality,
                $session->temperature ?? 0,
                str_replace('"', '""', $session->notes ?? '')
            );
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="reporte_produccion_' . date('Y-m-d') . '.csv"');
    }
}