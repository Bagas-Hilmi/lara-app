<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Exception;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::make(Employee::select('id', 'name', 'position', 'birth_date', 'hired_on'))->make(true);
        }

        return view('pages.tables');
    }
}