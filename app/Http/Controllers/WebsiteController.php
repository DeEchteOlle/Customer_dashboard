<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $websites = Website::where('user_id', auth()->id())->get();
        return view('websites.index', compact('websites'));
    }

    public function create()
    {
        return view('websites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'url'  => 'required|url',
        ]);
        $validated['user_id'] = auth()->id();
        Website::create($validated);
        return redirect('websites')->with('success', 'Website aangemaakt.');
    }

    public function edit($id)
    {
        $website = Website::where('user_id', auth()->id())->findOrFail($id);
        return view('websites.edit', compact('website'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $website = Website::where('user_id', auth()->id())->findOrFail($id);
        $website->update($request->validate([
            'name' => 'required|max:255',
            'url'  => 'required|url',
        ]));
        return redirect()->route('websites.index')->with('success', 'Website bijgewerkt.');
    }

    public function delete($id)
    {
        Website::where('user_id', auth()->id())->findOrFail($id)->delete();
        return redirect()->route('websites.index')->with('success', 'Website verwijderd.');
    }
}