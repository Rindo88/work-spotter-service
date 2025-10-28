<?php



namespace App\Models;



use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
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

        'password',

        'phone',

        'profile_picture',

        'role'

    ];



    protected $hidden = [

        'password',

        'remember_token',

    ];



    protected function casts(): array

    {

        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Scope to get only admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope to get only regular users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Get the vendor associated with the user
     */
    public function vendor()

    {

        return $this->hasOne(Vendor::class);
    }

    /**
     * Get the reviews written by the user
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the checkins made by the user
     */
    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    /**
     * Get the chats where the user is involved
     */
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    // Relasi favorites
    public function favorites()
    {


        return $this->hasMany(Favorite::class);
    }





    // Favorit vendor saja


    public function vendorFavorites()


    {


        return $this->favorites()->vendorFavorites()->with('vendor');
    }





    // Favorit service saja


    public function serviceFavorites()


    {


        return $this->favorites()->serviceFavorites()->with('service.vendor');
    }





    // Check jika vendor sudah difavoritkan


    public function hasFavoritedVendor($vendorId)


    {


        return $this->favorites()


            ->where('vendor_id', $vendorId)


            ->whereNull('service_id')


            ->exists();
    }





    // Check jika service sudah difavoritkan


    public function hasFavoritedService($serviceId)


    {


        return $this->favorites()


            ->where('service_id', $serviceId)


            ->exists();
    }
}
