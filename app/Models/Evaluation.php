<?php

namespace App\Models;

use App\Models\User;
use App\Models\EvaluationsResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'creator_id',
        'type',
        'desc',
        'title',
        'text',
        'students_concerned',
        'evaluation_responses',
        'students_responses'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'evaluation_responses' => 'array',
        'students_responses' => 'array',
        'students_concerned' => 'array',
    ];

    /**
     * Créateur de l'évaluation
     */
    public function creator() {
        return $this->belongsTo(User::class); 
    }

    /**
     * Toutes les réponses des étudiants à l'évaluation avec toutes les informations d'utilsateur pour chaque étudiant
     */
    public function students_responses() {
        return $this->hasMany(EvaluationsResponse::class)->with('student');
    }
}
