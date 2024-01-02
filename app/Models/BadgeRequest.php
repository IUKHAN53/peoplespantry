<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_id',
        'user_id',
        'status',
        'comments',
        'requested_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approveRequest(): void
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->save();
    }

    public function rejectWithComments($comments): void
    {
        $this->status = 'rejected';
        $this->comments = $comments;
        $this->rejected_at = now();
        $this->save();
    }

}
