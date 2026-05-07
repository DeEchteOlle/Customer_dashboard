<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $websites = Website::all();
        return view('websites.index', compact('websites'));
    }

    public function create()
    {
        return view('websites.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'url'  => 'required|url',
        ]);
        Website::create($validatedData);
        return redirect('websites')->with('success', 'Website created successfully.');
    }
    public function edit($id)
    {
        $website = Website::findOrFail($id);
        return view('websites.edit', compact('website'));
    }

    public function update(Request $request, $id)
    {
        $website = Website::findOrFail($id);
        $website->update($request->all());
        return redirect()->route('websites.index')->with('success', 'Website updated successfully.');
    }
    public function delete($id)
    {
        $website = Website::findOrFail($id);
        $website->delete();
        return redirect()->route('websites.index')->with('success', 'Website deleted successfully.');
    }
}

