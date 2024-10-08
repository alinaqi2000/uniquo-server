<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",
        "post_id",
        "comment_id",
        "text",
        "hidden",
        "type"
    ];
    // scopes
    public function scopeComs($query)
    {
        return $query->where('type', "comment");
    }
    public function scopeReps($query)
    {
        return $query->where('type', "reply");
    }
    public function scopeDefault($query)
    {
        return $query->orderBy('created_at', "DESC");
    }
    public function scopeVisible($query)
    {
        return $query->where('hidden', 0);
    }
    // relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function replies()
    {
        return $this->hasMany(self::class, 'comment_id');
    }
}
