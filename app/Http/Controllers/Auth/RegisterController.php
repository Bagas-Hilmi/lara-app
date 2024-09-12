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
            'role' => 'required|string|exists:roles,id',
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

        $rolesStructure = config('laratrust_seeder.roles_structure');
        $permissionsMap = config('laratrust_seeder.permissions_map');

        $role = $data['role'];
        if (array_key_exists($role, $rolesStructure)) {
            $user->addRole($role);

            $permissions = $rolesStructure[$role];
            foreach ($permissions as $resource => $actions) {
                foreach (str_split($actions) as $action) {
                    if (isset($permissionsMap[$action])) {
                        $permission = $permissionsMap[$action];
                        $user->givePermissionTo("{$resource}-{$permission}");
                    }
                }
            }
        }

        // Kembalikan pengguna yang baru dibuat
        return $user;
    }
}
