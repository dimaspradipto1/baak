<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DahsboardController extends Controller
{
    public function dashboard()
    {
        return view("layouts.dashboard.index");
    }
}
