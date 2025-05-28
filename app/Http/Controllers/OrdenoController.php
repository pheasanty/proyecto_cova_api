<?php

namespace App\Http\Controllers;
use App\Models\Ordeno;

use Illuminate\Http\Request;

class OrdenoController extends Controller
{
    //

    public function store(Request $request)
{
    $validated = $request->validate([
        'vaca_id' => 'required|exists:vacas,id',
        'fecha' => 'required|date',
        'hora' => 'required',
        'cantidad_litros' => 'required|numeric|min:0',
        'responsable' => 'required|string'
    ]);

    $ordeno = Ordeno::create($validated);

    return response()->json([
        'message' => 'Registro de ordeño guardado correctamente',
        'ordeno' => $ordeno
    ], 201);
}

}
