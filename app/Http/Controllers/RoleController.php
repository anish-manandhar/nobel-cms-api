<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {   
        $this->middleware('permission:role-list', ['only' => ['index']]);
        $this->middleware('permission:role-store', ['only' => ['store']]);
        $this->middleware('permission:role-show', ['only' => ['show']]);
        $this->middleware('permission:role-update', ['only' => ['update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = request('per_page') ?: 10;
        $roles = Role::with('permissions')->paginate($per_page);
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'required_with:permissions|integer|min:0',
        ], [
            'permissions.*.required_with' => 'The permission field is required',
            'permissions.*.integer' => 'The permission field must be an integer',
            'permissions.*.min' => 'The permission field must be an positive integer',
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web'
            ]);

            $role->syncPermissions($validated['permissions']);


            DB::commit();
            return response(['message' => 'Role has been succesfully created'], 201);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response(['message' => 'Something went wrong' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        return RoleResource::make($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'required_with:permissions|integer|min:0',
        ], [
            'permissions.*.required_with' => 'The permission field is required',
            'permissions.*.integer' => 'The permission field must be an integer',
            'permissions.*.min' => 'The permission field must be an positive integer',
        ]);

        try {
            DB::beginTransaction();

            $role->update($validated + ['guard_name' => 'web']);
            $role->syncPermissions($validated['permissions']);


            DB::commit();
            return response(['message' => 'Role has been succesfully updated'], 200);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response(['message' => 'Role has been succesfully deleted'], 200);
        } catch (\Throwable $th) {
            return response(['message' => 'Something went wrong'], 500);
        }
    }
}
