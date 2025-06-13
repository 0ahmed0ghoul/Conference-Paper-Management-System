<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewAssignment extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's plural convention
    protected $table = 'review_assignments';

    // Specify which fields are mass assignable
    protected $fillable = [
        'paper_id',
        'reviewer_id',
        'status', 
        // e.g. 'pending', 'accepted', 'rejected', 'completed'
        // Add other relevant fields here
    ];

    /**
     * Get the paper related to this assignment.
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * Get the reviewer (user) assigned to this review.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the review submitted for this assignment (if any).
     */
    public function review()
    {
        return $this->hasOne(Review::class, 'assignment_id');
    }
}
