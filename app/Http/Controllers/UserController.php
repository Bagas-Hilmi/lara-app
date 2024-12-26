<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Mendapatkan data dengan pagination dan sesuai dengan server-side processing
            $data = User::select('id', 'name', 'email', 'created_at', 'updated_at'); // Sesuaikan dengan kolom yang ingin ditampilkan
    
            return datatables::of($data)
                ->addColumn('action', function ($row) {
                    // Menambahkan tombol aksi di setiap baris
                    return view('user-management/datatables/actionbtn', ['row' => $row]);
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('user-management.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
