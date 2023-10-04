<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    public const SUPER_ADMIN_ROLE = 'super_admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_name',
        'status',
        'provider',
        'provider_id',
        'provider_token',
        'working_hours_start',
        'working_hours_end',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'provider_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'roles_name' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'roles_name', 'status', 'working_hours_start', 'working_hours_end'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                switch ($eventName) {
                    case 'created':
                        return 'انشاء مستخدم جديد';
                    case 'updated':
                        return 'تحديث بيانات مستخدم';
                    case 'deleted':
                        return 'حذف مستخدم';
                    default:
                        return "This model has been {$eventName}";
                }
            })
            ->useLogName('User');

    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'causer_id');
    }

    public function setProviderTokenAttribute($value)
    {
        $this->attributes['provider_token'] = Crypt::encryptString($value);
    }

    public function getProviderTokenAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
