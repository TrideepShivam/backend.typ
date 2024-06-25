<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $table = 'stories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'language',
        'level',
        'content'
    ];
    public function attempts()
    {
        return $this->hasMany(TestAttempts::class, 'id');
    }
}
