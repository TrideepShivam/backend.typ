<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TestAttempts;

class TestDetails extends Model
{
    use HasFactory;
    protected $table = 'test_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'wpm',
        'accuracy',
        'words',
        'kpm',
        'duration',
        'char_with_spaces',
        'errors'
    ];
    public function attempts()
    {
        return $this->hasMany(TestAttempts::class, 'id');
    }
}
