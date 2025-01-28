<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\Role;

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
                ->addColumn('role', function ($row) {
                    // Mengambil nama role yang terkait dengan user
                    return $row->roles->pluck('name')->implode(', '); // Jika ada lebih dari satu role, dipisahkan dengan koma
                })
                ->addColumn('action', function ($row) {
                    // Menambahkan tombol aksi di setiap baris
                    return view('user-management/datatables/actionbtn', ['row' => $row]);
                })
                ->addIndexColumn()
                ->make(true);
        }
        // Ambil semua roles
        $roles = Role::all();
        return view('user-management.index', compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); // Ambil semua role
        return view('user-management.modal.index', compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $flag = $request->input('flag');

        if ($flag === 'new-user') {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm-password',
                'role' => 'required|exists:roles,id', // Pastikan role ada di tabel roles
            ]);

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);

            $role = Role::findOrFail($request->role);

            $user->syncRoles([$role->id]); // Gunakan syncRoles

            return response()->json([
                'success' => true,
                'message' => 'User successfully added with role.',
            ]);
        } else if ($flag === 'update-user') {
            $this->validate($request, [
                'id' => 'required|exists:users,id', // Pastikan ID valid
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $request->id, // Email harus unik, kecuali milik user ini
                'password' => 'nullable|same:confirm-password', // Password tidak wajib
                'role' => 'required|exists:roles,id', // Pastikan role valid

            ]);

            $user = User::findOrFail($request->id); // Ambil user berdasarkan ID
            $user->name = $request->name;
            $user->email = $request->email;

            // Jika ada password baru, update
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->roles()->sync([$request->role]); // Sync role yang dipilih

            $user->save(); // Simpan perubahan

            return response()->json([
                'success' => true,
                'message' => 'User successfully updated.',
            ]);
        }
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
