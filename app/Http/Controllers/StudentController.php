<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
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
        $user = User::role('Student')->with('student_details')->orderBy('name')->paginate($per_page);

        return UserResource::collection($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $data['password'] = bcrypt('password');
            $program = Program::findOrFail($data['program']);
            $semester = Semester::findOrFail($data['semester']);

            $user = User::create($data);
            $user->students()->create($data + [
                'program_id' => $program->id,
                'semester_id' => $semester->id,
            ]);

            $user->assignRole('Student');
            $user->addMedia(resource_path() . '/images/avatar.png')->preservingOriginal()->toMediaCollection('profile');
            $user->load('student_details');

            DB::commit();
            return response(UserResource::make($user), 201);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response(['message' => 'Something went wrong' . $th], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, User $user)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();

            $program = Program::findOrFail($data['program']);
            $semester = Semester::findOrFail($data['semester']);

            $user->update($data);
            $user->students()->first()->update($data + [
                'program_id' => $program->id,
                'semester_id' => $semester->id,
            ]);

            DB::commit();
            return response(['message' => 'Student has been updated succesfully'], 200);
        } catch (\Throwable $th) {

            DB::rollBack();
            return response(['message' => 'Something went wrong'], 500);
        }
    }
}
