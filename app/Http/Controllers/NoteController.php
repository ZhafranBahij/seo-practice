<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Traits\SEOTools;
class NoteController extends Controller
{
    use SEOTools;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Note::query()
                    ->whereAny([
                        'title',
                        'description'
                    ], 'like', "%{$request->search}%")
                    ->latest()
                    ->paginate(10);

        return view('note.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('note.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        // dd($request);
        $validate = $request->validated();
        Note::create($validate);
        return to_route('note.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $data = $note;
        // SEO
        $this->seo()->setTitle($data->title)
                    ->setDescription($data->description)
                    ->opengraph()
                        ->setType('article')
                        ->setUrl(route('note.show', $data->id))
                        ->addImage('https://pbs.twimg.com/media/GtspJcnXcAAFAAg?format=jpg&name=large');

        return view('note.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $data = $note;
        return view('note.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $validate = $request->validated();
        $note->update($validate);
        return to_route('note.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return to_route('note.index');
    }
}
