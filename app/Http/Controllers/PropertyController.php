<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::when($request->search, fn($q) =>
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('location', 'like', "%{$request->search}%")
        )->latest()->paginate(10);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'rent_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Property::create($request->only('name', 'location', 'rent_amount', 'description'));
        return redirect()->route('properties.index')->with('success', 'Property created successfully.');
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'rent_amount' => 'required|numeric|min:0',
            'status'      => 'required|in:available,occupied',
            'description' => 'nullable|string',
        ]);

        $property->update($request->only('name', 'location', 'rent_amount', 'status', 'description'));
        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted.');
    }

    public function show(Property $property)
    {
        $property->load('tenants.user');
        return view('properties.show', compact('property'));
    }
}
