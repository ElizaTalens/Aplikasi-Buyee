<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
USE App\Models\Store;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        // 'phone',
        // 'birth_date',
        // 'gender',
        // 'profile_photo',
        // 'email_verified_at',
        // 'phone_verified_at',
        // 'wallet_balance',
        // 'gopay_balance',
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
            // 'phone_verified_at' => 'datetime',
            // 'birth_date' => 'date',
            // 'wallet_balance' => 'decimal:2',
            // 'gopay_balance' => 'decimal:2',
        ];
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    // public function addresses()
    // {
    //     return $this->hasMany(Address::class);
    // }
    // public function paymentMethods()
    // {
    //     return $this->hasMany(UserPaymentMethod::class);
    // }
    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }
    // public function cartItems()
    // {
    //     return $this->hasMany(CartItem::class);
    // }
    // public function wishlist()
    // {
    //     return $this->hasMany(WishlistItem::class);
    // }
    public function favoriteStores()
    {
        // Assuming you have a pivot table 'favorite_store_user' with 'user_id' and 'store_id'
        return $this->belongsToMany(Store::class, 'favorite_store_user', 'user_id', 'store_id');
    }
    // public function settings()
    // {
    //     return $this->hasOne(UserSetting::class);
    // }
    // public function notifications()
    // {
    //     return $this->hasMany(UserNotification::class);
    // }
    // public function isEmailVerified()
    // {
    //     return !is_null($this->email_verified_at);
    // }
    // public function isPhoneVerified()
    // {
    //     return !is_null($this->phone_verified_at);
    // }

    // /**
    //  * Get formatted birth date.
    //  */
    // public function getFormattedBirthDateAttribute()
    // {
    //     return $this->birth_date ? $this->birth_date->format('d F Y') : null;
    // }

    // /**
    //  * Get profile photo URL.
    //  */
    // public function getProfilePhotoUrlAttribute()
    // {
    //     if ($this->profile_photo) {
    //         return asset('storage/' . $this->profile_photo);
    //     }
        
    //     // Default avatar with user initials
    //     $initials = collect(explode(' ', $this->name))->map(function ($name) {
    //         return strtoupper(substr($name, 0, 1));
    //     })->implode('');
        
    //     return "https://via.placeholder.com/200x250/f8a9c2/ffffff?text=" . urlencode($initials);
    // }

    // /**
    //  * Get gender display text.
    //  */
    // public function getGenderDisplayAttribute()
    // {
    //     return match($this->gender) {
    //         'male' => 'Pria',
    //         'female' => 'Wanita',
    //         default => 'Tidak Diset'
    //     };
    // }

    // /**
    //  * Get unread notifications count.
    //  */
    // public function getUnreadNotificationsCountAttribute()
    // {
    //     try {
    //         return $this->notifications()->whereNull('read_at')->count();
    //     } catch (\Exception $e) {
    //         return 3; // Default fallback
    //     }
    // }

    // /**
    //  * Get cart items count.
    //  */
    // public function getCartItemsCountAttribute()
    // {
    //     try {
    //         return $this->cartItems()->sum('quantity');
    //     } catch (\Exception $e) {
    //         return 28; // Default fallback
    //     }
    // }
}
