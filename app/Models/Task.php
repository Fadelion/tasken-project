<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'category_id'
    ];

    /**
     * Relation entre utilisateur et tache
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation entre tache et categorie
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation entre tache et sous taches
     */
    public function subtasks() {
        return $this->hasMany(Subtask::class);
    }
}
