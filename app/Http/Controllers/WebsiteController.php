<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    // Display the homepage with the list of websites
    public function index()
    {
        $websites = Website::all();
        return view('websites.index', compact('websites'));
    }

    // Show the form to create a new website
    public function create()
    {
        return view('websites.create');
    }

    // Store a new website
    public function store(Request $request): RedirectResponse
    {
        Website::create($request->all());
        return redirect('websites')->with('success', 'Website created successfully.');
    }

    // Show the form to edit an existing website
    public function edit($id)
    {
        $website = Website::findOrFail($id);
        return view('websites.edit', compact('website'));
    }

    // Update an existing website
    public function update(Request $request, $id)
    {
        $website = Website::findOrFail($id);
        $website->update($request->all());
        return redirect()->route('websites.index')->with('success', 'Website updated successfully.');
    }

    // Delete a website
    public function delete($id)
    {
        $website = Website::findOrFail($id);
        $website->delete();
        return redirect()->route('websites.index')->with('success', 'Website deleted successfully.');
    }
}

