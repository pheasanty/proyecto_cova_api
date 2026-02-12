<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions grouped by category.
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy('category');

        return response()->json([
            'data' => $permissions
        ]);
    }
}
