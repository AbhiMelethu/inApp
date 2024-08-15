<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Constants here
     */
    
    /**
     * ROLE_SUPER_ADMIN
     * @var string
     */
    const ROLE_SUPER_ADMIN = 'Super Admin';
    /**
     * ROLE_USER
     * @var string
     */
    const ROLE_USER = 'User';

    /**
     * Get user role
     * @return string
     */
    public static function getRole()
    {
        $role = self::select('role')->where('user_id',Auth::user()->id)->first();
        return $role->role;
    }

}
