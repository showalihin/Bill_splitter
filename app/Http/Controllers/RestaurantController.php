<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RestaurantController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the restaurants index.
     *
     * - Admin  : sees ALL restaurants (global + every user's private ones).
     * - User   : sees all global restaurants + their own private ones.
     */
    public function index()
    {
        $this->authorize('viewAny', Restaurant::class);

        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin sees everything, grouped
            $globalRestaurants  = Restaurant::global()->with('owner')->latest()->get();
            $pendingRestaurants = Restaurant::pendingApproval()->with('owner')->latest()->get();
            $privateRestaurants = Restaurant::where('scope', 'private')
                                            ->where('status', 'private')
                                            ->with('owner')
                                            ->latest()
                                            ->get();
        } else {
            $globalRestaurants  = Restaurant::global()->with('owner')->latest()->get();
            $pendingRestaurants = collect(); // users don't see the pending admin queue
            $privateRestaurants = Restaurant::ownedBy($user->id)
                                            ->where('scope', 'private')
                                            ->latest()
                                            ->get();
        }

        return view('restaurants.index', compact(
            'globalRestaurants',
            'pendingRestaurants',
            'privateRestaurants'
        ));
    }

    /**
     * Show create form.
     * Admin creating = global by default.
     * User creating  = private by default.
     */
    public function create()
    {
        $this->authorize('create', Restaurant::class);
        return view('restaurants.create');
    }

    /**
     * Store a new restaurant.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Restaurant::class);

        $user = Auth::user();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'cuisine'     => 'nullable|string|max:100',
            'address'     => 'nullable|string|max:500',
            'phone'       => 'nullable|string|max:30',
            'description' => 'nullable|string|max:1000',
        ]);

        // Admins create global restaurants directly
        $scope  = $user->isAdmin() ? 'global'   : 'private';
        $status = $user->isAdmin() ? 'approved' : 'private';

        Restaurant::create([
            ...$validated,
            'user_id' => $user->id,
            'scope'   => $scope,
            'status'  => $status,
        ]);

        return redirect()->route('restaurants.index')
                         ->with('success', 'Restaurant created successfully!');
    }

    /**
     * Show a single restaurant with its menu.
     */
    public function show(Restaurant $restaurant)
    {
        $this->authorize('view', $restaurant);

        $menuItems = $restaurant->menuItems()->available()->orderBy('category')->orderBy('name')->get();
        $categories = $menuItems->pluck('category')->unique()->filter()->values();

        return view('restaurants.show', compact('restaurant', 'menuItems', 'categories'));
    }

    /**
     * Show edit form.
     */
    public function edit(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);
        return view('restaurants.edit', compact('restaurant'));
    }

    /**
     * Update a restaurant.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'cuisine'     => 'nullable|string|max:100',
            'address'     => 'nullable|string|max:500',
            'phone'       => 'nullable|string|max:30',
            'description' => 'nullable|string|max:1000',
        ]);

        $restaurant->update($validated);

        return redirect()->route('restaurants.show', $restaurant)
                         ->with('success', 'Restaurant updated successfully!');
    }

    /**
     * Delete a restaurant.
     */
    public function destroy(Restaurant $restaurant)
    {
        $this->authorize('delete', $restaurant);
        $restaurant->delete();

        return redirect()->route('restaurants.index')
                         ->with('success', 'Restaurant deleted.');
    }

    // -------------------------------------------------------------------------
    // "Make Global" workflow
    // -------------------------------------------------------------------------

    /**
     * User requests to make their private restaurant global.
     */
    public function requestGlobal(Restaurant $restaurant)
    {
        $this->authorize('requestGlobal', $restaurant);

        $restaurant->update(['status' => 'pending']);

        return redirect()->route('restaurants.show', $restaurant)
                         ->with('success', 'Your request has been sent to the admin for review!');
    }

    /**
     * Admin approves a pending global request.
     */
    public function approve(Restaurant $restaurant)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $restaurant->update([
            'scope'            => 'global',
            'status'           => 'approved',
            'rejection_reason' => null,
        ]);

        return redirect()->route('restaurants.index')
                         ->with('success', "'{$restaurant->name}' is now a global restaurant!");
    }

    /**
     * Admin rejects a pending global request.
     */
    public function reject(Request $request, Restaurant $restaurant)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $restaurant->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('restaurants.index')
                         ->with('success', "Request for '{$restaurant->name}' has been rejected.");
    }
}
