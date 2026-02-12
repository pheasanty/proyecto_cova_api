<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    /**
     * Display a listing of user activities.
     */
    public function index(Request $request)
    {
        $query = UserActivity::with('user');

        // Filter by user_id if provided
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 50));

        return response()->json($activities);
    }

    /**
     * Store a new activity log.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $activity = UserActivity::create([
            'user_id' => $validated['user_id'],
            'action' => $validated['action'],
            'description' => $validated['description'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Actividad registrada',
            'data' => $activity
        ], 201);
    }
}
