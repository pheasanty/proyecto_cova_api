<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Http\Requests\StoreAnimalRequest;
use App\Http\Resources\AnimalResource;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateAnimalRequest;

class AnimalController extends Controller
{
    /** GET /api/animals */
    public function index(Request $request)
    {
        $animals = Animal::latest()->paginate($request->integer('per_page', 15));

        return AnimalResource::collection($animals);
    }

    /** POST /api/animals */
    public function store(StoreAnimalRequest $request)
    {
        $animal = Animal::create($request->validated());

        return AnimalResource::make($animal)
               ->response()
               ->setStatusCode(201);
    }

    /** GET /api/animals/{animal} */
    public function show(Animal $animal)
    {
        return AnimalResource::make($animal);
    }

    /** PATCH /api/animals/{animal} */

        public function update(UpdateAnimalRequest $request, Animal $animal)
        {
            $animal->update($request->validated());
            return AnimalResource::make($animal);
        }

    /** DELETE /api/animals/{animal} */
    public function destroy(Animal $animal)
    {
        $animal->delete();

        return response()->noContent();
    }
}
