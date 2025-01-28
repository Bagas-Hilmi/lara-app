<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function create()
    {
        return view('pages.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not authenticated.');
        }

        $attributes = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required',
            'phone' => 'required|max:14',
            'about' => 'required|max:150',
            'location' => 'required'
        ]);

        // Menggunakan Eloquent untuk update
        User::where('id', $user->id)->update($attributes);

        // Atau, untuk mengambil kembali model dan memperbarui
        $updatedUser = User::find($user->id);
        $updatedUser->update($attributes);

        return back()->with('status', 'Profile successfully updated.');
    }
}
