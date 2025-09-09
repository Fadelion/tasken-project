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
     * Relation entre utilisateur et tache
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation entre tache et categorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation entre tache et sous taches
     */
    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    // Fonction de comptage des sous-tâches terminées
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
