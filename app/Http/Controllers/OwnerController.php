<?php

namespace App\Http\Controllers;

class OwnerController extends Controller
{
    public function dashboard()
    {
        return view('owner.dashboard');
    }
}
