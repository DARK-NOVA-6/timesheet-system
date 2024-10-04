<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'gender', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationship with Project (Many-to-Many)
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    // Relationship with Timesheet (One-to-Many)
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}
