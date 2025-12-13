<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // For now, just return the view
        // Later we'll add real data from database
        
        return view('dashboard.index');
    }
}