<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportIssue extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'evidence',
        'author_id',
    ];
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

}
