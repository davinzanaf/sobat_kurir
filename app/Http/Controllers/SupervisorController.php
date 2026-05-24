<?php

namespace App\Http\Controllers;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        return view('supervisor.dashboard');
    }
}
