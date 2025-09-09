<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable =[
        'name',
        'user_id',
    ];

    /**
     * Get the user that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks() {
        return $this->hasMany(Task::class);
    }
}
