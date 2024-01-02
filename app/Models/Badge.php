<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasPermissions;

class Badge extends Model implements HasMedia
{
    use HasFactory;
    use HasPermissions;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
    ];

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return;
        }
        $parts = explode(' ', $term);
        foreach ($parts as $part) {
            $query->where('name', 'LIKE', "%$part%")
                ->orWhere('description', 'LIKE', "%$part%");
        }
    }

    public function getImageAttribute()
    {
        $url = $this->getFirstMediaUrl('badges');
        $decodedUrl = urldecode($url);
        return str_replace('\\', '/', $decodedUrl);
    }

    public function badgeRequests()
    {
        return $this->hasMany(BadgeRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestedByAuth()
    {
        return $this->badgeRequests()->where('user_id', Auth::guard('web')->id())->first();
    }
}
