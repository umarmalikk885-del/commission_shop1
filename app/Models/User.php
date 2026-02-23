<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'low_stock_alert_enabled' => 'boolean',
        ];
    }

    /**
     * Get the profile image URL.
     *
     * @return string|null
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (!$this->profile_image) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::url($this->profile_image);
    }

    /**
     * Check if profile image exists.
     *
     * @return bool
     */
    public function hasProfileImage(): bool
    {
        if (!$this->profile_image) {
            return false;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_image);
    }
}
