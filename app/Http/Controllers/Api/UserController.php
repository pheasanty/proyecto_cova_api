<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('permissions')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'department' => $user->department,
                'joinDate' => $user->join_date?->toISOString(),
                'lastAccess' => $user->last_login?->toISOString(),
            ];
        });

        return response()->json(['data' => $users]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['admin', 'manager', 'operator', 'viewer'])],
            'status' => ['nullable', Rule::in(['active', 'inactive', 'pending'])],
            'department' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'] ?? 'pending',
            'department' => $validated['department'],
            'password' => Hash::make($validated['password']),
            'join_date' => now(),
        ]);

        // Attach permissions if provided
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
            ]
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        $user = User::with('permissions')->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'department' => $user->department,
                'joinDate' => $user->join_date?->toISOString(),
                'lastAccess' => $user->last_login?->toISOString(),
                'permissions' => $user->permissions,
            ]
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => ['sometimes', 'required', Rule::in(['admin', 'manager', 'operator', 'viewer'])],
            'status' => ['sometimes', 'required', Rule::in(['active', 'inactive', 'pending'])],
            'department' => 'sometimes|required|string|max:100',
            'password' => 'nullable|string|min:6',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Update password only if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Update permissions if provided
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting the last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'message' => 'No se puede eliminar el último administrador'
                ], 403);
            }
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deactivating the last active admin
        if ($user->role === 'admin' && $user->status === 'active') {
            $activeAdminCount = User::where('role', 'admin')
                                   ->where('status', 'active')
                                   ->count();
            if ($activeAdminCount <= 1) {
                return response()->json([
                    'message' => 'No se puede desactivar el último administrador activo'
                ], 403);
            }
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Estado del usuario actualizado',
            'data' => [
                'id' => $user->id,
                'status' => $user->status
            ]
        ]);
    }
}
