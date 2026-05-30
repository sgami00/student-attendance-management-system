<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    // Tell Laravel to look at the correct table name if it deviates
    protected $table = 'school_classes';

    protected $fillable = ['name', 'code', 'teacher_id'];

    // Connects back to the Teacher (User)
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Many-to-Many: Classes contain many students
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_student');
    }

    // One-to-Many: A class has many attendance logs
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}