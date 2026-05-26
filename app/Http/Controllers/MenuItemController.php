<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuItemController extends Controller
{
    /**
     * Gate helper: only the restaurant's owner or an admin can manage menu items.
     */
    private function authorizeManage(Restaurant $restaurant): void
    {
        $user = Auth::user();
        abort_unless(
            $user->isAdmin() || $restaurant->isOwnedBy($user),
            403,
            'You are not allowed to manage items for this restaurant.'
        );
    }

    /**
     * Show create-item form for a restaurant.
     */
    public function create(Restaurant $restaurant)
    {
        $this->authorizeManage($restaurant);
        return view('restaurants.menu_items.create', compact('restaurant'));
    }

    /**
     * Store a new menu item.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $this->authorizeManage($restaurant);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:500',
            'price'        => 'required|numeric|min:0',
            'unit'         => 'nullable|string|max:50',
            'is_available' => 'boolean',
        ]);

        $validated['is_available'] = $request->boolean('is_available', true);

        $restaurant->menuItems()->create($validated);

        return redirect()->route('restaurants.show', $restaurant)
                         ->with('success', 'Menu item added!');
    }

    /**
     * Show edit form for a menu item.
     */
    public function edit(Restaurant $restaurant, MenuItem $menuItem)
    {
        $this->authorizeManage($restaurant);
        abort_unless($menuItem->restaurant_id === $restaurant->id, 404);

        return view('restaurants.menu_items.edit', compact('restaurant', 'menuItem'));
    }

    /**
     * Update a menu item.
     */
    public function update(Request $request, Restaurant $restaurant, MenuItem $menuItem)
    {
        $this->authorizeManage($restaurant);
        abort_unless($menuItem->restaurant_id === $restaurant->id, 404);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:500',
            'price'        => 'required|numeric|min:0',
            'unit'         => 'nullable|string|max:50',
            'is_available' => 'boolean',
        ]);

        $validated['is_available'] = $request->boolean('is_available', true);

        $menuItem->update($validated);

        return redirect()->route('restaurants.show', $restaurant)
                         ->with('success', 'Menu item updated!');
    }

    /**
     * Delete (soft-delete) a menu item.
     */
    public function destroy(Restaurant $restaurant, MenuItem $menuItem)
    {
        $this->authorizeManage($restaurant);
        abort_unless($menuItem->restaurant_id === $restaurant->id, 404);

        $menuItem->delete();

        return redirect()->route('restaurants.show', $restaurant)
                         ->with('success', 'Menu item removed.');
    }
}
