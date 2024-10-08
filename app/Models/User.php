<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'specification_id',
        'name',
        'email',
        'password',
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
    ];

    public function dailyItemCode()
    {
        return $this->hasMany(DailyItemCode::class);
    }

    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }

    public function jobs()
    {
        return $this->hasOne(MachineJob::class, 'user_id');
    }

    public function hasRoleAccess($requiredRole)
    {
        // Get the role hierarchy from config
        $roleHierarchy = config('roles.hierarchy');

        // Get the current user's role
        $userRole = $this->specification->name;

        // Check if the user's role is allowed to access the required role
        return in_array($requiredRole, $roleHierarchy[$userRole]);
    }
}
