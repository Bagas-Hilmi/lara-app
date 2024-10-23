<?php

namespace App\Http\Controllers;
use App\Models\Capex;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $availableYears = Capex::getAvailableYears(); // Memanggil metode untuk mendapatkan tahun yang tersedia

        return view('capex.index')->with('availableYears', $availableYears);
    }
}
