<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperAssignment extends Model
{
    use HasFactory;

    protected $table = 'paper_assignments'; // explicitly define table name

    protected $fillable = [
        'paper_id',
        'reviewer_id',
        'assigned_by',
        'assigned_at',
        'completed_at',
        'status',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    
    public function review()
    {
        return $this->hasOne(Review::class, 'assignment_id');
    }
    

}
