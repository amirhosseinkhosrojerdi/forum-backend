<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'thread_id' => 'integer',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function thread(){
        return $this->belongsTo(Thread::class);
    }
}
