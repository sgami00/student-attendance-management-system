<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Add this import
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory; // <-- Add this inside the class!

    protected $fillable = ['name', 'student_id_number', 'email'];

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}