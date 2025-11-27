<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboradController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', []);
    }
}
