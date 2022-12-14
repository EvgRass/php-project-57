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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labels = Label::orderBy('id')->paginate(15);
        return view('labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = new Label();
        return view('labels.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!isset($user)) {
                throw new \Exception('User is not authenticated');
        }

        $data = $this->validate($request, [
            'name' => 'required|unique:labels,name',
            'description' => 'nullable',
        ], ['unique' => __('messages.Label exists')]);

        $label = new Label();
        $label->fill($data);
        $label->save();

        flash(__('messages.Label added successfully!'))->success();

        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Label $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $label->fill($data);
        $label->save();

        flash(__('messages.Label edited successfully!'))->success();

        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Label $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        if ($label->tasks()->count() !== 0) {
            flash(__('messages.Failed to delete label!'))->error();
            return redirect()->route('labels.index');
        }

        $label->delete();
        flash(__('messages.Label deleted successfully!'))->success();

        return redirect()->route('labels.index');
    }
}
