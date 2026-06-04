<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'student_id_number', 'email'];

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student');
    }

    // FIX: use 'student_id_number' as the foreign key (not default 'student_id')
    // and 'student_id_number' as the local key on this model too
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id_number', 'student_id_number');
    }
}