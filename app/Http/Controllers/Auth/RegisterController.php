<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;
    //setelah tekan tombol sign up user akan ke sini (dibawah) 
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,id'], // Validasi role
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create($data)
    {
        // Buat pengguna baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

            if ($data['role'] == 'super-admin') {
                $user->addRole('super-admin');
                $user->givePermission('task-create');
                $user->givePermission('task-read');
                $user->givePermission('task-update');
                $user->givePermission('task-delete');
                $user->givePermission('task-approve');
                $user->givePermission('task-acknowledge');
        }

        if ($data['role'] == 'admin') {
            $user->addRole('admin');
            $user->givePermission('task-create');
            $user->givePermission('task-read');
            $user->givePermission('task-update');
            $user->givePermission('task-approve');
            $user->givePermission('task-acknowledge');
            // return redirect('writer/articles');
        }

        if ($data['role'] == 'manager') {
            $user->addRole('manager');
            $user->givePermission('task-create');
            $user->givePermission('task-read');
            $user->givePermission('task-update');
            $user->givePermission('task-delete');
            $user->givePermission('task-approve');
            $user->givePermission('task-acknowledge');
            // return redirect('writer/articles');
        }

        if ($data['role'] == 'superintendent') {
            $user->addRole('superintendent');
            $user->givePermission('task-create');
            $user->givePermission('task-read');
            $user->givePermission('task-update');
            $user->givePermission('task-delete');
            $user->givePermission('task-approve');
            // return redirect('articles');
        }

        if ($data['role'] == 'senior-supervisor') {
            $user->addRole('senior-supervisor');
            $user->givePermission('task-create');
            $user->givePermission('task-read');
            $user->givePermission('task-update');
            $user->givePermission('task-delete');
            // return redirect('articles');
        }

        if ($data['role'] == 'supervisor') {
            $user->addRole('supervisor');
            $user->givePermission('task-create');
            $user->givePermission('task-read');
            $user->givePermission('task-update');
            $user->givePermission('task-delete');
            // return redirect('articles');
        }

        if ($data['role'] == 'senior-staff') {
            $user->addRole('senior-staff');
            $user->givePermission('task-create');
            $user->givePermission('task-read');
            $user->givePermission('task-update');
            $user->givePermission('task-approve');
            // return redirect('articles');
        }
        if ($data['role'] == 'staff') {
            $user->addRole('staff');
            $user->givePermission('task-create');
            $user->givePermission('task-read');
            $user->givePermission('task-update');
            // return redirect('articles');
        }

        if ($data['role'] == 'viewer') {
            $user->addRole('viewer');
            $user->givePermission('task-read');
        }

        return $user;
    }
}
