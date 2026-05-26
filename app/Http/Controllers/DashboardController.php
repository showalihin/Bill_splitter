<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $bills = $user->billSessions()->with(['restaurant', 'items'])->latest()->get();
        
        $totalBills = $bills->count();
        $totalVolume = $bills->sum('calculated_grand_total');
        $settledBills = $bills->where('status', 'settled')->count();
        $openBills = $bills->where('status', 'open')->count();
        
        $recentBills = $bills->take(4);
        
        return view('dashboard', compact('totalBills', 'totalVolume', 'settledBills', 'openBills', 'recentBills'));
    }
}
