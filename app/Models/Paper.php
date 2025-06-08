<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'author_id',
        'title', 
        'abstract', 
        'file_path', 
        'status',
        'decision'
    ];
    
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assignments()
    {
        return $this->hasMany(PaperAssignment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'paper_assignments', 'paper_id', 'reviewer_id');
    }
}