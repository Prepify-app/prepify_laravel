<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(RoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());
        return response()->json($role, 201);
    }

    public function show($id): JsonResponse
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(RoleRequest $request, $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->update($request->validated());
        return response()->json($role);
    }

    public function destroy($id): Response
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->noContent();
    }
}
