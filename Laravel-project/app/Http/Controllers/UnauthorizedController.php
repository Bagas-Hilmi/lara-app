<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnauthorizedController extends Controller
{
    public function index()
    {
        return view('unauthorized');
    }
}