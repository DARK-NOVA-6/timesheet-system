<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'department', 'start_date', 'end_date', 'status',
    ];

    // Relationship with User (Many-to-Many)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Relationship with Timesheet (One-to-Many)
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}
