<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable =[
        'title'
    ];

    /**
     * Relation entre catégorie et utilisateur
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation entre catégorie et tache
     */

    public function tasks() {
        return $this->hasMany(Task::class);
    }
}
