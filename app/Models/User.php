<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone_number',
        'role',
        'login_preference',
        'require_password_login',
        'is_admin',
        'is_banned',
        'abuse_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
            'require_password_login' => 'boolean',
            'abuse_count' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships & Accessors
    |--------------------------------------------------------------------------
    */

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function communityMemberships()
    {
        return $this->hasMany(CommunityMember::class);
    }

    public function joinedThreads()
    {
        return $this->belongsToMany(CommunityThread::class, 'community_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function createdThreads()
    {
        return $this->hasMany(CommunityThread::class, 'creator_id');
    }

    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class);
    }

    /**
     * Get masked phone or name for display.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->name) return $this->name;
        
        $phone = $this->phone_number;
        if (!$phone) return 'Anonymous';
        
        // Masking: +255712345678 -> +2557***678
        if (strlen($phone) > 10) {
            return substr($phone, 0, 5) . '***' . substr($phone, -3);
        }
        
        return substr($phone, 0, 3) . '***' . substr($phone, -2);
    }
}
