<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    /** @use HasFactory<\Database\Factories\SubtaskFactory> */
    use HasFactory;

    protected $fillable =[
        'title',
        'status',
        'order'
    ];

    /**
     * Relation entre sous-tache et tache
     */
    public function task() {
        return $this->belongsTo(Task::class);
    }
}
