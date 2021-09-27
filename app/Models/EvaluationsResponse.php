<?php

namespace App\Models;

use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationsResponse extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'evaluation_id',
        'student_concerned_id',
        'responses',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'responses' => 'array',
    ];

    /**
     * Évaluation relié à la réponse de l'évaluation
     */
    public function evaluation() {
        return $this->belongsTo(Evaluation::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_concerned_id', 'id');
    }
}
