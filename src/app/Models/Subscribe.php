<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */ 
    protected $guarded = [];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function thread(){
        return $this->belongsTo(Thread::class);
    }
}
