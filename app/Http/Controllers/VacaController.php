<?php

namespace App\Http\Controllers;
use App\Models\Ordeno;
use App\Models\Vaca;

use Illuminate\Http\Request;

class VacaController extends Controller
{
    //

    public function index() {
    return Vaca::all();
}

public function store(Request $request) {
    return Vaca::create($request->validate([
        'nombre' => 'required',
        'raza' => 'nullable|string',
        'fecha_nacimiento' => 'nullable|date',
        'estado' => 'nullable|string',
    ]));
}
}
