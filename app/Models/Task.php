<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'assigned_to',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

    // Jika Anda ingin menambahkan relasi, misalnya dengan model User
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class); // Pastikan User adalah model yang sesuai
    }
}
