<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:program-list', ['only' => ['index']]);
        $this->middleware('permission:program-store', ['only' => ['store']]);
        $this->middleware('permission:program-show', ['only' => ['show']]);
        $this->middleware('permission:program-update', ['only' => ['update']]);
        $this->middleware('permission:program-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page') ?: 10;

        if ($request->query('faculty')) {
            $faculty = Faculty::query()
                ->where(DB::raw("LOWER(name)"), strtolower($request->query('faculty')))
                ->firstOrFail();
            $programs = $faculty->programs()->with('faculty')->paginate($per_page);
        } else
            $programs = Program::with('faculty')->orderBy('name')->paginate($per_page);

        return ProgramResource::collection($programs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:255|unique:programs,name',
            'description' => 'nullable|string|min:3|max:255',
        ]);

        try {
            DB::beginTransaction();

            $faculty->programs()->create($validated);

            DB::commit();
            return response(['message' => 'Program has been succesfully created'], 201);
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
    public function show(Program $program)
    {
        $program->load('faculty');
        return ProgramResource::make($program);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faculty $faculty, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:255|unique:programs,name,' . $program->id,
            'description' => 'nullable|string|min:3|max:255',
        ]);

        try {
            DB::beginTransaction();

            $program->update($validated + [
                'faculty_id' => $faculty->id,
            ]);

            DB::commit();
            return response(['message' => 'Program has been succesfully updated'], 200);
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
    public function destroy(Program $program)
    {
        $program->delete();
        return response(['message' => 'Program has been succesfully deleted'], 200);
    }
}
