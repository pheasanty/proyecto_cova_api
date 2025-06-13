<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MilkingSession;
use App\Http\Requests\StoreMilkingSessionRequest;
use App\Http\Resources\MilkingSessionResource;
use Illuminate\Http\Request;

class MilkingSessionController extends Controller
{
// app/Http/Controllers/Api/MilkingSessionController.php
public function index(Request $request)
{
    $query = MilkingSession::with('animal')->latest();

    // ?animal_id=UUID   →  filtra solo esas sesiones
    if ($request->filled('animal_id')) {
        $query->where('animal_id', $request->animal_id);
    }

    // ?per_page=20      →  cambia tamaño de página
    $sessions = $query->paginate($request->integer('per_page', 15));

    return MilkingSessionResource::collection($sessions);
}


    /** POST /api/milking-sessions */
    public function store(StoreMilkingSessionRequest $request)
    {
        $session = MilkingSession::create($request->validated());

        return MilkingSessionResource::make($session->load('animal'))
               ->response()
               ->setStatusCode(201);
    }

    /** GET /api/milking-sessions/{id} */
    public function show(MilkingSession $milkingSession)
    {
        return MilkingSessionResource::make(
            $milkingSession->load('animal')
        );
    }

    /** PUT/PATCH /api/milking-sessions/{id} */
    public function update(StoreMilkingSessionRequest $request, MilkingSession $milkingSession)
    {
        $milkingSession->update($request->validated());

        return MilkingSessionResource::make(
            $milkingSession->load('animal')
        );
    }

    /** DELETE /api/milking-sessions/{id} */
    public function destroy(MilkingSession $milkingSession)
    {
        $milkingSession->delete();

        return response()->noContent();
    }
}
