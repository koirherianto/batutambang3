<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use hasRoles;
    public $table = 'users';

    public $fillable = [
        'nama_lengkap',
        'nama_panggilan',
        'email',
        'is_approved',
        'email_verified_at',
        'password',
        'remember_token'
    ];

    protected $casts = [
        'nama_lengkap' => 'string',
        'nama_panggilan' => 'string',
        'email' => 'string',
        'is_approved' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string'
    ];

    public static array $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'nama_panggilan' => 'required|string|max:45',
        'email' => 'required|string|max:255',
        'is_approved' => 'nullable|boolean',
        'email_verified_at' => 'nullable',
        'password' => 'required|string|max:255',
        'remember_token' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function mekanikServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\MekanikService::class, 'user_id');
    }

    public function servicers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\MekanikService::class, 'mekanik_services_has_users');
    }
}
