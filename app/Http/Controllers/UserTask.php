<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class UserTaskController extends Controller
{
    // Metode untuk resource `tasks`
    public function index()
    {
        // Logika untuk menampilkan daftar tasks
    }

    public function create()
    {
        // Logika untuk menampilkan form tambah task
    }

    public function store(Request $request)
    {
        // Logika untuk menyimpan task baru
    }

    public function show(Task $task)
    {
        // Logika untuk menampilkan detail task
    }

    public function edit(Task $task)
    {
        // Logika untuk menampilkan form edit task
    }

    public function update(Request $request, Task $task)
    {
        // Logika untuk memperbarui task
    }

    public function destroy(Task $task)
    {
        // Logika untuk menghapus task
    }

    // Metode untuk resource `users`
    public function userIndex()
    {
        // Logika untuk menampilkan daftar users
    }

    public function userCreate()
    {
        // Logika untuk menampilkan form tambah user
    }

    public function userStore(Request $request)
    {
        // Logika untuk menyimpan user baru
    }

    public function userShow(User $user)
    {
        // Logika untuk menampilkan detail user
    }

    public function userEdit(User $user)
    {
        // Logika untuk menampilkan form edit user
    }

    public function userUpdate(Request $request, User $user)
    {
        // Logika untuk memperbarui user
    }

    public function userDestroy(User $user)
    {
        // Logika untuk menghapus user
    }
}
