<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "category_id",
        "winner_id",
        "title",
        "slug",
        "cost",
        "entry_fee",
        "prize_money",
        "participants_allowed",
        "announcement_at",
        "voting_start_at",
        "published_at",
        "payment_verified_at",
    ];

    // scopes
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    // relations
    public function organizer()
    {
        return $this->belongsTo(User::class, "organizer_id");
    }
    public function votes()
    {
        return $this->hasMany(PostVotes::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function participants()
    {
        return $this->hasMany(CompetitionParticipants::class, "competition_id");
    }
    public function comments()
    {
        return $this->hasMany(CompetitionComments::class);
    }
}
