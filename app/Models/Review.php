<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'paper_id',
        'reviewer_id',
        'assignment_id',
        'score',
        'comments',
        'submitted_at'
    ];

    protected $dates = ['submitted_at'];

    // Relationships
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function assignment()
    {
        return $this->belongsTo(PaperAssignment::class, 'assignment_id');
    }
}