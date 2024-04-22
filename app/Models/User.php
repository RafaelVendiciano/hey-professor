<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function votes(): HasMany {
        return $this->hasMany(Vote::class);
    }

    public function like(Question $question): void {
        
        $this->votes()->updateOrCreate(
            ['question_id' => $question->id],
            [
            'like' => 1,
            'dislike' => 0
            ]
        );
    }

    public function dislike(Question $question): void {
        
        $this->votes()->updateOrCreate(
            ['question_id' => $question->id],
            [
            'like' => 0,
            'dislike' => 1
            ]
        );
    }

    public function questions(): HasMany {

        return $this->hasMany(Question::class, 'created_by');
    }
}
