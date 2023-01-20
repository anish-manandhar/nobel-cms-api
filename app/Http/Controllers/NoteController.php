<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = request('per_page') ?: 10;
        $notes = Note::with('programs', 'semesters','subjects')->orderBy('title')->paginate($per_page);
        return NoteResource::collection($notes);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notes = $request->validate([
            'program' => 'required|integer|min:0',
            'semester' => 'required|integer|min:0',
            'subject' => 'required|integer|min:0',
            'description' => 'nullable|string|min:3|max:255',
        ]);
        // dd($notes);
        $program = Program::findOrFail($notes['program']);
        $semester = Semester::findOrFail($notes['semester']);
        $subject = Subject::findOrFail($notes['subject']);
        // dd($program);



        $validatedData = $request->validate([
            'path' => 'required|mimes:doc,docx,pdf,txt,csv|max:2048',

        ]);
        // dd($validatedData['path']);

        $title = $request->file('path')->getClientOriginalName();
        $path = $request->file('path')->store('public/files');
        $note = new Note();
        $note->program_id = $program->id;
        $note->subject_id = $subject->id;
        $note->semester_id = $semester->id;
        $note->description= $notes['description'];
        $note->title = $title;
        $note->path = $path;
        $note->save();
        return response(['message' => 'Notes has been added succesfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        
        $note->load('programs', 'semesters', 'subjects');
        return NoteResource::make($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        $notes = $request->validate([
            'program' => 'required|integer|min:0',
            'semester' => 'required|integer|min:0',
            'subject' => 'required|integer|min:0',
            'description' => 'nullable|string|min:3|max:255',
        ]);
        // dd($notes);
        $program = Program::findOrFail($notes['program']);
        $semester = Semester::findOrFail($notes['semester']);
        $subject = Subject::findOrFail($notes['subject']);
        $validatedData = $request->validate([
            'path' => 'required|mimes:doc,docx,pdf,txt,csv|max:2048',

        ]);
        // dd($validatedData['path']);

        $title = $request->file('path')->getClientOriginalName();
        $path = $request->file('path')->store('public/files');
        $note->title = $title;
        $note->path = $path;
        $note->update($notes + [
            'program_id' => $program->id,
            'semester_id' => $semester->id,
            'subject_id'  => $subject->id,
        ]);
        return response(['message' => 'Note Details has been succesfully updated'], 200);
       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return response(['message' => 'Note has been removed'], 200);
    }
}
