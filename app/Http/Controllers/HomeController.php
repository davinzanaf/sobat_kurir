<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function customer()
    {
        return view('customer.dashboard');
    }
}
