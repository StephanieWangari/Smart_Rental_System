<?php

namespace App\Http\Controllers;

use App\Mail\TenantRegisteredMail;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $tenants = Tenant::with('user', 'property')
            ->when($request->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%")
                )
            )
            ->latest()->paginate(10);

        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        $properties = Property::where('status', 'available')->get();
        return view('tenants.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8',
            'phone'        => 'required|string|max:20',
            'property_id'  => 'required|exists:properties,id',
            'move_in_date' => 'required|date',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'tenant',
        ]);

        $tenant = Tenant::create([
            'user_id'      => $user->id,
            'property_id'  => $request->property_id,
            'phone'        => $request->phone,
            'move_in_date' => $request->move_in_date,
        ]);

        Property::find($request->property_id)->update(['status' => 'occupied']);

        $tenant->load('property');
        Mail::to($user->email)->send(new TenantRegisteredMail($user, $tenant, $request->password));

        return redirect()->route('tenants.index')->with('success', 'Tenant registered successfully.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('user', 'property', 'payments');
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $properties = Property::all();
        return view('tenants.edit', compact('tenant', 'properties'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'property_id' => 'required|exists:properties,id',
            'status'      => 'required|in:active,inactive',
        ]);

        $tenant->user->update(['name' => $request->name]);
        $tenant->update($request->only('phone', 'property_id', 'status'));

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $property = $tenant->property;
        $tenant->user->delete();
        if ($property && $property->tenants()->count() === 0) {
            $property->update(['status' => 'available']);
        }
        return redirect()->route('tenants.index')->with('success', 'Tenant removed.');
    }
}
