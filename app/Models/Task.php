<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


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
        'category_id',
        'user_id'
    ];

    /**
     * Attribut de pourcentage de progression de l'exécution de la tâche
     * en se basant sur le nombre de sous-tâches achevé
     * 
     * @var array
     */
    protected $appends = ['progress_percentage'];
    /**
     * Get the user that owns the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the subtasks for the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    /**
     * Get the completed subtasks for the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function completedSubtasks()
    {
        return $this->hasMany(Subtask::class)->where('status', true);
    }

    /**
     * Calcul du pourcentage de progression basé sur le nombre
     * de sous-tâches terminées
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
    */

    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Charge les compteurs si non présents

                if (!isset($this->subtasks_count) || !isset($this->completed_subtasks_count)) {
                    $this->loadCount(['subtasks', 'completedSubtasks']);
                }

                $totalSubtasks = $this->subtasks_count ?? 0;
                $completedSubtasks = $this->completed_subtasks_count ?? 0;

                if ($totalSubtasks == 0) {
                    return 0;
                }

                return round(($completedSubtasks / $totalSubtasks) * 100);
            }
        );
    }
}
