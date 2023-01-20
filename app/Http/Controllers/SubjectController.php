<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:subject-list', ['only' => ['index']]);
        $this->middleware('permission:subject-store', ['only' => ['store']]);
        $this->middleware('permission:subject-show', ['only' => ['show']]);
        $this->middleware('permission:subject-update', ['only' => ['update']]);
        $this->middleware('permission:subject-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = request('per_page') ?: 10;
        $subjects = Subject::with('programs', 'semesters')->orderBy('name')->paginate($per_page);
        return SubjectResource::collection($subjects);
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
            'program' => 'required|integer|min:0',
            'semester' => 'required|integer|min:0',
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|min:3|max:255',
            'subject_code' => 'required|string|min:1|max:15|unique:subjects,subject_code',
            'credit' => 'required|integer|min:0|max:50',
        ]);

        try {
            DB::beginTransaction();

            $program = Program::findOrFail($validated['program']);
            $semester = Semester::findOrFail($validated['semester']);

            Subject::create($validated + [
                'program_id' => $program->id,
                'semester_id' => $semester->id,
            ]);

            DB::commit();
            return response(['message' => 'Subject has been added succesfully'], 201);
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
    public function show(Subject $subject)
    {
        $subject->load('programs', 'semesters');
        return SubjectResource::make($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'program' => 'required|integer|min:0',
            'semester' => 'required|integer|min:0',
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|min:3|max:255',
            'subject_code' => 'required|string|min:1|max:15|unique:subjects,subject_code,' . $subject->id,
            'credit' => 'required|integer|min:0|max:50',
        ]);

        try {
            DB::beginTransaction();

            $program = Program::findOrFail($validated['program']);
            $semester = Semester::findOrFail($validated['semester']);

            $subject->update($validated + [
                'program_id' => $program->id,
                'semester_id' => $semester->id,
            ]);

            DB::commit();
            return response(['message' => 'Subject has been succesfully updated'], 200);
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
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response(['message' => 'Subject has been succesfully deleted'], 200);
    }
}
