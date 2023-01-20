<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\UserResource;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index']]);
        $this->middleware('permission:user-store', ['only' => ['store']]);
        $this->middleware('permission:user-update', ['only' => ['update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = request('per_page') ?: 10;
        $user = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Student');
        })->with('employees')->orderBy('name')->paginate($per_page);

        return UserResource::collection($user);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $data['password'] = bcrypt('password');

            if (isset($data['program']))
                $program = Program::findOrFail($data['program']);

            if (isset($data['faculty']))
                $faculty = Faculty::findOrFail($data['faculty']);

            $user = User::create($data);

            $user->employees()->create($data + [
                'faculty_id' => $faculty->id ?? null,
                'program_id' => $program->id ?? null,
            ]);

            $user->assignRole($data['role']);
            $user->addMedia(resource_path() . '/images/avatar.png')->preservingOriginal()->toMediaCollection('profile');
            $user->load('employees');

            DB::commit();
            return response(UserResource::make($user), 201);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, User $user)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            if (strtolower($data['role']) == User::STUDENT)
                return response(['message' => "User with role {$user->getRoleNames()[0]} can't be changed to student"], 400);

            if (isset($data['program']))
                $program = Program::findOrFail($data['program']);

            if (isset($data['faculty']))
                $faculty = Faculty::findOrFail($data['faculty']);

            $user->update($data);

            $user->employees()->first()->update($data + [
                'faculty_id' => $faculty->id ?? null,
                'program_id' => $program->id ?? null,
            ]);

            $user->syncRoles($data['role']);

            DB::commit();
            return response(['message' => 'Employee has been updated succesfully'], 200);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response(['message' => 'Something went wrong'], 500);
        }
    }
}
