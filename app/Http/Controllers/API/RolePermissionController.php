<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\PermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\ApiFormatResponse;

class RolePermissionController extends Controller
{
    use ApiFormatResponse;
    public function store(PermissionRequest $request) {
        $validated = $request->validated();
        $role = Role::find($validated['role_id']);
        $role->permissions()->attach($validated['permissions']);
        return $this->respondSuccess($role, 'Permission Added');
    }

    public function allRoles() {
        $roles = Role::select('id', 'name')->get();
        return $this->respondSuccess($roles, 'All Roles');
    }

    public function allPermissions() {
        $permissions = Permission::select('id', 'name')->get();
        return $this->respondSuccess($permissions, 'All Permissions');
    }
}
