<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TestDetails;
use App\Models\Stories;
use App\Models\User;

class TestAttempts extends Model
{
    use HasFactory;
    protected $table = 'test_attempts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'story_id',
        'test_id',
        'duration',
        'mistakes'
    ];

    public function testDetails()
    {
        return $this->belongsTo(TestDetails::class, 'test_id', 'id');
    }

    public function stories()
    {
        return $this->belongsTo(Stories::class, 'story_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
