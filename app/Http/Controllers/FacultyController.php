<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:faculty-list', ['only' => ['index']]);
        $this->middleware('permission:faculty-store', ['only' => ['store']]);
        $this->middleware('permission:faculty-show', ['only' => ['show']]);
        $this->middleware('permission:faculty-update', ['only' => ['update']]);
        $this->middleware('permission:faculty-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = request('per_page') ?: 10;
        $faculties = Faculty::with('programs')->orderBy('name')->paginate($per_page);
        return FacultyResource::collection($faculties);
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
            'name' => 'required|string|min:3|max:255|unique:faculties,name',
            'description' => 'nullable|string|min:3|max:255',
        ]);

        try {
            DB::beginTransaction();

            Faculty::create($validated);

            DB::commit();
            return response(['message' => 'Faculty has been succesfully created'], 201);
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
    public function show(Faculty $faculty)
    {
        $faculty->load('programs');
        return FacultyResource::make($faculty);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:faculties,name,' . $faculty->id,
            'description' => 'nullable|string|min:3|max:255',
        ]);

        try {
            DB::beginTransaction();

            $faculty->update($validated);

            DB::commit();
            return response(['message' => 'Faculty has been succesfully updated'], 200);
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
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return response(['message' => 'Faculty has been succesfully deleted'], 200);
    }
}
