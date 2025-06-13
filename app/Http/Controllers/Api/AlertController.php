<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlertResource;
use App\Models\Alert;

class AlertController extends Controller
{
    public function index()
    {
        return AlertResource::collection(
            Alert::latest()->paginate(15)
        );
    }

    public function show(Alert $alert)
    {
        return AlertResource::make($alert);
    }
}
