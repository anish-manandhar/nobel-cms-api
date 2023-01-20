<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:event-list', ['only' => ['index']]);
        $this->middleware('permission:event-store', ['only' => ['store']]);
        $this->middleware('permission:event-show', ['only' => ['show']]);
        $this->middleware('permission:event-update', ['only' => ['update']]);
        $this->middleware('permission:event-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = request('per_page') ?: 10;
        $event = Event::with('created_by','updated_by')->orderBy('created_at', 'desc')->paginate($per_page);
        return EventResource::collection($event);
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
            'title' => 'required|string|min:5|max:255|unique:events,title',
            'description' => 'required|string|min:5',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $validated['created_by'] = auth()->id();

            $event = Event::create($validated);
            if ($request->hasFile('image') && $request->file('image')->isValid())
                $event->addMediaFromRequest('image')->toMediaCollection('event');

            DB::commit();
            return response(['message' => 'Event has been succesfully created'], 201);
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
    public function show($id)
    {
        $event = Event::find($id)->with('created_by','updated_by');
        return EventResource::make($event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id)->with('created_by','updated_by');
        return EventResource::make($event);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255|unique:events,title',
            'description' => 'required|string|min:5',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $validated['updated_by'] = auth()->id();            

            $event = Event::findOrFail($id);
            $event->update($validated);
            if ($request->hasFile('image') && $request->file('image')->isValid())
                $event->addMediaFromRequest('image')->toMediaCollection('event');

            DB::commit();
            return response(['message' => 'Event has been succesfully updated'], 201);
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
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            Event::destroy($id);

            DB::commit();
            return response(['message' => 'Event has been succesfully deleted'], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response(['message' => 'Something went wrong'], 500);
        }
    }
}
