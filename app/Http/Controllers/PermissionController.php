<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:permission-list', ['only' => ['index']]);
        $this->middleware('permission:permission-store', ['only' => ['store']]);
        $this->middleware('permission:permission-show', ['only' => ['show']]);
        $this->middleware('permission:permission-update', ['only' => ['update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page') ?: 10;

        if ($request->query('role')) {
            $role = Role::where('name', strtolower($request->query('role')))->firstOrFail();
            $permissions =  $role->permissions()->with('roles')->paginate();
        } else
            $permissions = Permission::with('roles')->paginate($per_page);

        return PermissionResource::collection($permissions);
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
            'name' => 'required|string|min:3|max:255|unique:permissions,name',
        ]);

        try {
            DB::beginTransaction();

            Permission::create($validated + ['guard_name' => 'web']);

            DB::commit();
            return response(['message' => 'Permission has been succesfully created'], 201);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return PermissionResource::make($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:permissions,name,' . $permission->id,
        ]);

        try {
            DB::beginTransaction();

            $permission->update($validated + ['guard_name' => 'web']);

            DB::commit();
            return response(['message' => 'Permission has been succesfully updated'], 200);
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
    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return response(['message' => 'Permission has been succesfully deleted'], 200);
        } catch (\Throwable $th) {
            return response(['message' => 'Something went wrong'], 500);
        }
    }
}
