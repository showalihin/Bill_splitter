<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BillSession;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the Admin Master Dashboard.
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalBills = BillSession::count();
        
        // Calculate total volume handled across the platform
        $totalVolume = BillSession::all()->sum(function($bill) {
            return $bill->calculated_grand_total;
        });

        // Get pending restaurant requests
        $pendingRestaurants = Restaurant::pendingApproval()->with('owner')->latest()->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBills',
            'totalVolume',
            'pendingRestaurants'
        ));
    }

    /**
     * Display the User Management page.
     */
    public function users()
    {
        // Get all users with their bill count
        $users = User::withCount('billSessions')->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }
}
