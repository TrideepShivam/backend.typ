<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $table = 'stories';
    protected $fillable = [
        'title',
        'language',
        'level',
        'capitalized',
        'numeric',
        'content'
    ];
}
