<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Label;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Label::class);
    }

    public function index()
    {
        $labels = Label::orderBy('id')->paginate(15);
        return view('labels.index', compact('labels'));
    }

    public function create()
    {
        $label = new Label();
        return view('labels.create', compact('label'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Label::class);
        
        $data = $request->validate([
            'name' => 'required|unique:labels,name',
            'description' => 'nullable',
        ]);

        $label = Label::create($data);

        return $this->redirectWithSuccess('labels.index', __('messages.Label added successfully!'));
    }

    public function edit(Label $label)
    {
        $this->authorize('update', $label);
        
        return view('labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $this->authorize('update', $label);
        
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $label->update($data);

        return $this->redirectWithSuccess('labels.index', __('messages.Label edited successfully!'));
    }

    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);
        
        if ($label->tasks()->exists()) {
            return $this->redirectWithError('labels.index', __('messages.Failed to delete label!'));
        }

        $label->delete();

        return $this->redirectWithSuccess('labels.index', __('messages.Label deleted successfully!'));
    }

    protected function redirectWithSuccess(string $route, string $message)
    {
        flash($message)->success();
        return redirect()->route($route);
    }
}
