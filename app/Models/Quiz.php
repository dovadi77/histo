<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quiz';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['material_id', 'title', 'content', 'answer', 'type'];

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
    }
}
