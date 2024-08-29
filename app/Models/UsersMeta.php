<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersMeta extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'role_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'users_meta_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'users_meta_id', 'id')->with('file');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'users_meta_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isRole($roleName)
    {
        return $this->role->name === $roleName;
    }
}
